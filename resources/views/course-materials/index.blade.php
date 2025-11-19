@extends('layouts.app')

@section('title', 'Course Materials')

@section('content')
<div class="row">
    <div class="col-lg-3 theiaStickySidebar">
        @include('layouts.partials.sidebar')
    </div>

    <div class="col-lg-9">
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">{{ $course->title }} - Materials</h5>
                        <p class="text-muted mb-0">
                            {{ $currentMaterialCount }} of {{ $contentLimit == 999999 ? 'Unlimited' : $contentLimit }} files used
                            <span class="text-muted">(across all courses)</span>
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('courses.index') }}" class="btn btn-secondary">
                            <i class="isax isax-arrow-left me-2"></i>Back to Courses
                        </a>
                        <a href="{{ route('course-materials.create', $course) }}" class="btn btn-primary">
                            <i class="isax isax-document-upload me-2"></i>Upload Material
                        </a>
                    </div>
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

        @if($materials->count() > 0)
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>File Name</th>
                                    <th>Size</th>
                                    <th>Uploaded</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($materials as $material)
                                <tr>
                                    <td>{{ $material->title }}</td>
                                    <td>
                                        <span class="badge bg-{{ $material->type === 'video' ? 'primary' : 'info' }}">
                                            <i class="isax isax-{{ $material->type === 'video' ? 'video' : 'document' }} me-1"></i>
                                            {{ ucfirst($material->type) }}
                                        </span>
                                    </td>
                                    <td>{{ $material->file_name }}</td>
                                    <td>{{ $material->file_size_formatted }}</td>
                                    <td>{{ $material->created_at->format('d M Y') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('course-materials.download', [$course, $material]) }}"
                                               class="btn btn-outline-primary">
                                                <i class="isax isax-document-download"></i>
                                            </a>
                                            <form action="{{ route('course-materials.destroy', [$course, $material]) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this material?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger">
                                                    <i class="isax isax-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $materials->links() }}
                    </div>
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="isax isax-document text-gray-5" style="font-size: 64px;"></i>
                    <h5 class="mt-3">No materials yet</h5>
                    <p class="text-muted">Upload your first material to get started</p>
                    <a href="{{ route('course-materials.create', $course) }}" class="btn btn-primary mt-3">
                        <i class="isax isax-document-upload me-2"></i>Upload Material
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
