@extends('layouts.app')

@section('title', 'Edit Course')

@section('content')
<div class="row">
    <div class="col-lg-3 theiaStickySidebar">
        @include('layouts.partials.sidebar')
    </div>

    <div class="col-lg-9">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Course</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('app.courses.update', $course) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="title" class="form-label">Course Title <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('title') is-invalid @enderror"
                               id="title"
                               name="title"
                               value="{{ old('title', $course->title) }}"
                               required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                        <select class="form-select @error('category_id') is-invalid @enderror"
                                id="category_id"
                                name="category_id"
                                required>
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $course->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description"
                                  name="description"
                                  rows="5"
                                  required>{{ old('description', $course->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="price" class="form-label">Price (RM) <span class="text-danger">*</span></label>
                        <input type="number"
                               step="0.01"
                               class="form-control @error('price') is-invalid @enderror"
                               id="price"
                               name="price"
                               value="{{ old('price', $course->price) }}"
                               required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror"
                                id="status"
                                name="status"
                                required>
                            <option value="open" {{ old('status', $course->status) == 'open' ? 'selected' : '' }}>Open</option>
                            <option value="closed" {{ old('status', $course->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Open courses are visible to students, closed courses are hidden</small>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('app.courses.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="isax isax-save-2 me-2"></i>Update Course
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
