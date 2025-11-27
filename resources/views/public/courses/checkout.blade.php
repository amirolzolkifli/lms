@extends('layouts.welcome')

@section('title', 'Checkout - ' . $course->title)

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb-bar text-center">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-12">
                <h2 class="breadcrumb-title mb-2">Checkout</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">Courses</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('courses.show', $course) }}">{{ Str::limit($course->title, 20) }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- /Breadcrumb -->

<!-- Checkout Section -->
<section class="section checkout-section">
    <div class="container">
        <div class="row">
            <!-- Checkout Form -->
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Enrollment Details</h5>
                    </div>
                    <div class="card-body">
                        @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        <form action="{{ route('enrollment.process', $course) }}" method="POST" enctype="multipart/form-data" id="checkout-form">
                            @csrf

                            @guest
                            <!-- New Student Registration -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0">Create Your Account</h6>
                                    <a href="{{ route('login', ['redirect' => url()->current()]) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="isax isax-login me-1"></i>Already have account? Login
                                    </a>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                        @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}">
                                    @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                        @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Minimum 8 characters</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                    </div>
                                </div>

                                <div class="alert alert-info">
                                    <small><i class="isax isax-info-circle me-1"></i> An account will be created for you to access your enrolled courses.</small>
                                </div>
                            </div>
                            @else
                            <!-- Logged in User Info -->
                            <div class="mb-4">
                                <h6 class="mb-3">Your Information</h6>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Full Name</label>
                                        <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly disabled>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email Address</label>
                                        <input type="email" class="form-control" value="{{ Auth::user()->email }}" readonly disabled>
                                    </div>
                                </div>

                                <div class="alert alert-success mb-0">
                                    <i class="isax isax-user-tick me-2"></i>
                                    You are logged in and ready to enroll.
                                </div>
                            </div>
                            @endguest

                            <hr class="my-4">

                            <!-- Payment Method -->
                            <div class="mb-4">
                                <h6 class="mb-3">Payment Method</h6>

                                @if($course->price == 0)
                                <!-- Free Course -->
                                <input type="hidden" name="payment_method" value="free">
                                <div class="alert alert-success">
                                    <i class="isax isax-tick-circle me-2"></i>
                                    This is a free course. Click "Enroll Now" to get instant access.
                                </div>
                                @else
                                <!-- Paid Course Payment Options -->
                                <div class="payment-methods">
                                    @if($hasChipPayment)
                                    <div class="form-check payment-option mb-3">
                                        <input class="form-check-input" type="radio" name="payment_method" id="payment_chip" value="chip" {{ old('payment_method', 'chip') === 'chip' ? 'checked' : '' }}>
                                        <label class="form-check-label d-flex align-items-center" for="payment_chip">
                                            <span class="payment-icon me-3">
                                                <i class="isax isax-card fs-4 text-primary"></i>
                                            </span>
                                            <span>
                                                <strong>Online Payment</strong><br>
                                                <small class="text-muted">Pay securely with credit/debit card or online banking</small>
                                            </span>
                                        </label>
                                    </div>
                                    @endif

                                    @if($hasManualPayment)
                                    <div class="form-check payment-option mb-3">
                                        <input class="form-check-input" type="radio" name="payment_method" id="payment_manual" value="manual" {{ old('payment_method') === 'manual' ? 'checked' : '' }}>
                                        <label class="form-check-label d-flex align-items-center" for="payment_manual">
                                            <span class="payment-icon me-3">
                                                <i class="isax isax-bank fs-4 text-warning"></i>
                                            </span>
                                            <span>
                                                <strong>Bank Transfer</strong><br>
                                                <small class="text-muted">Transfer to trainer's bank account and upload proof</small>
                                            </span>
                                        </label>
                                    </div>

                                    <!-- Bank Transfer Details -->
                                    <div id="manual-payment-details" style="{{ old('payment_method') === 'manual' ? '' : 'display: none;' }}" class="mt-3 ms-4">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h6 class="mb-3">Bank Transfer Details</h6>
                                                <table class="table table-sm table-borderless mb-3">
                                                    <tr>
                                                        <td class="text-muted" width="120">Bank Name:</td>
                                                        <td><strong>{{ $bankDetails['bank_name'] }}</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Account Name:</td>
                                                        <td><strong>{{ $bankDetails['account_name'] }}</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Account No:</td>
                                                        <td><strong>{{ $bankDetails['account_number'] }}</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Amount:</td>
                                                        <td><strong class="text-success">RM{{ number_format($course->price, 2) }}</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Reference:</td>
                                                        <td><strong>{{ Auth::check() ? Auth::user()->email : 'Your Email' }}</strong></td>
                                                    </tr>
                                                </table>

                                                <div class="mb-3">
                                                    <label for="payment_proof" class="form-label">Upload Payment Proof <span class="text-danger">*</span></label>
                                                    <input type="file" class="form-control @error('payment_proof') is-invalid @enderror" id="payment_proof" name="payment_proof" accept="image/*">
                                                    @error('payment_proof')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="form-text text-muted">Upload screenshot or photo of your transfer receipt (max 5MB)</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    @if(!$hasChipPayment && !$hasManualPayment)
                                    <div class="alert alert-warning">
                                        <i class="isax isax-warning-2 me-2"></i>
                                        No payment method is available for this course at the moment. Please contact the trainer.
                                    </div>
                                    @endif
                                </div>
                                @endif
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg" {{ (!$hasChipPayment && !$hasManualPayment && $course->price > 0) ? 'disabled' : '' }}>
                                    @if($course->price == 0)
                                    <i class="isax isax-tick-circle me-2"></i>Enroll Now - Free
                                    @else
                                    <i class="isax isax-card me-2"></i>Proceed to Payment - RM{{ number_format($course->price, 2) }}
                                    @endif
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <!-- Course Info -->
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0" style="width: 80px;">
                                @if($course->cover_image_thumbnail)
                                <img src="{{ route('courses.cover', ['course' => $course->id, 'type' => 'thumbnail']) }}" alt="{{ $course->title }}" class="img-fluid rounded">
                                @else
                                <img src="{{ asset('assets/img/course/course-01.jpg') }}" alt="{{ $course->title }}" class="img-fluid rounded">
                                @endif
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-1">{{ $course->title }}</h6>
                                <small class="text-muted">by {{ $trainer->name }}</small>
                            </div>
                        </div>

                        <hr>

                        <!-- Price Breakdown -->
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Course Price</span>
                            <span>RM{{ number_format($course->price, 2) }}</span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <strong>Total</strong>
                            <strong class="text-primary fs-5">
                                @if($course->price == 0)
                                Free
                                @else
                                RM{{ number_format($course->price, 2) }}
                                @endif
                            </strong>
                        </div>
                    </div>
                </div>

                <!-- Security Note -->
                <div class="card mt-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center text-muted">
                            <i class="isax isax-shield-tick fs-4 me-2 text-success"></i>
                            <small>Your payment information is encrypted and secure.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /Checkout Section -->
@endsection

@push('scripts')
<script>
    // Toggle manual payment details based on payment method selection
    document.querySelectorAll('input[name="payment_method"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            const manualDetails = document.getElementById('manual-payment-details');
            if (manualDetails) {
                manualDetails.style.display = this.value === 'manual' ? 'block' : 'none';
            }
        });
    });
</script>
@endpush
