@extends('layouts.app')

@section('title', $material->title)

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
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-1">
                                <li class="breadcrumb-item"><a href="{{ route('app.courses.index') }}">My Courses</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('app.course-materials.index', $course) }}">{{ $course->title }}</a></li>
                                <li class="breadcrumb-item active">{{ $material->title }}</li>
                            </ol>
                        </nav>
                        <h5 class="mb-0">{{ $material->title }}</h5>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('app.course-materials.index', $course) }}" class="btn btn-secondary">
                            <i class="isax isax-arrow-left me-2"></i>Back to Materials
                        </a>
                        <a href="{{ route('app.course-materials.download', [$course, $material]) }}" class="btn btn-primary">
                            <i class="isax isax-document-download me-2"></i>Download
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                {{-- Material Info Header --}}
                <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                    <span class="badge material-type-badge {{ $material->type === 'video' ? 'type-video' : 'type-document' }}" style="font-size: 0.9rem; padding: 8px 16px;">
                        <i class="isax isax-{{ $material->type === 'video' ? 'video' : 'document' }} me-1"></i>
                        {{ ucfirst($material->type) }}
                    </span>
                    <span class="text-muted">
                        <i class="isax isax-document-text me-1"></i>{{ $material->file_name }}
                    </span>
                    <span class="text-muted">
                        <i class="isax isax-folder me-1"></i>{{ $material->file_size_formatted }}
                    </span>
                    <span class="text-muted">
                        <i class="isax isax-calendar me-1"></i>{{ $material->created_at->format('d M Y, H:i') }}
                    </span>
                </div>

                {{-- Content Preview --}}
                <div class="material-preview">
                    @if($material->type === 'video')
                        {{-- Video Player --}}
                        <div class="video-player-container">
                            <video id="videoPlayer" class="w-100" controls playsinline style="max-height: 70vh; background: #000; border-radius: 8px;">
                                <source src="{{ route('app.course-materials.stream', [$course, $material]) }}" type="{{ $material->mime_type }}">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    @elseif($material->mime_type === 'application/pdf')
                        {{-- PDF Preview --}}
                        <div class="pdf-viewer-container" style="height: 80vh;">
                            <iframe
                                src="{{ route('app.course-materials.stream', [$course, $material]) }}"
                                width="100%"
                                height="100%"
                                style="border: none; border-radius: 8px;"
                                title="{{ $material->title }}"
                            ></iframe>
                        </div>
                    @else
                        {{-- Other Document Types - Show download prompt --}}
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
                                <i class="isax isax-{{ $iconClass }}" style="font-size: 80px; color: var(--primary-color, #3366ff);"></i>
                            </div>
                            <h4 class="mb-2">{{ $material->file_name }}</h4>
                            <p class="text-muted mb-4">
                                This file type ({{ strtoupper($extension) }}) cannot be previewed in the browser.<br>
                                Please download it to view the content.
                            </p>
                            <a href="{{ route('app.course-materials.download', [$course, $material]) }}" class="btn btn-primary btn-lg">
                                <i class="isax isax-document-download me-2"></i>Download File
                            </a>
                            <p class="text-muted mt-3 small">
                                <i class="isax isax-folder me-1"></i>File size: {{ $material->file_size_formatted }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
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

    .breadcrumb {
        background: transparent !important;
        padding: 0;
        margin: 0;
    }

    .breadcrumb-item a,
    .breadcrumb-item a:link,
    .breadcrumb-item a:visited {
        color: #60a5fa !important;
        text-decoration: none !important;
    }

    .breadcrumb-item a:hover {
        text-decoration: underline !important;
        color: #93c5fd !important;
    }

    .breadcrumb-item.active {
        color: #94a3b8 !important;
    }

    .breadcrumb-item + .breadcrumb-item::before {
        color: #64748b !important;
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
            localStorage.setItem('video_{{ $material->id }}_time', video.currentTime);
        });

        // Restore playback position
        const savedTime = localStorage.getItem('video_{{ $material->id }}_time');
        if (savedTime) {
            video.currentTime = parseFloat(savedTime);
        }

        // Clear saved position when video ends
        video.addEventListener('ended', function() {
            localStorage.removeItem('video_{{ $material->id }}_time');
        });
    }
});
</script>
@endpush
