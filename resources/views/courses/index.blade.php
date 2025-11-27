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
                    <a href="{{ route('app.courses.create') }}" class="btn btn-primary">
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
                    <div class="card h-100 course-card">
                        <div class="card-body d-flex flex-column">
                            <!-- Header: Title and Status Badge -->
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="course-title mb-0 pe-2">{{ $course->title }}</h5>
                                <span class="badge course-status-badge {{ $course->status === 'open' ? 'status-open' : 'status-closed' }}">
                                    {{ ucfirst($course->status) }}
                                </span>
                            </div>

                            <!-- Category -->
                            <p class="course-category mb-2">
                                <i class="isax isax-category me-1"></i>{{ $course->category->name }}
                            </p>

                            <!-- Description -->
                            <p class="course-description flex-grow-1">
                                {{ Str::limit($course->description, 100) }}
                            </p>

                            <!-- Footer: Price and Actions -->
                            <div class="course-footer mt-auto pt-3">
                                <div class="course-price mb-3">
                                    <span class="price-label">Price</span>
                                    <span class="price-value">RM {{ number_format($course->price, 2) }}</span>
                                </div>
                                <div class="course-actions d-flex gap-2">
                                    <a href="{{ route('app.course-materials.index', $course) }}" class="btn btn-sm btn-course-action btn-materials flex-fill">
                                        <i class="isax isax-document me-1"></i>Materials
                                    </a>
                                    <a href="{{ route('app.courses.edit', $course) }}" class="btn btn-sm btn-course-action btn-edit flex-fill">
                                        <i class="isax isax-edit-2 me-1"></i>Edit
                                    </a>
                                    <form action="{{ route('app.courses.destroy', $course) }}" method="POST" class="flex-fill" onsubmit="return confirm('Are you sure you want to delete this course?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-course-action btn-delete w-100">
                                            <i class="isax isax-trash me-1"></i>Delete
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
                    <a href="{{ route('app.courses.create') }}" class="btn btn-primary mt-3">
                        <i class="isax isax-add me-2"></i>Create Your First Course
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
