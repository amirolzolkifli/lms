@extends('layouts.app')

@section('title', 'Upload Material')

@section('content')
<div class="row">
    <div class="col-lg-3 theiaStickySidebar">
        @include('layouts.partials.sidebar')
    </div>

    <div class="col-lg-9">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Upload Material - {{ $course->title }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('app.course-materials.store', $course) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label for="title" class="form-label">Material Title <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('title') is-invalid @enderror"
                               id="title"
                               name="title"
                               value="{{ old('title') }}"
                               required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="type" class="form-label">Material Type <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror"
                                id="type"
                                name="type"
                                required>
                            <option value="">Select type</option>
                            <option value="video" {{ old('type') == 'video' ? 'selected' : '' }}>Video</option>
                            <option value="document" {{ old('type') == 'document' ? 'selected' : '' }}>Document</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Video: MP4, MOV, AVI, WMV, FLV, MKV | Document: PDF, PPT, DOC, XLS, ZIP, RAR
                        </small>
                    </div>

                    <div class="mb-4">
                        <label for="file" class="form-label">File <span class="text-danger">*</span></label>
                        <input type="file"
                               class="form-control @error('file') is-invalid @enderror"
                               id="file"
                               name="file"
                               required>
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Maximum file size: 100MB</small>
                    </div>

                    <div class="alert alert-info">
                        <i class="isax isax-info-circle me-2"></i>
                        <strong>Note:</strong> Files are counted across all your courses. Make sure you don't exceed your plan limit.
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('app.course-materials.index', $course) }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="isax isax-document-upload me-2"></i>Upload Material
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
