@extends('layouts.app')

@section('title', 'Payment Successful')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card text-center">
            <div class="card-body py-5">
                <div class="mb-4">
                    <div class="avatar avatar-xxl bg-success-transparent rounded-circle mx-auto">
                        <i class="isax isax-tick-circle text-success" style="font-size: 64px;"></i>
                    </div>
                </div>

                <h2 class="fw-bold mb-3">Payment Successful!</h2>
                <p class="text-gray-7 mb-4">
                    Thank you for your payment. Your {{ $planName }} subscription is now active.
                </p>

                <div class="alert alert-success mb-4">
                    <div class="row text-start">
                        <div class="col-6">
                            <strong>Plan:</strong>
                        </div>
                        <div class="col-6">
                            {{ $planName }}
                        </div>
                        @if($validity)
                        <div class="col-6">
                            <strong>Valid Until:</strong>
                        </div>
                        <div class="col-6">
                            {{ $validity->format('d M Y') }}
                        </div>
                        @endif
                        @if($purchase && isset($purchase->payment->amount))
                        <div class="col-6">
                            <strong>Amount Paid:</strong>
                        </div>
                        <div class="col-6">
                            RM {{ number_format($purchase->payment->amount / 100, 2) }}
                        </div>
                        @endif
                        @if($purchase && isset($purchase->reference))
                        <div class="col-6">
                            <strong>Reference:</strong>
                        </div>
                        <div class="col-6">
                            {{ $purchase->reference }}
                        </div>
                        @endif
                    </div>
                </div>

                <div class="d-flex gap-3 justify-content-center">
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">
                        <i class="isax isax-home-2 me-2"></i>Go to Dashboard
                    </a>
                </div>

                <p class="text-muted mt-4 mb-0 fs-14">
                    <i class="isax isax-info-circle me-1"></i>
                    A confirmation email has been sent to your email address.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
