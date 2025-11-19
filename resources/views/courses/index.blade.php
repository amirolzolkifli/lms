@extends('layouts.app')

@section('title', 'My Courses')

@section('content')
<div class="row">
    <!-- Sidebar -->
    <div class="col-lg-3 theiaStickySidebar">
        @include('layouts.partials.sidebar')
    </div>
    <!-- /Sidebar -->

    <!-- Main Content -->
    <div class="col-lg-9">
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">My Courses</h5>
                        <p class="text-muted mb-0">
                            {{ $currentCourseCount }} of {{ $courseLimit == 999999 ? 'Unlimited' : $courseLimit }} courses used
                        </p>
                    </div>
                    <a href="{{ route('courses.create') }}" class="btn btn-primary">
                        <i class="isax isax-add me-2"></i>Create New Course
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($courses->count() > 0)
            <div class="row">
                @foreach($courses as $course)
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="mb-0">{{ $course->title }}</h5>
                                <span class="badge bg-{{ $course->status === 'open' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($course->status) }}
                                </span>
                            </div>
                            <p class="text-muted mb-2">
                                <i class="isax isax-category me-1"></i>{{ $course->category->name }}
                            </p>
                            <p class="text-gray-7 mb-3">
                                {{ Str::limit($course->description, 100) }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-primary">RM {{ number_format($course->price, 2) }}</span>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('course-materials.index', $course) }}" class="btn btn-outline-info">
                                        <i class="isax isax-document"></i> Materials
                                    </a>
                                    <a href="{{ route('courses.edit', $course) }}" class="btn btn-outline-primary">
                                        <i class="isax isax-edit-2"></i> Edit
                                    </a>
                                    <form action="{{ route('courses.destroy', $course) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this course?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">
                                            <i class="isax isax-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center">
                {{ $courses->links() }}
            </div>
        @else
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="isax isax-book-1 text-gray-5" style="font-size: 64px;"></i>
                    <h5 class="mt-3">No courses yet</h5>
                    <p class="text-muted">Create your first course to get started</p>
                    <a href="{{ route('courses.create') }}" class="btn btn-primary mt-3">
                        <i class="isax isax-add me-2"></i>Create Your First Course
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
