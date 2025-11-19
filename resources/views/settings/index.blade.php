@extends('layouts.app')

@section('title', 'Settings')

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
                <h5 class="mb-0">System Settings</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Tabs -->
                <ul class="nav nav-tabs mb-4" id="settingsTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ request('tab') != 'pricing' ? 'active' : '' }}"
                                id="general-tab"
                                data-bs-toggle="tab"
                                data-bs-target="#general"
                                type="button"
                                role="tab">
                            <i class="isax isax-setting-2 me-2"></i>General
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ request('tab') == 'pricing' ? 'active' : '' }}"
                                id="pricing-tab"
                                data-bs-toggle="tab"
                                data-bs-target="#pricing"
                                type="button"
                                role="tab">
                            <i class="isax isax-wallet-2 me-2"></i>Pricing Plans
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="settingsTabContent">
                    <!-- General Settings Tab -->
                    <div class="tab-pane fade {{ request('tab') != 'pricing' ? 'show active' : '' }}"
                         id="general"
                         role="tabpanel">
                        <form action="{{ route('settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Site Name -->
                    <div class="mb-4">
                        <label for="site_name" class="form-label">Site Name</label>
                        <input type="text"
                               class="form-control @error('site_name') is-invalid @enderror"
                               id="site_name"
                               name="site_name"
                               value="{{ old('site_name', $siteName) }}"
                               required>
                        @error('site_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Registration Status -->
                    <div class="mb-4">
                        <label for="registration_open" class="form-label">Registration Status</label>
                        <select class="form-select @error('registration_open') is-invalid @enderror"
                                id="registration_open"
                                name="registration_open"
                                required>
                            <option value="1" {{ old('registration_open', $registrationOpen) == '1' ? 'selected' : '' }}>Open</option>
                            <option value="0" {{ old('registration_open', $registrationOpen) == '0' ? 'selected' : '' }}>Closed</option>
                        </select>
                        @error('registration_open')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Control whether new users can register</small>
                    </div>

                    <!-- Contact Email -->
                    <div class="mb-4">
                        <label for="contact_email" class="form-label">Contact Email</label>
                        <input type="email"
                               class="form-control @error('contact_email') is-invalid @enderror"
                               id="contact_email"
                               name="contact_email"
                               value="{{ old('contact_email', $contactEmail) }}"
                               required>
                        @error('contact_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Primary contact email for the site</small>
                    </div>

                    <hr class="my-4">
                    <h6 class="mb-3">Payment Gateway Settings (CHIP)</h6>

                    <!-- CHIP Brand ID -->
                    <div class="mb-4">
                        <label for="chip_brand_id" class="form-label">CHIP Brand ID</label>
                        <input type="text"
                               class="form-control @error('chip_brand_id') is-invalid @enderror"
                               id="chip_brand_id"
                               name="chip_brand_id"
                               value="{{ old('chip_brand_id', $chipBrandId) }}"
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
                               value="{{ old('chip_api_key', $chipApiKey) }}"
                               placeholder="sk_xxxxxxxxxx">
                        @error('chip_api_key')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Get your API Key from <a href="https://portal.chip-in.asia/collect/developers/api-keys" target="_blank">CHIP Portal</a></small>
                    </div>

                            <!-- Submit Button -->
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="isax isax-save-2 me-2"></i>Save Settings
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Pricing Settings Tab -->
                    <div class="tab-pane fade {{ request('tab') == 'pricing' ? 'show active' : '' }}"
                         id="pricing"
                         role="tabpanel">
                        <form action="{{ route('settings.pricing.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Basic Plan -->
                                <div class="col-md-6">
                                    <div class="card mb-3">
                                        <div class="card-header bg-secondary text-white">
                                            <h6 class="mb-0">Basic Plan</h6>
                                        </div>
                                        <div class="card-body">
                                            <!-- Price -->
                                            <div class="mb-3">
                                                <label for="basic_price" class="form-label">Monthly Price (RM)</label>
                                                <input type="number"
                                                       step="0.01"
                                                       class="form-control @error('basic_price') is-invalid @enderror"
                                                       id="basic_price"
                                                       name="basic_price"
                                                       value="{{ old('basic_price', $plans['Basic Plan']->price_monthly ?? 0) }}"
                                                       required>
                                                @error('basic_price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Course Limit -->
                                            <div class="mb-3">
                                                <label for="basic_course_limit" class="form-label">Course Limit</label>
                                                <input type="number"
                                                       class="form-control @error('basic_course_limit') is-invalid @enderror"
                                                       id="basic_course_limit"
                                                       name="basic_course_limit"
                                                       value="{{ old('basic_course_limit', $plans['Basic Plan']->course_limit ?? 0) }}"
                                                       required>
                                                @error('basic_course_limit')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Content Upload Limit -->
                                            <div class="mb-3">
                                                <label for="basic_content_limit" class="form-label">Content Upload Limit (files)</label>
                                                <input type="number"
                                                       class="form-control @error('basic_content_limit') is-invalid @enderror"
                                                       id="basic_content_limit"
                                                       name="basic_content_limit"
                                                       value="{{ old('basic_content_limit', $plans['Basic Plan']->content_upload_limit ?? 0) }}"
                                                       required>
                                                @error('basic_content_limit')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Student Limit -->
                                            <div class="mb-3">
                                                <label for="basic_student_limit" class="form-label">Student Limit</label>
                                                <input type="number"
                                                       class="form-control @error('basic_student_limit') is-invalid @enderror"
                                                       id="basic_student_limit"
                                                       name="basic_student_limit"
                                                       value="{{ old('basic_student_limit', $plans['Basic Plan']->student_limit ?? 0) }}"
                                                       required>
                                                @error('basic_student_limit')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pro Plan -->
                                <div class="col-md-6">
                                    <div class="card mb-3">
                                        <div class="card-header bg-primary text-white">
                                            <h6 class="mb-0">Pro Plan</h6>
                                        </div>
                                        <div class="card-body">
                                            <!-- Price -->
                                            <div class="mb-3">
                                                <label for="pro_price" class="form-label">Monthly Price (RM)</label>
                                                <input type="number"
                                                       step="0.01"
                                                       class="form-control @error('pro_price') is-invalid @enderror"
                                                       id="pro_price"
                                                       name="pro_price"
                                                       value="{{ old('pro_price', $plans['Pro Plan']->price_monthly ?? 0) }}"
                                                       required>
                                                @error('pro_price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Course Limit -->
                                            <div class="mb-3">
                                                <label for="pro_course_limit" class="form-label">Course Limit</label>
                                                <input type="number"
                                                       class="form-control @error('pro_course_limit') is-invalid @enderror"
                                                       id="pro_course_limit"
                                                       name="pro_course_limit"
                                                       value="{{ old('pro_course_limit', $plans['Pro Plan']->course_limit ?? 0) }}"
                                                       required>
                                                @error('pro_course_limit')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Content Upload Limit -->
                                            <div class="mb-3">
                                                <label for="pro_content_limit" class="form-label">Content Upload Limit (files)</label>
                                                <input type="number"
                                                       class="form-control @error('pro_content_limit') is-invalid @enderror"
                                                       id="pro_content_limit"
                                                       name="pro_content_limit"
                                                       value="{{ old('pro_content_limit', $plans['Pro Plan']->content_upload_limit ?? 0) }}"
                                                       required>
                                                @error('pro_content_limit')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Student Limit -->
                                            <div class="mb-3">
                                                <label for="pro_student_limit" class="form-label">Student Limit</label>
                                                <input type="number"
                                                       class="form-control @error('pro_student_limit') is-invalid @enderror"
                                                       id="pro_student_limit"
                                                       name="pro_student_limit"
                                                       value="{{ old('pro_student_limit', $plans['Pro Plan']->student_limit ?? 0) }}"
                                                       required>
                                                @error('pro_student_limit')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="isax isax-save-2 me-2"></i>Save Pricing
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Main Content -->
</div>
@endsection
