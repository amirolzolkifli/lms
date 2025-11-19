<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Services\ChipPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PlanController extends Controller
{
    protected $chipPayment;

    public function __construct(ChipPaymentService $chipPayment)
    {
        $this->chipPayment = $chipPayment;
    }

    public function index()
    {
        $plans = Plan::whereIn('name', ['Basic Plan', 'Pro Plan'])->get();

        return view('plans.index', compact('plans'));
    }

    public function select(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:Basic Plan,Pro Plan'
        ]);

        $user = Auth::user();
        $planName = $request->plan;
        $plan = Plan::where('name', $planName)->first();

        if (!$plan) {
            return redirect()->back()->with('error', 'Plan not found.');
        }

        // If Basic Plan selected (free plan), assign it and redirect to dashboard
        if ($planName === 'Basic Plan') {
            $user->update([
                'plan' => 'Basic Plan',
                'validity' => null
            ]);

            return redirect()->route('dashboard')
                ->with('success', 'You have successfully subscribed to the Basic Plan!');
        }

        // If Pro Plan selected, create CHIP payment
        if ($planName === 'Pro Plan') {
            try {
                // Generate unique reference
                $reference = 'ORDER-' . strtoupper(Str::random(10));

                // Create purchase via CHIP
                $result = $this->chipPayment->createPurchase($user, $plan, $reference);

                if ($result && isset($result->checkout_url)) {
                    // Store purchase details in session
                    session([
                        'chip_purchase_id' => $result->id,
                        'chip_reference' => $reference,
                        'selected_plan' => $planName
                    ]);

                    // Redirect to CHIP checkout page
                    return redirect($result->checkout_url);
                } else {
                    return redirect()->back()
                        ->with('error', 'Unable to create payment. Please try again or contact support.');
                }

            } catch (\Exception $e) {
                \Log::error('Payment creation error: ' . $e->getMessage());

                return redirect()->back()
                    ->with('error', $e->getMessage());
            }
        }
    }
}
