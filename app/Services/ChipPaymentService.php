<?php

namespace App\Services;

use Chip\ChipApi;
use Chip\Model\ClientDetails;
use Chip\Model\Purchase;
use Chip\Model\PurchaseDetails;
use Chip\Model\Product;

class ChipPaymentService
{
    protected $chip;
    protected $brandId;

    public function __construct()
    {
        $this->brandId = setting('chip_brand_id');
        $apiKey = setting('chip_api_key');

        if (!$this->brandId || !$apiKey) {
            throw new \Exception('CHIP payment gateway credentials not configured. Please configure in settings.');
        }

        $this->chip = new ChipApi($this->brandId, $apiKey);
    }

    /**
     * Create a purchase for a plan subscription
     *
     * @param \App\Models\User $user
     * @param \App\Models\Plan $plan
     * @param string $reference
     * @return object|null
     */
    public function createPurchase($user, $plan, $reference)
    {
        try {
            // Set client details
            $client = new ClientDetails();
            $client->email = $user->email;
            $client->full_name = $user->name;

            // Create product
            $product = new Product();
            $product->name = $plan->name . ' - Monthly Subscription';
            $product->price = (int)($plan->price_monthly * 100); // Convert to cents

            // Set purchase details
            $details = new PurchaseDetails();
            $details->products = [$product];

            // Create purchase object
            $purchase = new Purchase();
            $purchase->client = $client;
            $purchase->purchase = $details;
            $purchase->brand_id = $this->brandId;
            $purchase->currency = 'MYR';
            $purchase->reference = $reference;

            // Set redirect URLs
            $purchase->success_redirect = route('payment.success') . '?purchase_id={id}';
            $purchase->failure_redirect = route('payment.failed') . '?purchase_id={id}';

            // Set callback URL (webhook)
            $purchase->success_callback = route('webhook.chip');

            // Set expiry (1 hour from now)
            $purchase->due = time() + 3600;

            // Create the purchase
            $result = $this->chip->createPurchase($purchase);

            return $result;

        } catch (\Exception $e) {
            \Log::error('CHIP Payment Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get purchase details by ID
     *
     * @param string $purchaseId
     * @return object|null
     */
    public function getPurchase($purchaseId)
    {
        try {
            return $this->chip->getPurchase($purchaseId);
        } catch (\Exception $e) {
            \Log::error('CHIP Get Purchase Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Verify webhook signature
     *
     * @param array $data
     * @return bool
     */
    public function verifyWebhook($data)
    {
        try {
            return $this->chip->verify($data);
        } catch (\Exception $e) {
            \Log::error('CHIP Webhook Verification Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get available payment methods
     *
     * @return array
     */
    public function getPaymentMethods()
    {
        try {
            return $this->chip->getPaymentMethods();
        } catch (\Exception $e) {
            \Log::error('CHIP Get Payment Methods Error: ' . $e->getMessage());
            return [];
        }
    }
}
