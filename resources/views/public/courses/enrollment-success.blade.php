@extends('layouts.welcome')

@section('title', 'Enrollment Successful')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb-bar text-center">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-12">
                <h2 class="breadcrumb-title mb-2">Enrollment Successful</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">Courses</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Enrollment Success</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- /Breadcrumb -->

<!-- Success Section -->
<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card text-center">
                    <div class="card-body p-5">
                        <div class="mb-4">
                            <div class="success-icon mx-auto" style="width: 100px; height: 100px; background: rgba(40, 199, 111, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="isax isax-tick-circle text-success" style="font-size: 50px;"></i>
                            </div>
                        </div>

                        <h3 class="mb-3">You're Enrolled!</h3>
                        <p class="text-muted mb-4">
                            Congratulations! You have successfully enrolled in <strong>{{ $course->title }}</strong>.
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
                                        <td class="text-muted">Amount Paid:</td>
                                        <td class="text-end">
                                            <strong>
                                                @if($enrollment->amount == 0)
                                                    Free
                                                @else
                                                    RM{{ number_format($enrollment->amount, 2) }}
                                                @endif
                                            </strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Enrolled On:</td>
                                        <td class="text-end"><strong>{{ $enrollment->enrolled_at->format('d M Y, h:i A') }}</strong></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="alert alert-success text-start mb-4">
                            <i class="isax isax-tick-circle me-2"></i>
                            You can now access all course materials from your dashboard or by visiting the course page.
                        </div>

                        <div class="d-grid gap-2">
                            <a href="{{ route('courses.show', $course) }}" class="btn btn-primary">
                                <i class="isax isax-book me-2"></i>Go to Course
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
<!-- /Success Section -->
@endsection
