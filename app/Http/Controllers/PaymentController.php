<?php

namespace App\Http\Controllers;

use App\Services\ChipPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    protected $chipPayment;

    public function __construct(ChipPaymentService $chipPayment)
    {
        $this->chipPayment = $chipPayment;
    }

    /**
     * Payment success page
     */
    public function success(Request $request)
    {
        $purchaseId = $request->get('purchase_id');

        // Get user's current plan info
        $user = Auth::user();
        $planName = $user->plan ?? 'Pro Plan';
        $validity = $user->validity;

        // Only try to get purchase if we have a valid purchase ID (not the placeholder)
        $purchase = null;
        if ($purchaseId && $purchaseId !== '{id}') {
            $purchase = $this->chipPayment->getPurchase($purchaseId);
        }

        return view('payment.success', compact('purchase', 'planName', 'validity'));
    }

    /**
     * Payment failed page
     */
    public function failed(Request $request)
    {
        $purchaseId = $request->get('purchase_id');

        // Only try to get purchase if we have a valid purchase ID (not the placeholder)
        $purchase = null;
        if ($purchaseId && $purchaseId !== '{id}') {
            $purchase = $this->chipPayment->getPurchase($purchaseId);
        }

        return view('payment.failed', compact('purchase'));
    }
}
