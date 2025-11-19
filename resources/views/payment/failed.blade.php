@extends('layouts.app')

@section('title', 'Payment Failed')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card text-center">
            <div class="card-body py-5">
                <div class="mb-4">
                    <div class="avatar avatar-xxl bg-danger-transparent rounded-circle mx-auto">
                        <i class="isax isax-close-circle text-danger" style="font-size: 64px;"></i>
                    </div>
                </div>

                <h2 class="fw-bold mb-3">Payment Failed</h2>
                <p class="text-gray-7 mb-4">
                    Unfortunately, your payment could not be processed. Please try again.
                </p>

                @if($purchase)
                <div class="alert alert-warning mb-4 text-start">
                    <p class="mb-2"><strong>Possible reasons:</strong></p>
                    <ul class="mb-0">
                        <li>Insufficient funds</li>
                        <li>Incorrect payment details</li>
                        <li>Payment timeout</li>
                        <li>Bank declined the transaction</li>
                    </ul>
                </div>
                @endif

                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="{{ route('plans.index') }}" class="btn btn-primary">
                        <i class="isax isax-refresh me-2"></i>Try Again
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                        <i class="isax isax-home-2 me-2"></i>Back to Dashboard
                    </a>
                </div>

                <p class="text-muted mt-4 mb-0 fs-14">
                    <i class="isax isax-info-circle me-1"></i>
                    Need help? Contact us at {{ setting('contact_email', 'support@example.com') }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
