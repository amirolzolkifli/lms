# CHIP Payment Gateway Integration Guide

## Overview

CHIP is a Digital Finance Platform for both individual and business merchants to collect payment from customers via online banking, cards, eWallets, and POS Terminals in Malaysia.

**Official Documentation:** https://developer.chip-in.asia/
**GitHub PHP SDK:** https://github.com/CHIPAsia/chip-php-sdk
**Portal:** https://portal.chip-in.asia/

---

## Authentication

### Base API Endpoint
```
https://gate.chip-in.asia/api/v1/
```

### Required Credentials
1. **API Key** - Obtained from [Developers Section](https://portal.chip-in.asia/collect/developers/api-keys)
2. **Brand ID** - Obtained from [Brands Section](https://portal.chip-in.asia/collect/developers/brands)

### Authorization Header
All API requests must include:
```
Authorization: Bearer <your-secret-api-key>
```

⚠️ **Important:** Integration must be done server-side only for security reasons.

---

## Installation (PHP)

### Requirements
- PHP 8.0+
- PHP extensions: `curl`, `json`, `openssl`

### Composer Installation
Add to your `composer.json`:
```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "git@github.com:CHIPAsia/chip-php-sdk.git"
    }
  ],
  "require": {
    "chip/chip-sdk-php": "^1.0"
  }
}
```

Then run:
```bash
composer update
```

---

## Payment Flow

### Step 1: Create Purchase
Your application creates a purchase request to CHIP API.

### Step 2: Get Checkout URL
CHIP returns a checkout URL where the customer will complete payment.

### Step 3: Redirect Customer
Redirect customer to the CHIP checkout page.

### Step 4: Customer Pays
Customer completes payment on CHIP's secure payment page.

### Step 5: Callback/Webhook
CHIP sends webhook to your server (server-to-server) to notify payment status.

### Step 6: Redirect Customer
CHIP redirects customer back to your success/failure URL.

### Step 7: Verify Payment
Your application verifies the payment status using the webhook data.

---

## Creating a Purchase

### Basic Implementation Example

```php
<?php

use Chip\ChipApi;
use Chip\Model\ClientDetails;
use Chip\Model\Purchase;
use Chip\Model\PurchaseDetails;
use Chip\Model\Product;

// Initialize CHIP API
$chip = new ChipApi(
    $brandId,      // Your Brand ID
    $apiKey,       // Your API Key
    $endpoint      // API endpoint (optional, defaults to production)
);

// Set client details
$client = new ClientDetails();
$client->email = 'customer@example.com';
$client->full_name = 'John Doe'; // Optional

// Create product(s)
$product = new Product();
$product->name = 'Pro Plan Subscription';
$product->price = 9900; // Price in cents (RM 99.00)

// Set purchase details
$details = new PurchaseDetails();
$details->products = [$product];

// Create purchase object
$purchase = new Purchase();
$purchase->client = $client;
$purchase->purchase = $details;
$purchase->brand_id = $brandId;
$purchase->currency = 'MYR'; // Malaysia Ringgit

// Set redirect URLs (where customer is sent after payment)
$purchase->success_redirect = 'https://yourdomain.com/payment/success';
$purchase->failure_redirect = 'https://yourdomain.com/payment/failed';

// Set callback URL (webhook - server to server notification)
$purchase->success_callback = 'https://yourdomain.com/webhook/chip';

// Additional optional fields
$purchase->reference = 'ORDER-12345'; // Your internal reference
$purchase->due = time() + 3600; // Payment expiry timestamp (1 hour from now)

// Create the purchase
$result = $chip->createPurchase($purchase);

// Check if successful and redirect to checkout
if ($result && isset($result->checkout_url)) {
    // Store purchase ID for later verification
    $purchaseId = $result->id;

    // Redirect customer to CHIP checkout page
    header("Location: " . $result->checkout_url);
    exit;
} else {
    // Handle error
    die('Error creating purchase');
}
```

### Purchase Object Fields

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `client` | Object | Yes | Client/customer details |
| `client.email` | String | Yes | Customer email |
| `client.full_name` | String | No | Customer full name |
| `purchase` | Object | Yes | Purchase details |
| `purchase.products` | Array | Yes | Array of products |
| `purchase.products[].name` | String | Yes | Product name |
| `purchase.products[].price` | Integer | Yes | Price in cents (e.g., 9900 = RM99.00) |
| `brand_id` | String | Yes | Your Brand ID |
| `currency` | String | No | Currency code (default: MYR) |
| `success_redirect` | String | Yes | URL to redirect after successful payment |
| `failure_redirect` | String | Yes | URL to redirect after failed payment |
| `success_callback` | String | Yes | Webhook URL for payment notification |
| `reference` | String | No | Your internal order/reference number |
| `due` | Integer | No | Unix timestamp for payment expiry |

---

## Webhooks (Callbacks)

### Available Webhook Events

CHIP provides 21 webhook events. Key purchase-related events:

1. **Purchase Paid** - Payment completed successfully
2. **Purchase Payment Failure** - Payment failed
3. **Purchase Cancelled** - Purchase was cancelled
4. **Purchase Hold** - Payment on hold
5. **Purchase Captured** - Payment captured (for pre-auth)
6. **Purchase Released** - Payment released
7. **Purchase Preauthorized** - Payment pre-authorized

### Webhook Setup

1. Go to [CHIP Portal](https://portal.chip-in.asia/)
2. Navigate to Developers > Webhooks
3. Add your webhook callback URL: `https://yourdomain.com/webhook/chip`
4. Select events you want to receive (e.g., "Purchase Paid")

### Handling Webhooks

```php
<?php

use Chip\ChipApi;

// Initialize CHIP API
$chip = new ChipApi($brandId, $apiKey);

// Get webhook payload
$payload = file_get_contents('php://input');
$data = json_decode($payload, true);

// Verify the webhook signature (important for security)
$isValid = $chip->verify($data);

if (!$isValid) {
    http_response_code(403);
    die('Invalid signature');
}

// Get event type
$event = $data['event_type'] ?? null;

switch ($event) {
    case 'purchase.paid':
        // Payment successful
        $purchaseId = $data['id'];
        $status = $data['status']; // Should be 'paid'
        $amount = $data['payment']['amount']; // Amount in cents
        $reference = $data['reference']; // Your order reference

        // Update your database - mark order as paid
        // Send confirmation email
        // Activate subscription, etc.

        handleSuccessfulPayment($purchaseId, $reference);
        break;

    case 'purchase.payment_failure':
        // Payment failed
        $purchaseId = $data['id'];
        $reference = $data['reference'];

        handleFailedPayment($purchaseId, $reference);
        break;

    case 'purchase.cancelled':
        // Purchase cancelled
        handleCancelledPayment($data['id'], $data['reference']);
        break;

    default:
        // Handle other events
        break;
}

// Always return 200 OK to acknowledge receipt
http_response_code(200);
echo 'OK';
```

### Webhook Payload Example (Purchase Paid)

```json
{
  "id": "purchase_abc123xyz",
  "event_type": "purchase.paid",
  "status": "paid",
  "created_at": 1234567890,
  "reference": "ORDER-12345",
  "brand_id": "brand_xyz",
  "client": {
    "email": "customer@example.com",
    "full_name": "John Doe"
  },
  "purchase": {
    "currency": "MYR",
    "products": [
      {
        "name": "Pro Plan Subscription",
        "price": 9900
      }
    ]
  },
  "payment": {
    "amount": 9900,
    "currency": "MYR",
    "status": "paid",
    "paid_at": 1234567890
  },
  "checkout_url": "https://gate.chip-in.asia/payment/abc123xyz",
  "success_redirect": "https://yourdomain.com/payment/success",
  "failure_redirect": "https://yourdomain.com/payment/failed",
  "success_callback": "https://yourdomain.com/webhook/chip"
}
```

---

## Getting Purchase Details

### Retrieve Purchase by ID

```php
<?php

use Chip\ChipApi;

$chip = new ChipApi($brandId, $apiKey);

// Get purchase details
$purchase = $chip->getPurchase($purchaseId);

if ($purchase) {
    $status = $purchase->status; // paid, pending, failed, etc.
    $amount = $purchase->payment->amount;

    if ($status === 'paid') {
        // Payment confirmed
    }
}
```

### Purchase Status Values

- `pending` - Awaiting payment
- `paid` - Payment completed successfully
- `cancelled` - Purchase cancelled
- `expired` - Payment expired (past due date)
- `failed` - Payment failed
- `hold` - Payment on hold
- `refunded` - Payment refunded

---

## Redirect URLs

### Success Redirect
URL where customer is redirected after successful payment.

**Example:** `https://yourdomain.com/payment/success?purchase_id={id}`

The `{id}` placeholder will be replaced with the actual purchase ID by CHIP.

### Failure Redirect
URL where customer is redirected after failed payment.

**Example:** `https://yourdomain.com/payment/failed?purchase_id={id}`

### Handling Redirects

```php
<?php
// success.php

$purchaseId = $_GET['purchase_id'] ?? null;

if (!$purchaseId) {
    die('Invalid request');
}

// Verify payment status from your database
// Don't rely solely on the redirect - always verify via webhook
$order = getOrderByPurchaseId($purchaseId);

if ($order && $order->status === 'paid') {
    // Show success message
    echo "Payment successful! Thank you for your purchase.";
} else {
    // Payment not confirmed yet
    echo "Processing your payment...";
}
```

⚠️ **Important:** Never trust redirect URLs alone. Always verify payment status using webhooks or by calling the API to get purchase details.

---

## Security Best Practices

### 1. Always Verify Webhooks
```php
$isValid = $chip->verify($webhookData);
if (!$isValid) {
    http_response_code(403);
    die('Invalid signature');
}
```

### 2. Store API Keys Securely
- Never commit API keys to version control
- Use environment variables
- Keep keys in `.env` file (excluded from git)

### 3. Use HTTPS
- All callback/webhook URLs must use HTTPS
- Redirect URLs should use HTTPS

### 4. Validate Amounts
- Always verify the payment amount matches your expected amount
- Store expected amounts in your database

### 5. Handle Idempotency
- Webhooks may be sent multiple times
- Check if you've already processed a payment before updating your database

```php
// Check if already processed
if ($order->status === 'paid') {
    // Already processed, just return OK
    http_response_code(200);
    echo 'OK';
    exit;
}

// Process payment
updateOrderStatus($orderId, 'paid');
```

---

## Testing

### Sandbox Environment
CHIP provides a sandbox environment for testing.

**Sandbox Endpoint:** Check CHIP documentation for sandbox URL

### Test Cards/Methods
Contact CHIP support for test payment methods and credentials.

---

## Get Payment Methods

```php
<?php

use Chip\ChipApi;

$chip = new ChipApi($brandId, $apiKey);

// Get available payment methods
$methods = $chip->getPaymentMethods();

// Returns array of available payment methods:
// - FPX (Online Banking)
// - Credit/Debit Cards
// - eWallets (Boost, GrabPay, TouchNGo, etc.)
```

---

## Error Handling

### Common Errors

| Error | Description | Solution |
|-------|-------------|----------|
| 401 Unauthorized | Invalid API key | Check your API key |
| 403 Forbidden | Invalid brand ID | Check your brand ID |
| 422 Validation Error | Invalid parameters | Check required fields |
| 500 Server Error | CHIP server error | Retry request or contact support |

### Error Response Example

```json
{
  "error": {
    "type": "validation_error",
    "message": "Invalid email format",
    "field": "client.email"
  }
}
```

---

## Implementation Checklist

- [ ] Obtain API Key from CHIP Portal
- [ ] Obtain Brand ID from CHIP Portal
- [ ] Install CHIP PHP SDK via Composer
- [ ] Configure environment variables for API credentials
- [ ] Implement create purchase functionality
- [ ] Set up webhook endpoint
- [ ] Implement webhook verification
- [ ] Handle "Purchase Paid" event
- [ ] Set up success/failure redirect pages
- [ ] Test payment flow in sandbox
- [ ] Implement error handling
- [ ] Add logging for webhooks
- [ ] Test idempotency
- [ ] Go live with production credentials

---

## Support & Resources

- **Developer Portal:** https://developer.chip-in.asia/
- **Merchant Portal:** https://portal.chip-in.asia/
- **PHP SDK:** https://github.com/CHIPAsia/chip-php-sdk
- **Blog/Guides:** https://blog.chip-in.asia/
- **Support:** Contact via CHIP Portal

---

## Integration Flow Summary

```
1. User selects plan on your website
   ↓
2. Your server creates purchase via CHIP API
   ↓
3. CHIP returns checkout_url
   ↓
4. Redirect user to checkout_url
   ↓
5. User completes payment on CHIP's page
   ↓
6. CHIP sends webhook to your success_callback URL
   ↓
7. Your server verifies webhook and updates database
   ↓
8. CHIP redirects user to success_redirect URL
   ↓
9. User sees success message on your website
```

---

## Laravel Implementation Notes

For Laravel integration:

1. Store credentials in `.env`:
```env
CHIP_BRAND_ID=your_brand_id
CHIP_API_KEY=your_api_key
CHIP_ENDPOINT=https://gate.chip-in.asia/api/v1/
```

2. Add to `config/services.php`:
```php
'chip' => [
    'brand_id' => env('CHIP_BRAND_ID'),
    'api_key' => env('CHIP_API_KEY'),
    'endpoint' => env('CHIP_ENDPOINT'),
],
```

3. Create service class or use SDK directly in controllers

4. Create webhook route with CSRF exemption:
```php
// app/Http/Middleware/VerifyCsrfToken.php
protected $except = [
    'webhook/chip',
];
```

---

*Last Updated: 2025-01-19*
