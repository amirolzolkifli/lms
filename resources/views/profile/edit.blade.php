@extends('layouts.app')

@section('title', 'My Profile')

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
                <h5 class="mb-0">My Profile</h5>
            </div>
            <div class="card-body">
                @if(session('status') === 'profile-updated')
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Profile updated successfully.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('status') === 'password-updated')
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Password updated successfully.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Tabs -->
                <ul class="nav nav-tabs mb-4" id="profileTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ !request('tab') || request('tab') == 'profile' ? 'active' : '' }}"
                                id="profile-tab"
                                data-bs-toggle="tab"
                                data-bs-target="#profile"
                                type="button"
                                role="tab">
                            <i class="isax isax-user me-2"></i>Profile Information
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ request('tab') == 'password' ? 'active' : '' }}"
                                id="password-tab"
                                data-bs-toggle="tab"
                                data-bs-target="#password"
                                type="button"
                                role="tab">
                            <i class="isax isax-lock me-2"></i>Change Password
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ request('tab') == 'delete' ? 'active' : '' }}"
                                id="delete-tab"
                                data-bs-toggle="tab"
                                data-bs-target="#delete"
                                type="button"
                                role="tab">
                            <i class="isax isax-trash me-2"></i>Delete Account
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="profileTabContent">
                    <!-- Profile Information Tab -->
                    <div class="tab-pane fade {{ !request('tab') || request('tab') == 'profile' ? 'show active' : '' }}"
                         id="profile"
                         role="tabpanel">
                        <p class="text-muted mb-4">Update your account's profile information and email address.</p>

                        <form action="{{ route('app.profile.update') }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <!-- Name -->
                            <div class="mb-4">
                                <label for="name" class="form-label">Name</label>
                                <input type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="name"
                                       name="name"
                                       value="{{ old('name', $user->name) }}"
                                       required
                                       autofocus>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="mb-4">
                                <label for="email" class="form-label">Email</label>
                                <input type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       id="email"
                                       name="email"
                                       value="{{ old('email', $user->email) }}"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                    <div class="mt-2">
                                        <p class="text-warning small">
                                            Your email address is unverified.
                                            <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-link p-0 text-decoration-underline small">
                                                    Click here to re-send the verification email.
                                                </button>
                                            </form>
                                        </p>

                                        @if (session('status') === 'verification-link-sent')
                                            <p class="text-success small">
                                                A new verification link has been sent to your email address.
                                            </p>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <!-- Submit Button -->
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="isax isax-save-2 me-2"></i>Save Changes
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Change Password Tab -->
                    <div class="tab-pane fade {{ request('tab') == 'password' ? 'show active' : '' }}"
                         id="password"
                         role="tabpanel">
                        <p class="text-muted mb-4">Ensure your account is using a long, random password to stay secure.</p>

                        <form action="{{ route('password.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Current Password -->
                            <div class="mb-4">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password"
                                       class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                                       id="current_password"
                                       name="current_password"
                                       autocomplete="current-password">
                                @error('current_password', 'updatePassword')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- New Password -->
                            <div class="mb-4">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password"
                                       class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                                       id="password"
                                       name="password"
                                       autocomplete="new-password">
                                @error('password', 'updatePassword')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password"
                                       class="form-control"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       autocomplete="new-password">
                            </div>

                            <!-- Submit Button -->
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="isax isax-lock me-2"></i>Update Password
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Delete Account Tab -->
                    <div class="tab-pane fade {{ request('tab') == 'delete' ? 'show active' : '' }}"
                         id="delete"
                         role="tabpanel">
                        <div class="alert alert-danger mb-4">
                            <h6 class="alert-heading mb-2"><i class="isax isax-danger me-2"></i>Danger Zone</h6>
                            <p class="mb-0">Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</p>
                        </div>

                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                            <i class="isax isax-trash me-2"></i>Delete Account
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Main Content -->
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('app.profile.destroy') }}">
                @csrf
                @method('DELETE')

                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAccountModalLabel">Delete Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted.</p>
                    <p>Please enter your password to confirm you would like to permanently delete your account.</p>

                    <div class="mb-3">
                        <label for="delete_password" class="form-label">Password</label>
                        <input type="password"
                               class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                               id="delete_password"
                               name="password"
                               placeholder="Enter your password"
                               required>
                        @error('password', 'userDeletion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete Account</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($errors->userDeletion->isNotEmpty())
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteAccountModal'));
        deleteModal.show();
    });
</script>
@endpush
@endif
@endsection
