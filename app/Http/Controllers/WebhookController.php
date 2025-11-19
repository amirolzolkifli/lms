<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ChipPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    protected $chipPayment;

    public function __construct(ChipPaymentService $chipPayment)
    {
        $this->chipPayment = $chipPayment;
    }

    /**
     * Handle CHIP webhook callback
     */
    public function handleChipWebhook(Request $request)
    {
        // Get webhook payload
        $payload = file_get_contents('php://input');
        $data = json_decode($payload, true);

        Log::info('CHIP Webhook received', ['data' => $data]);

        // Verify webhook signature
        if (!$this->chipPayment->verifyWebhook($data)) {
            Log::error('CHIP Webhook verification failed');
            return response('Invalid signature', 403);
        }

        // Get event type
        $event = $data['event_type'] ?? null;
        $purchaseId = $data['id'] ?? null;
        $reference = $data['reference'] ?? null;
        $status = $data['status'] ?? null;

        Log::info('CHIP Webhook verified', [
            'event' => $event,
            'purchase_id' => $purchaseId,
            'reference' => $reference,
            'status' => $status
        ]);

        // Handle purchase.paid event
        if ($event === 'purchase.paid' && $status === 'paid') {
            $this->handleSuccessfulPayment($data);
        }
        // Handle purchase.payment_failure event
        elseif ($event === 'purchase.payment_failure') {
            $this->handleFailedPayment($data);
        }
        // Handle purchase.cancelled event
        elseif ($event === 'purchase.cancelled') {
            $this->handleCancelledPayment($data);
        }

        // Always return 200 OK to acknowledge receipt
        return response('OK', 200);
    }

    /**
     * Handle successful payment
     */
    protected function handleSuccessfulPayment($data)
    {
        $purchaseId = $data['id'];
        $reference = $data['reference'];
        $clientEmail = $data['client']['email'] ?? null;
        $amount = $data['payment']['amount'] ?? 0;

        Log::info('Processing successful payment', [
            'purchase_id' => $purchaseId,
            'reference' => $reference,
            'email' => $clientEmail,
            'amount' => $amount
        ]);

        // Find user by email
        $user = User::where('email', $clientEmail)->first();

        if (!$user) {
            Log::error('User not found for payment', ['email' => $clientEmail]);
            return;
        }

        // Update user's plan to Pro Plan
        $user->update([
            'plan' => 'Pro Plan',
            'validity' => now()->addMonth() // 1 month validity
        ]);

        Log::info('User plan updated successfully', [
            'user_id' => $user->id,
            'plan' => 'Pro Plan',
            'validity' => $user->validity
        ]);

        // TODO: Send confirmation email to user
        // TODO: Store transaction record in database
    }

    /**
     * Handle failed payment
     */
    protected function handleFailedPayment($data)
    {
        $purchaseId = $data['id'];
        $reference = $data['reference'];

        Log::warning('Payment failed', [
            'purchase_id' => $purchaseId,
            'reference' => $reference
        ]);

        // TODO: Notify user about failed payment
        // TODO: Store failed transaction record
    }

    /**
     * Handle cancelled payment
     */
    protected function handleCancelledPayment($data)
    {
        $purchaseId = $data['id'];
        $reference = $data['reference'];

        Log::info('Payment cancelled', [
            'purchase_id' => $purchaseId,
            'reference' => $reference
        ]);

        // TODO: Store cancelled transaction record
    }
}
