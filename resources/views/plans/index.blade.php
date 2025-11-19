@extends('layouts.app')

@section('title', 'Choose Your Plan')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-2">Choose Your Plan</h2>
            <p class="text-gray-7">Select a plan that best fits your needs</p>
        </div>

        <div class="row justify-content-center">
            @foreach($plans as $plan)
            <div class="col-lg-5 col-md-6 mb-4">
                <div class="card h-100 {{ $plan->name === 'Pro Plan' ? 'border-primary' : '' }}">
                    @if($plan->name === 'Pro Plan')
                        <div class="card-header bg-primary text-white text-center">
                            <span class="badge bg-white text-primary">POPULAR</span>
                        </div>
                    @endif

                    <div class="card-body text-center p-4">
                        <h4 class="fw-bold mb-3">{{ $plan->name }}</h4>

                        <div class="mb-4">
                            <h2 class="fw-bold text-primary mb-0">
                                RM {{ number_format($plan->price_monthly, 2) }}
                            </h2>
                            <small class="text-muted">per month</small>
                        </div>

                        <ul class="list-unstyled text-start mb-4">
                            <li class="mb-3">
                                <i class="isax isax-tick-circle text-success me-2"></i>
                                <strong>{{ $plan->course_limit == 999999 ? 'Unlimited' : $plan->course_limit }}</strong> Courses
                            </li>
                            <li class="mb-3">
                                <i class="isax isax-tick-circle text-success me-2"></i>
                                <strong>{{ $plan->content_upload_limit == 999999 ? 'Unlimited' : $plan->content_upload_limit }}</strong> Content Files
                            </li>
                            <li class="mb-3">
                                <i class="isax isax-tick-circle text-success me-2"></i>
                                <strong>{{ $plan->student_limit == 999999 ? 'Unlimited' : $plan->student_limit }}</strong> Students
                            </li>
                            <li class="mb-3">
                                <i class="isax isax-tick-circle text-success me-2"></i>
                                Full Dashboard Access
                            </li>
                            <li class="mb-3">
                                <i class="isax isax-tick-circle text-success me-2"></i>
                                Email Support
                            </li>
                            @if($plan->name === 'Pro Plan')
                            <li class="mb-3">
                                <i class="isax isax-tick-circle text-success me-2"></i>
                                Priority Support
                            </li>
                            <li class="mb-3">
                                <i class="isax isax-tick-circle text-success me-2"></i>
                                Advanced Analytics
                            </li>
                            @endif
                        </ul>

                        <form action="{{ route('plans.select') }}" method="POST">
                            @csrf
                            <input type="hidden" name="plan" value="{{ $plan->name }}">
                            <button type="submit" class="btn {{ $plan->name === 'Pro Plan' ? 'btn-primary' : 'btn-secondary' }} btn-lg w-100">
                                @if($plan->price_monthly == 0)
                                    Get Started Free
                                @else
                                    Select Plan
                                @endif
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-4">
            <p class="text-muted">
                <i class="isax isax-shield-tick me-2"></i>
                All plans include SSL certificate and data protection
            </p>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.border-primary {
    border: 2px solid var(--bs-primary) !important;
}
</style>
@endpush
