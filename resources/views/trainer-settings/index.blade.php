@extends('layouts.app')

@section('title', 'Payment Settings')

@section('content')
<div class="row">
    <!-- Sidebar -->
    <div class="col-lg-3 theiaStickySidebar">
        @include('layouts.partials.sidebar')
    </div>
    <!-- /Sidebar -->

    <!-- Main Content -->
    <div class="col-lg-9">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Payment Settings</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="alert alert-info mb-4">
                    <i class="isax isax-info-circle me-2"></i>
                    Configure your payment settings to receive payments from students when they enroll in your courses.
                </div>

                <form action="{{ route('app.trainer.settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <h6 class="mb-3">CHIP Payment Gateway</h6>

                    <!-- CHIP Brand ID -->
                    <div class="mb-4">
                        <label for="chip_brand_id" class="form-label">CHIP Brand ID</label>
                        <input type="text"
                               class="form-control @error('chip_brand_id') is-invalid @enderror"
                               id="chip_brand_id"
                               name="chip_brand_id"
                               value="{{ old('chip_brand_id', $settings['chip_brand_id'] ?? '') }}"
                               placeholder="brand_xxxxxxxxxx">
                        @error('chip_brand_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Get your Brand ID from <a href="https://portal.chip-in.asia/collect/developers/brands" target="_blank">CHIP Portal</a></small>
                    </div>

                    <!-- CHIP API Key -->
                    <div class="mb-4">
                        <label for="chip_api_key" class="form-label">CHIP API Key (Secret Key)</label>
                        <input type="password"
                               class="form-control @error('chip_api_key') is-invalid @enderror"
                               id="chip_api_key"
                               name="chip_api_key"
                               value="{{ old('chip_api_key', $settings['chip_api_key'] ?? '') }}"
                               placeholder="sk_xxxxxxxxxx">
                        @error('chip_api_key')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Get your API Key from <a href="https://portal.chip-in.asia/collect/developers/api-keys" target="_blank">CHIP Portal</a></small>
                    </div>

                    <hr class="my-4">
                    <h6 class="mb-3">Manual Payment (Bank Transfer)</h6>

                    <!-- Enable Manual Payment -->
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input type="checkbox"
                                   class="form-check-input"
                                   id="manual_payment_enabled"
                                   name="manual_payment_enabled"
                                   value="1"
                                   {{ old('manual_payment_enabled', $settings['manual_payment_enabled'] ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="manual_payment_enabled">Enable Manual Payment</label>
                        </div>
                        <small class="form-text text-muted">Allow students to pay via bank transfer. You will need to manually verify payments.</small>
                    </div>

                    <!-- Bank Details (shown when manual payment is enabled) -->
                    <div id="bank-details" style="{{ old('manual_payment_enabled', $settings['manual_payment_enabled'] ?? false) ? '' : 'display: none;' }}">
                        <!-- Bank Name -->
                        <div class="mb-4">
                            <label for="bank_name" class="form-label">Bank Name</label>
                            <input type="text"
                                   class="form-control @error('bank_name') is-invalid @enderror"
                                   id="bank_name"
                                   name="bank_name"
                                   value="{{ old('bank_name', $settings['bank_name'] ?? '') }}"
                                   placeholder="e.g. Maybank, CIMB, Public Bank">
                            @error('bank_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Bank Account Name -->
                        <div class="mb-4">
                            <label for="bank_account_name" class="form-label">Account Holder Name</label>
                            <input type="text"
                                   class="form-control @error('bank_account_name') is-invalid @enderror"
                                   id="bank_account_name"
                                   name="bank_account_name"
                                   value="{{ old('bank_account_name', $settings['bank_account_name'] ?? '') }}"
                                   placeholder="e.g. Ahmad bin Abdullah">
                            @error('bank_account_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Bank Account Number -->
                        <div class="mb-4">
                            <label for="bank_account_number" class="form-label">Account Number</label>
                            <input type="text"
                                   class="form-control @error('bank_account_number') is-invalid @enderror"
                                   id="bank_account_number"
                                   name="bank_account_number"
                                   value="{{ old('bank_account_number', $settings['bank_account_number'] ?? '') }}"
                                   placeholder="e.g. 1234567890">
                            @error('bank_account_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="isax isax-save-2 me-2"></i>Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /Main Content -->
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('manual_payment_enabled').addEventListener('change', function() {
        const bankDetails = document.getElementById('bank-details');
        if (this.checked) {
            bankDetails.style.display = 'block';
        } else {
            bankDetails.style.display = 'none';
        }
    });
</script>
@endpush
