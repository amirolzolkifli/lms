@extends('layouts.welcome')

@section('title', 'Browse Courses')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/plugins/ion-rangeslider/css/ion.rangeSlider.min.css') }}">
<style>
    .course-content .section {
        padding: 60px 0;
    }
</style>
@endpush

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb-bar text-center">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-12">
                <h2 class="breadcrumb-title mb-2">Browse Courses</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Courses</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- /Breadcrumb -->

<!-- Course -->
<section class="course-content section">
    <div class="container">
        <div class="row align-items-baseline">
            <div class="col-lg-3 stickySidebar">
                <div class="filter-clear">
                    <div class="clear-filter mb-4 pb-lg-2 d-flex align-items-center justify-content-between">
                        <h5><i class="feather-filter me-2"></i>Filters</h5>
                        <a href="{{ route('courses.index') }}" class="clear-text">Clear</a>
                    </div>

                    <form action="{{ route('courses.index') }}" method="GET" id="filter-form">
                        <div class="accordion accordion-customicon1 accordions-items-seperate">
                            <!-- Categories Filter -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingCategories">
                                    <a href="#" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseCategories" aria-expanded="true" aria-controls="collapseCategories">
                                        Categories <i class="fa-solid fa-chevron-down"></i>
                                    </a>
                                </h2>
                                <div id="collapseCategories" class="accordion-collapse collapse show" aria-labelledby="headingCategories">
                                    <div class="accordion-body">
                                        @foreach($categories as $category)
                                        <div>
                                            <label class="custom_check">
                                                <input type="radio" name="category" value="{{ $category->id }}" {{ request('category') == $category->id ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit()">
                                                <span class="checkmark"></span> {{ $category->name }} ({{ $category->courses_count }})
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Trainers Filter -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTrainers">
                                    <a href="#" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseTrainers" aria-expanded="true" aria-controls="collapseTrainers">
                                        Trainers <i class="fa-solid fa-chevron-down"></i>
                                    </a>
                                </h2>
                                <div id="collapseTrainers" class="accordion-collapse collapse show" aria-labelledby="headingTrainers">
                                    <div class="accordion-body">
                                        @foreach($trainers as $trainer)
                                        <div>
                                            <label class="custom_check">
                                                <input type="radio" name="trainer" value="{{ $trainer->id }}" {{ request('trainer') == $trainer->id ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit()">
                                                <span class="checkmark"></span> {{ $trainer->name }} ({{ $trainer->courses_count }})
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Price Filter -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingPrice">
                                    <a href="#" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapsePrice" aria-expanded="true" aria-controls="collapsePrice">
                                        Price <i class="fa-solid fa-chevron-down"></i>
                                    </a>
                                </h2>
                                <div id="collapsePrice" class="accordion-collapse collapse show" aria-labelledby="headingPrice">
                                    <div class="accordion-body">
                                        <div>
                                            <label class="custom_check custom_one">
                                                <input type="radio" name="price" value="" {{ !request('price') ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit()">
                                                <span class="checkmark"></span> All
                                            </label>
                                        </div>
                                        <div>
                                            <label class="custom_check custom_one">
                                                <input type="radio" name="price" value="free" {{ request('price') == 'free' ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit()">
                                                <span class="checkmark"></span> Free
                                            </label>
                                        </div>
                                        <div>
                                            <label class="custom_check custom_one mb-0">
                                                <input type="radio" name="price" value="paid" {{ request('price') == 'paid' ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit()">
                                                <span class="checkmark"></span> Paid
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden inputs to preserve other filters -->
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    </form>
                </div>
            </div>

            <div class="col-lg-9">
                <!-- Filter -->
                <div class="showing-list mb-4">
                    <div class="row align-items-center">
                        <div class="col-lg-4">
                            <div class="show-result text-center text-lg-start">
                                <h6 class="fw-medium">Showing {{ $courses->firstItem() ?? 0 }}-{{ $courses->lastItem() ?? 0 }} of {{ $courses->total() }} results</h6>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="show-filter add-course-info">
                                <form action="{{ route('courses.index') }}" method="GET" id="sort-form">
                                    <div class="d-sm-flex justify-content-center justify-content-lg-end mb-1 mb-lg-0">
                                        <select class="form-select" name="sort" onchange="document.getElementById('sort-form').submit()">
                                            <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Newly Published</option>
                                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                            <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Title: A-Z</option>
                                        </select>
                                        <div class="search-group">
                                            <i class="isax isax-search-normal-1"></i>
                                            <input type="text" class="form-control" name="search" placeholder="Search courses..." value="{{ request('search') }}">
                                        </div>
                                    </div>
                                    <!-- Preserve other filters -->
                                    <input type="hidden" name="category" value="{{ request('category') }}">
                                    <input type="hidden" name="trainer" value="{{ request('trainer') }}">
                                    <input type="hidden" name="price" value="{{ request('price') }}">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Filter -->

                <div class="row">
                    @forelse($courses as $course)
                    <div class="col-xl-4 col-md-6">
                        <div class="course-item-two course-item mx-0">
                            <div class="course-img">
                                <a href="{{ route('courses.show', $course) }}">
                                    @if($course->cover_image_thumbnail)
                                    <img src="{{ route('courses.cover', ['course' => $course->id, 'type' => 'thumbnail']) }}" alt="{{ $course->title }}" class="img-fluid">
                                    @else
                                    <img src="{{ asset('assets/img/course/course-0' . (($loop->index % 9) + 1) . '.jpg') }}" alt="{{ $course->title }}" class="img-fluid">
                                    @endif
                                </a>
                                <div class="position-absolute start-0 top-0 d-flex align-items-start w-100 z-index-2 p-3">
                                    @if($course->price == 0)
                                    <div class="badge text-bg-success">Free</div>
                                    @endif
                                    <a href="javascript:void(0);" class="fav-icon ms-auto"><i class="isax isax-heart"></i></a>
                                </div>
                            </div>
                            <div class="course-content">
                                <div class="d-flex justify-content-between mb-2">
                                    <div class="d-flex align-items-center">
                                        <a href="javascript:void(0);" class="avatar avatar-sm">
                                            @if($course->trainer && $course->trainer->logo)
                                            <img src="{{ Storage::url($course->trainer->logo) }}" alt="{{ $course->trainer->name }}" class="img-fluid avatar avatar-sm rounded-circle">
                                            @else
                                            <img src="{{ asset('assets/img/user/user-29.jpg') }}" alt="{{ $course->trainer->name ?? 'Trainer' }}" class="img-fluid avatar avatar-sm rounded-circle">
                                            @endif
                                        </a>
                                        <div class="ms-2">
                                            <span class="link-default fs-14">{{ $course->trainer->name ?? 'Unknown' }}</span>
                                        </div>
                                    </div>
                                    @if($course->category)
                                    <span class="badge badge-light rounded-pill bg-light d-inline-flex align-items-center fs-13 fw-medium mb-0">
                                        {{ $course->category->name }}
                                    </span>
                                    @endif
                                </div>
                                <h6 class="title mb-2">
                                    <a href="{{ route('courses.show', $course) }}">{{ Str::limit($course->title, 50) }}</a>
                                </h6>
                                <p class="text-muted fs-14 mb-3">{{ Str::limit($course->description, 60) }}</p>
                                <div class="d-flex align-items-center justify-content-between">
                                    <h5 class="text-secondary mb-0">
                                        @if($course->price == 0)
                                        Free
                                        @else
                                        RM{{ number_format($course->price, 2) }}
                                        @endif
                                    </h5>
                                    <a href="{{ route('courses.show', $course) }}" class="btn btn-dark btn-sm d-inline-flex align-items-center">View Course<i class="isax isax-arrow-right-3 ms-1"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="isax isax-book fs-1 text-muted mb-3 d-block"></i>
                            <h5>No courses found</h5>
                            <p class="text-muted">Try adjusting your search or filter criteria</p>
                            <a href="{{ route('courses.index') }}" class="btn btn-primary">Clear Filters</a>
                        </div>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($courses->hasPages())
                <div class="row align-items-center mt-4">
                    <div class="col-md-4">
                        <p class="pagination-text mb-0">Page {{ $courses->currentPage() }} of {{ $courses->lastPage() }}</p>
                    </div>
                    <div class="col-md-8">
                        <ul class="pagination lms-page justify-content-center justify-content-md-end mt-2 mt-md-0 mb-0">
                            @if($courses->onFirstPage())
                            <li class="page-item prev disabled">
                                <span class="page-link"><i class="fas fa-angle-left"></i></span>
                            </li>
                            @else
                            <li class="page-item prev">
                                <a class="page-link" href="{{ $courses->previousPageUrl() }}"><i class="fas fa-angle-left"></i></a>
                            </li>
                            @endif

                            @foreach($courses->getUrlRange(max(1, $courses->currentPage() - 2), min($courses->lastPage(), $courses->currentPage() + 2)) as $page => $url)
                            <li class="page-item {{ $page == $courses->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                            @endforeach

                            @if($courses->hasMorePages())
                            <li class="page-item next">
                                <a class="page-link" href="{{ $courses->nextPageUrl() }}"><i class="fas fa-angle-right"></i></a>
                            </li>
                            @else
                            <li class="page-item next disabled">
                                <span class="page-link"><i class="fas fa-angle-right"></i></span>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
                @endif
                <!-- /Pagination -->

            </div>
        </div>
    </div>
</section>
<!-- /Course -->
@endsection

@push('scripts')
<script src="{{ asset('assets/plugins/theia-sticky-sidebar/ResizeSensor.js') }}"></script>
<script src="{{ asset('assets/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js') }}"></script>
<script>
    $(document).ready(function() {
        if (typeof $.fn.theiaStickySidebar !== 'undefined') {
            $('.stickySidebar').theiaStickySidebar({
                additionalMarginTop: 100
            });
        }
    });
</script>
@endpush
