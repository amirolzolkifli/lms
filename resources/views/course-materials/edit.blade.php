@extends('layouts.app')

@section('title', 'Edit Material')

@section('content')
<div class="row">
    <div class="col-lg-3 theiaStickySidebar">
        @include('layouts.partials.sidebar')
    </div>

    <div class="col-lg-9">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Material - {{ $course->title }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('app.course-materials.update', [$course, $material]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="title" class="form-label">Material Title <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('title') is-invalid @enderror"
                               id="title"
                               name="title"
                               value="{{ old('title', $material->title) }}"
                               required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Material Type</label>
                        <div>
                            <span class="badge material-type-badge {{ $material->type === 'video' ? 'type-video' : 'type-document' }}" style="font-size: 0.9rem; padding: 8px 16px;">
                                <i class="isax isax-{{ $material->type === 'video' ? 'video' : 'document' }} me-1"></i>
                                {{ ucfirst($material->type) }}
                            </span>
                        </div>
                        <small class="form-text text-muted">Material type cannot be changed after upload</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Current File</label>
                        <div class="d-flex align-items-center gap-3">
                            <span class="text-muted">
                                <i class="isax isax-document-text me-1"></i>{{ $material->file_name }}
                            </span>
                            <span class="text-muted">
                                <i class="isax isax-folder me-1"></i>{{ $material->file_size_formatted }}
                            </span>
                        </div>
                        <small class="form-text text-muted">To change the file, delete this material and upload a new one</small>
                    </div>

                    <div class="mb-4">
                        <label for="visibility" class="form-label">Visibility <span class="text-danger">*</span></label>
                        <select class="form-select @error('visibility') is-invalid @enderror"
                                id="visibility"
                                name="visibility"
                                required>
                            <option value="private" {{ old('visibility', $material->visibility) == 'private' ? 'selected' : '' }}>Private</option>
                            <option value="public" {{ old('visibility', $material->visibility) == 'public' ? 'selected' : '' }}>Public</option>
                        </select>
                        @error('visibility')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Public materials can be viewed by anyone without purchasing the course</small>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('app.course-materials.index', $course) }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="isax isax-save-2 me-2"></i>Update Material
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
