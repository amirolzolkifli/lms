<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Services\TrainerChipPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EnrollmentWebhookController extends Controller
{
    /**
     * Handle CHIP payment callback (redirect from CHIP)
     */
    public function callback(Request $request, $orderReference, $status)
    {
        $enrollment = Enrollment::where('order_reference', $orderReference)->firstOrFail();
        $enrollment->load(['course', 'course.trainer']);

        // For success, verify with CHIP API
        if ($status === 'success' && $enrollment->chip_purchase_id) {
            try {
                $chipService = new TrainerChipPaymentService($enrollment->course->trainer);
                $purchase = $chipService->getPurchase($enrollment->chip_purchase_id);

                if ($purchase['status'] === 'paid') {
                    $enrollment->markAsPaid($purchase);
                    return redirect()->route('enrollment.success', $enrollment->order_reference)
                        ->with('success', 'Payment successful! You are now enrolled.');
                }
            } catch (\Exception $e) {
                Log::error('CHIP callback verification failed', [
                    'enrollment_id' => $enrollment->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        if ($status === 'failed') {
            $enrollment->update(['payment_status' => 'failed']);
            return redirect()->route('enrollment.failed', $enrollment->order_reference);
        }

        if ($status === 'cancelled') {
            $enrollment->update(['payment_status' => 'cancelled']);
            return redirect()->route('courses.show', $enrollment->course)
                ->with('info', 'Payment was cancelled.');
        }

        // If we can't verify success, show pending
        return redirect()->route('enrollment.pending', $enrollment->order_reference);
    }

    /**
     * Handle CHIP webhook (server-to-server notification)
     */
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $data = json_decode($payload, true);

        Log::info('CHIP webhook received', ['data' => $data]);

        if (!isset($data['id']) || !isset($data['status'])) {
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        // Find enrollment by CHIP purchase ID
        $enrollment = Enrollment::where('chip_purchase_id', $data['id'])->first();

        if (!$enrollment) {
            Log::warning('Enrollment not found for CHIP purchase', ['purchase_id' => $data['id']]);
            return response()->json(['error' => 'Enrollment not found'], 404);
        }

        // Update enrollment based on status
        switch ($data['status']) {
            case 'paid':
                if ($enrollment->payment_status !== 'paid') {
                    $enrollment->markAsPaid($data);
                    Log::info('Enrollment marked as paid via webhook', ['enrollment_id' => $enrollment->id]);
                }
                break;

            case 'failed':
            case 'error':
                $enrollment->update([
                    'payment_status' => 'failed',
                    'payment_data' => $data,
                ]);
                break;

            case 'cancelled':
            case 'expired':
                $enrollment->update([
                    'payment_status' => 'cancelled',
                    'payment_data' => $data,
                ]);
                break;
        }

        return response()->json(['success' => true]);
    }
}
