@extends('layouts.welcome')

@section('title', 'Payment Pending')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb-bar text-center">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-12">
                <h2 class="breadcrumb-title mb-2">Payment Pending</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">Courses</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Payment Pending</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- /Breadcrumb -->

<!-- Pending Section -->
<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card text-center">
                    <div class="card-body p-5">
                        <div class="mb-4">
                            <div class="pending-icon mx-auto" style="width: 100px; height: 100px; background: rgba(255, 159, 67, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="isax isax-clock text-warning" style="font-size: 50px;"></i>
                            </div>
                        </div>

                        <h3 class="mb-3">Payment Verification Pending</h3>
                        <p class="text-muted mb-4">
                            Your payment proof has been submitted for <strong>{{ $course->title }}</strong>.
                            Please wait while the trainer verifies your payment.
                        </p>

                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <table class="table table-borderless mb-0">
                                    <tr>
                                        <td class="text-muted">Order Reference:</td>
                                        <td class="text-end"><strong>{{ $enrollment->order_reference }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Course:</td>
                                        <td class="text-end"><strong>{{ $course->title }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Amount:</td>
                                        <td class="text-end"><strong>RM{{ number_format($enrollment->amount, 2) }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Payment Method:</td>
                                        <td class="text-end"><strong>Bank Transfer</strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Status:</td>
                                        <td class="text-end">
                                            <span class="badge bg-warning text-dark">Pending Verification</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="alert alert-info text-start mb-4">
                            <i class="isax isax-info-circle me-2"></i>
                            <strong>What happens next?</strong>
                            <ul class="mb-0 mt-2">
                                <li>The trainer will verify your payment proof</li>
                                <li>You will receive an email once verified</li>
                                <li>This usually takes 1-2 business days</li>
                            </ul>
                        </div>

                        @if($enrollment->payment_proof)
                        <div class="mb-4">
                            <p class="text-muted mb-2"><small>Payment proof submitted:</small></p>
                            <img src="{{ Storage::url($enrollment->payment_proof) }}" alt="Payment Proof" class="img-fluid rounded" style="max-height: 200px;">
                        </div>
                        @endif

                        <div class="d-grid gap-2">
                            <a href="{{ route('courses.show', $course) }}" class="btn btn-outline-primary">
                                <i class="isax isax-book me-2"></i>View Course Details
                            </a>
                            <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary">
                                Browse More Courses
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /Pending Section -->
@endsection
