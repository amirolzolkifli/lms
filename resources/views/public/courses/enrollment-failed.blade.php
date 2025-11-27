@extends('layouts.welcome')

@section('title', 'Payment Failed')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb-bar text-center">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-12">
                <h2 class="breadcrumb-title mb-2">Payment Failed</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">Courses</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Payment Failed</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- /Breadcrumb -->

<!-- Failed Section -->
<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card text-center">
                    <div class="card-body p-5">
                        <div class="mb-4">
                            <div class="failed-icon mx-auto" style="width: 100px; height: 100px; background: rgba(234, 84, 85, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="isax isax-close-circle text-danger" style="font-size: 50px;"></i>
                            </div>
                        </div>

                        <h3 class="mb-3">Payment Failed</h3>
                        <p class="text-muted mb-4">
                            Unfortunately, your payment for <strong>{{ $course->title }}</strong> could not be processed.
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
                                        <td class="text-muted">Status:</td>
                                        <td class="text-end">
                                            <span class="badge bg-danger">Failed</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="alert alert-warning text-start mb-4">
                            <i class="isax isax-warning-2 me-2"></i>
                            <strong>Common reasons for payment failure:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Insufficient funds</li>
                                <li>Card declined by issuing bank</li>
                                <li>Payment session expired</li>
                                <li>Network or connectivity issues</li>
                            </ul>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="{{ route('courses.checkout', $course) }}" class="btn btn-primary">
                                <i class="isax isax-refresh me-2"></i>Try Again
                            </a>
                            <a href="{{ route('courses.show', $course) }}" class="btn btn-outline-secondary">
                                View Course Details
                            </a>
                        </div>

                        <p class="mt-4 text-muted small">
                            Need help? Contact the trainer at <a href="mailto:{{ $course->trainer->email }}">{{ $course->trainer->email }}</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /Failed Section -->
@endsection
