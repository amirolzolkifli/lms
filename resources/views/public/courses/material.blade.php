@extends('layouts.welcome')

@section('title', $material->title . ' - ' . $course->title)

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb-bar text-center">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-12">
                <h2 class="breadcrumb-title mb-2">{{ $material->title }}</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">Courses</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('courses.show', $course) }}">{{ Str::limit($course->title, 20) }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($material->title, 20) }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- /Breadcrumb -->

<!-- Material Content -->
<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div>
                                <h4 class="mb-1">{{ $material->title }}</h4>
                                <p class="text-muted mb-0">
                                    From course: <a href="{{ route('courses.show', $course) }}">{{ $course->title }}</a>
                                </p>
                            </div>
                            <a href="{{ route('courses.show', $course) }}" class="btn btn-secondary">
                                <i class="isax isax-arrow-left me-2"></i>Back to Course
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        {{-- Material Info Header --}}
                        <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom flex-wrap">
                            <span class="badge {{ $material->type === 'video' ? 'bg-primary' : 'bg-warning' }}" style="font-size: 0.9rem; padding: 8px 16px;">
                                <i class="isax isax-{{ $material->type === 'video' ? 'video' : 'document' }} me-1"></i>
                                {{ ucfirst($material->type) }}
                            </span>
                            <span class="text-muted">
                                <i class="isax isax-document-text me-1"></i>{{ $material->file_name }}
                            </span>
                            <span class="text-muted">
                                <i class="isax isax-folder me-1"></i>{{ $material->file_size_formatted }}
                            </span>
                        </div>

                        {{-- Content Preview --}}
                        <div class="material-preview">
                            @if($material->type === 'video')
                                {{-- Video Player --}}
                                <div class="video-player-container">
                                    <video id="videoPlayer" class="w-100" controls playsinline style="max-height: 70vh; background: #000; border-radius: 8px;">
                                        <source src="{{ route('courses.materials.stream', [$course->id, $material->id]) }}" type="{{ $material->mime_type }}">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                            @elseif($material->mime_type === 'application/pdf')
                                {{-- PDF Preview --}}
                                <div class="pdf-viewer-container" style="height: 80vh;">
                                    <iframe
                                        src="{{ route('courses.materials.stream', [$course->id, $material->id]) }}"
                                        width="100%"
                                        height="100%"
                                        style="border: none; border-radius: 8px;"
                                        title="{{ $material->title }}"
                                    ></iframe>
                                </div>
                            @else
                                {{-- Other Document Types - Show info --}}
                                <div class="text-center py-5">
                                    <div class="document-icon mb-4">
                                        @php
                                            $extension = pathinfo($material->file_name, PATHINFO_EXTENSION);
                                            $iconClass = match(strtolower($extension)) {
                                                'doc', 'docx' => 'document-text',
                                                'xls', 'xlsx' => 'chart-square',
                                                'ppt', 'pptx' => 'presention-chart',
                                                'zip', 'rar' => 'archive-add',
                                                default => 'document'
                                            };
                                        @endphp
                                        <i class="isax isax-{{ $iconClass }}" style="font-size: 80px; color: #3366ff;"></i>
                                    </div>
                                    <h4 class="mb-2">{{ $material->file_name }}</h4>
                                    <p class="text-muted mb-4">
                                        This file type ({{ strtoupper($extension) }}) cannot be previewed in the browser.<br>
                                        Please enroll in the course to download this material.
                                    </p>
                                    <p class="text-muted mt-3 small">
                                        <i class="isax isax-folder me-1"></i>File size: {{ $material->file_size_formatted }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Enroll CTA -->
                <div class="card mt-4">
                    <div class="card-body text-center py-4">
                        <h5 class="mb-2">Want access to all materials?</h5>
                        <p class="text-muted mb-3">Enroll in this course to unlock all content and download materials.</p>
                        @auth
                        <a href="{{ route('courses.show', $course) }}" class="btn btn-primary">
                            <i class="isax isax-login me-2"></i>Enroll Now - RM{{ number_format($course->price, 2) }}
                        </a>
                        @else
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            <i class="isax isax-login me-2"></i>Login to Enroll
                        </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /Material Content -->
@endsection

@push('styles')
<style>
    .material-preview {
        min-height: 400px;
    }

    .video-player-container {
        background: #000;
        border-radius: 8px;
        overflow: hidden;
    }

    .video-player-container video {
        display: block;
    }

    .pdf-viewer-container {
        background: #f5f5f5;
        border-radius: 8px;
        overflow: hidden;
    }

    .document-icon {
        opacity: 0.8;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const video = document.getElementById('videoPlayer');
    if (video) {
        // Save playback position
        video.addEventListener('timeupdate', function() {
            localStorage.setItem('video_public_{{ $material->id }}_time', video.currentTime);
        });

        // Restore playback position
        const savedTime = localStorage.getItem('video_public_{{ $material->id }}_time');
        if (savedTime) {
            video.currentTime = parseFloat(savedTime);
        }

        // Clear saved position when video ends
        video.addEventListener('ended', function() {
            localStorage.removeItem('video_public_{{ $material->id }}_time');
        });
    }
});
</script>
@endpush
