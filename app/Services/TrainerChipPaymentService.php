<?php

namespace App\Services;

use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TrainerChipPaymentService
{
    protected User $trainer;
    protected string $brandId;
    protected string $apiKey;
    protected string $baseUrl = 'https://gate.chip-in.asia/api/v1';

    public function __construct(User $trainer)
    {
        $this->trainer = $trainer;
        $this->brandId = $trainer->getTrainerSetting('chip_brand_id');
        $this->apiKey = $trainer->getTrainerSetting('chip_api_key');

        if (empty($this->brandId) || empty($this->apiKey)) {
            throw new \Exception('CHIP payment credentials not configured for this trainer.');
        }
    }

    /**
     * Create a CHIP purchase for course enrollment
     */
    public function createPurchase(Enrollment $enrollment, string $email, string $name): string
    {
        $course = $enrollment->course;

        $payload = [
            'brand_id' => $this->brandId,
            'client' => [
                'email' => $email,
                'full_name' => $name,
            ],
            'purchase' => [
                'currency' => 'MYR',
                'products' => [
                    [
                        'name' => $course->title,
                        'price' => (int) ($enrollment->amount * 100), // Convert to cents
                        'quantity' => 1,
                    ],
                ],
            ],
            'success_redirect' => route('enrollment.chip.callback', ['enrollment' => $enrollment->order_reference, 'status' => 'success']),
            'failure_redirect' => route('enrollment.chip.callback', ['enrollment' => $enrollment->order_reference, 'status' => 'failed']),
            'cancel_redirect' => route('enrollment.chip.callback', ['enrollment' => $enrollment->order_reference, 'status' => 'cancelled']),
            'success_callback' => route('enrollment.chip.webhook'),
            'reference' => $enrollment->order_reference,
        ];

        $response = Http::withBasicAuth($this->apiKey, '')
            ->post("{$this->baseUrl}/purchases/", $payload);

        if (!$response->successful()) {
            Log::error('CHIP purchase creation failed', [
                'enrollment_id' => $enrollment->id,
                'response' => $response->json(),
            ]);
            throw new \Exception('Failed to create payment. Please try again.');
        }

        $data = $response->json();

        // Store the CHIP purchase ID
        $enrollment->update([
            'chip_purchase_id' => $data['id'],
        ]);

        return $data['checkout_url'];
    }

    /**
     * Verify purchase status from CHIP
     */
    public function getPurchase(string $purchaseId): array
    {
        $response = Http::withBasicAuth($this->apiKey, '')
            ->get("{$this->baseUrl}/purchases/{$purchaseId}/");

        if (!$response->successful()) {
            throw new \Exception('Failed to retrieve purchase information.');
        }

        return $response->json();
    }

    /**
     * Verify webhook signature
     */
    public static function verifyWebhookSignature(string $payload, string $signature, string $publicKey): bool
    {
        try {
            $publicKeyResource = openssl_pkey_get_public($publicKey);
            if (!$publicKeyResource) {
                return false;
            }

            $result = openssl_verify(
                $payload,
                base64_decode($signature),
                $publicKeyResource,
                OPENSSL_ALGO_SHA256
            );

            return $result === 1;
        } catch (\Exception $e) {
            Log::error('Webhook signature verification failed', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
