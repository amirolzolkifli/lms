<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainerEnrollmentController extends Controller
{
    /**
     * Display list of enrollments for trainer's courses
     */
    public function index(Request $request)
    {
        $trainer = Auth::user();

        // Get trainer's course IDs
        $courseIds = $trainer->courses()->pluck('id');

        // Build query
        $query = Enrollment::with(['course', 'user'])
            ->whereIn('course_id', $courseIds)
            ->orderBy('created_at', 'desc');

        // Filter by course
        if ($request->filled('course')) {
            $query->where('course_id', $request->course);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Search by student name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        $enrollments = $query->paginate(15)->withQueryString();

        // Get courses for filter dropdown
        $courses = $trainer->courses()->orderBy('title')->get();

        // Get stats
        $stats = [
            'total' => Enrollment::whereIn('course_id', $courseIds)->count(),
            'pending' => Enrollment::whereIn('course_id', $courseIds)->where('payment_status', 'pending')->count(),
            'paid' => Enrollment::whereIn('course_id', $courseIds)->where('payment_status', 'paid')->count(),
            'total_revenue' => Enrollment::whereIn('course_id', $courseIds)->where('payment_status', 'paid')->sum('amount'),
        ];

        return view('trainer.enrollments.index', compact('enrollments', 'courses', 'stats'));
    }

    /**
     * Show enrollment details
     */
    public function show(Enrollment $enrollment)
    {
        // Ensure trainer owns the course
        if ($enrollment->course->user_id !== Auth::id()) {
            abort(403);
        }

        $enrollment->load(['course', 'user']);

        return view('trainer.enrollments.show', compact('enrollment'));
    }

    /**
     * Approve manual payment
     */
    public function approve(Request $request, Enrollment $enrollment)
    {
        // Ensure trainer owns the course
        if ($enrollment->course->user_id !== Auth::id()) {
            abort(403);
        }

        // Only approve pending manual payments
        if ($enrollment->payment_status !== 'pending' || $enrollment->payment_method !== 'manual') {
            return back()->with('error', 'This enrollment cannot be approved.');
        }

        $enrollment->markAsPaid([
            'approved_by' => Auth::id(),
            'approved_at' => now()->toIso8601String(),
            'method' => 'manual',
        ]);

        return back()->with('success', 'Payment has been approved. Student is now enrolled.');
    }

    /**
     * Reject manual payment
     */
    public function reject(Request $request, Enrollment $enrollment)
    {
        // Ensure trainer owns the course
        if ($enrollment->course->user_id !== Auth::id()) {
            abort(403);
        }

        // Only reject pending payments
        if ($enrollment->payment_status !== 'pending') {
            return back()->with('error', 'This enrollment cannot be rejected.');
        }

        $request->validate([
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        $enrollment->update([
            'payment_status' => 'failed',
            'status' => 'cancelled',
            'payment_data' => [
                'rejected_by' => Auth::id(),
                'rejected_at' => now()->toIso8601String(),
                'rejection_reason' => $request->rejection_reason,
            ],
        ]);

        return back()->with('success', 'Payment has been rejected.');
    }
}
