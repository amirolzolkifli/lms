@extends('layouts.welcome')

@section('title', $course->title)

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb-bar text-center">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-12">
                <h2 class="breadcrumb-title mb-2">Course Details</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">Courses</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($course->title, 30) }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- /Breadcrumb -->

<!-- Course Details -->
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <!-- Course Overview -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h3 class="mb-3">{{ $course->title }}</h3>

                        <div class="d-flex align-items-center flex-wrap gap-3 mb-4">
                            @if($course->category)
                            <span class="badge bg-primary">{{ $course->category->name }}</span>
                            @endif
                            <span class="d-flex align-items-center text-muted">
                                <i class="isax isax-book me-1"></i> {{ $course->materials->count() }} Materials
                            </span>
                        </div>

                        <div class="course-image mb-4">
                            @if($course->cover_image)
                            <img src="{{ route('courses.cover', ['course' => $course->id, 'type' => 'cover']) }}" alt="{{ $course->title }}" class="img-fluid rounded">
                            @else
                            <img src="{{ asset('assets/img/course/course-0' . ((($course->id - 1) % 9) + 1) . '.jpg') }}" alt="{{ $course->title }}" class="img-fluid rounded">
                            @endif
                        </div>

                        <h5 class="mb-3">About This Course</h5>
                        <div class="course-description">
                            {!! nl2br(e($course->description)) !!}
                        </div>
                    </div>
                </div>

                <!-- Course Materials Preview -->
                @if($course->materials->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Course Content</h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @foreach($course->materials as $material)
                            <li class="list-group-item d-flex align-items-center">
                                <div class="me-3">
                                    @if($material->type == 'video')
                                    <i class="isax isax-video-play text-primary fs-4"></i>
                                    @elseif($material->type == 'document')
                                    <i class="isax isax-document-text text-warning fs-4"></i>
                                    @else
                                    <i class="isax isax-document text-secondary fs-4"></i>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    @if($material->visibility === 'public')
                                    <a href="{{ route('courses.materials.show', [$course->id, $material->id]) }}" class="text-decoration-none">
                                        <h6 class="mb-0">{{ $material->title }}</h6>
                                    </a>
                                    @else
                                    <h6 class="mb-0">{{ $material->title }}</h6>
                                    @endif
                                    <small class="text-muted text-capitalize">{{ $material->type }}</small>
                                </div>
                                @if($material->visibility === 'public')
                                <a href="{{ route('courses.materials.show', [$course->id, $material->id]) }}" class="btn btn-sm btn-outline-success">
                                    <i class="isax isax-eye me-1"></i>Preview
                                </a>
                                @else
                                <i class="isax isax-lock text-muted"></i>
                                @endif
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif
            </div>

            <div class="col-lg-4">
                <!-- Course Price Card -->
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <div class="mb-4">
                            @if($course->price == 0)
                            <h2 class="text-success mb-0">Free</h2>
                            @else
                            <h2 class="text-primary mb-0">RM{{ number_format($course->price, 2) }}</h2>
                            @endif
                        </div>

                        @auth
                            @if($course->isEnrolled(Auth::user()))
                            <div class="alert alert-success mb-3">
                                <i class="isax isax-tick-circle me-2"></i>You are enrolled in this course
                            </div>
                            @else
                            <a href="{{ route('courses.checkout', $course) }}" class="btn btn-primary w-100 mb-3">
                                <i class="isax isax-shopping-cart me-2"></i>Enroll Now
                            </a>
                            @endif
                        @else
                        <a href="{{ route('courses.checkout', $course) }}" class="btn btn-primary w-100 mb-3">
                            <i class="isax isax-shopping-cart me-2"></i>Enroll Now
                        </a>
                        <p class="text-muted small">Already have an account? <a href="{{ route('login') }}">Login here</a></p>
                        @endauth
                    </div>
                </div>

                <!-- Trainer Info -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Instructor</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                @if($course->trainer && $course->trainer->logo)
                                <img src="{{ Storage::url($course->trainer->logo) }}" alt="{{ $course->trainer->name }}" class="rounded-circle" width="60" height="60" style="object-fit: cover;">
                                @else
                                <img src="{{ asset('assets/img/user/user-29.jpg') }}" alt="{{ $course->trainer->name ?? 'Trainer' }}" class="rounded-circle" width="60" height="60" style="object-fit: cover;">
                                @endif
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-1">{{ $course->trainer->name ?? 'Unknown' }}</h6>
                                @if($course->trainer && $course->trainer->company_name)
                                <p class="text-muted mb-0 small">{{ $course->trainer->company_name }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Course Info -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Course Info</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="d-flex justify-content-between mb-3">
                                <span class="text-muted"><i class="isax isax-book me-2"></i>Materials</span>
                                <strong>{{ $course->materials->count() }}</strong>
                            </li>
                            @if($course->category)
                            <li class="d-flex justify-content-between mb-3">
                                <span class="text-muted"><i class="isax isax-category me-2"></i>Category</span>
                                <strong>{{ $course->category->name }}</strong>
                            </li>
                            @endif
                            <li class="d-flex justify-content-between">
                                <span class="text-muted"><i class="isax isax-calendar me-2"></i>Published</span>
                                <strong>{{ $course->created_at->format('M d, Y') }}</strong>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /Course Details -->
@endsection
