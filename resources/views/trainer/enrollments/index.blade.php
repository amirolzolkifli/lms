@extends('layouts.app')

@section('title', 'Enrollments')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">Enrollments</h4>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1 text-white-50">Total Enrollments</h6>
                                    <h3 class="mb-0">{{ $stats['total'] }}</h3>
                                </div>
                                <i class="isax isax-people fs-1 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1 opacity-75">Pending Approval</h6>
                                    <h3 class="mb-0">{{ $stats['pending'] }}</h3>
                                </div>
                                <i class="isax isax-clock fs-1 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1 text-white-50">Active Students</h6>
                                    <h3 class="mb-0">{{ $stats['paid'] }}</h3>
                                </div>
                                <i class="isax isax-user-tick fs-1 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1 text-white-50">Total Revenue</h6>
                                    <h3 class="mb-0">RM{{ number_format($stats['total_revenue'], 2) }}</h3>
                                </div>
                                <i class="isax isax-money fs-1 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('app.trainer.enrollments.index') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Student name or email..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Course</label>
                            <select name="course" class="form-select">
                                <option value="">All Courses</option>
                                @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ request('course') == $course->id ? 'selected' : '' }}>
                                    {{ Str::limit($course->title, 25) }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Payment Status</label>
                            <select name="payment_status" class="form-select">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Failed</option>
                                <option value="cancelled" {{ request('payment_status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Payment Method</label>
                            <select name="payment_method" class="form-select">
                                <option value="">All Methods</option>
                                <option value="chip" {{ request('payment_method') === 'chip' ? 'selected' : '' }}>Online (CHIP)</option>
                                <option value="manual" {{ request('payment_method') === 'manual' ? 'selected' : '' }}>Bank Transfer</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="isax isax-filter me-1"></i>Filter
                            </button>
                            <a href="{{ route('app.trainer.enrollments.index') }}" class="btn btn-outline-secondary">
                                <i class="isax isax-refresh me-1"></i>Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Enrollments Table -->
            <div class="card">
                <div class="card-body p-0">
                    @if($enrollments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Order Ref</th>
                                    <th>Student</th>
                                    <th>Course</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th width="150">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($enrollments as $enrollment)
                                <tr>
                                    <td>
                                        <code class="small">{{ $enrollment->order_reference }}</code>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $enrollment->user->name ?? 'N/A' }}</strong>
                                        </div>
                                        <small class="text-muted">{{ $enrollment->user->email ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <span title="{{ $enrollment->course->title }}">
                                            {{ Str::limit($enrollment->course->title, 30) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($enrollment->amount == 0)
                                        <span class="text-success">Free</span>
                                        @else
                                        RM{{ number_format($enrollment->amount, 2) }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($enrollment->payment_method === 'chip')
                                        <span class="badge bg-primary">Online</span>
                                        @else
                                        <span class="badge bg-secondary">Bank Transfer</span>
                                        @endif
                                    </td>
                                    <td>
                                        @switch($enrollment->payment_status)
                                            @case('paid')
                                                <span class="badge bg-success">Paid</span>
                                                @break
                                            @case('pending')
                                                <span class="badge bg-warning text-dark">Pending</span>
                                                @break
                                            @case('failed')
                                                <span class="badge bg-danger">Failed</span>
                                                @break
                                            @case('cancelled')
                                                <span class="badge bg-secondary">Cancelled</span>
                                                @break
                                            @default
                                                <span class="badge bg-light text-dark">{{ $enrollment->payment_status }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <small>{{ $enrollment->created_at->format('d M Y') }}</small><br>
                                        <small class="text-muted">{{ $enrollment->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('app.trainer.enrollments.show', $enrollment) }}" class="btn btn-outline-primary" title="View Details">
                                                <i class="isax isax-eye"></i>
                                            </a>
                                            @if($enrollment->payment_status === 'pending' && $enrollment->payment_method === 'manual')
                                            <button type="button" class="btn btn-outline-success" title="Approve" data-bs-toggle="modal" data-bs-target="#approveModal{{ $enrollment->id }}">
                                                <i class="isax isax-tick-circle"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger" title="Reject" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $enrollment->id }}">
                                                <i class="isax isax-close-circle"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                @if($enrollment->payment_status === 'pending' && $enrollment->payment_method === 'manual')
                                <!-- Approve Modal -->
                                <div class="modal fade" id="approveModal{{ $enrollment->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Approve Payment</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure you want to approve the payment for:</p>
                                                <ul>
                                                    <li><strong>Student:</strong> {{ $enrollment->user->name ?? 'N/A' }}</li>
                                                    <li><strong>Course:</strong> {{ $enrollment->course->title }}</li>
                                                    <li><strong>Amount:</strong> RM{{ number_format($enrollment->amount, 2) }}</li>
                                                </ul>
                                                @if($enrollment->payment_proof)
                                                <div class="mt-3">
                                                    <p class="mb-2"><strong>Payment Proof:</strong></p>
                                                    <a href="{{ Storage::url($enrollment->payment_proof) }}" target="_blank">
                                                        <img src="{{ Storage::url($enrollment->payment_proof) }}" alt="Payment Proof" class="img-fluid rounded" style="max-height: 200px;">
                                                    </a>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('app.trainer.enrollments.approve', $enrollment) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="isax isax-tick-circle me-1"></i>Approve Payment
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Reject Modal -->
                                <div class="modal fade" id="rejectModal{{ $enrollment->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('app.trainer.enrollments.reject', $enrollment) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Reject Payment</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to reject the payment for:</p>
                                                    <ul>
                                                        <li><strong>Student:</strong> {{ $enrollment->user->name ?? 'N/A' }}</li>
                                                        <li><strong>Course:</strong> {{ $enrollment->course->title }}</li>
                                                        <li><strong>Amount:</strong> RM{{ number_format($enrollment->amount, 2) }}</li>
                                                    </ul>
                                                    <div class="mb-3">
                                                        <label class="form-label">Reason for Rejection (Optional)</label>
                                                        <textarea name="rejection_reason" class="form-control" rows="3" placeholder="e.g., Payment proof unclear, amount mismatch..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="isax isax-close-circle me-1"></i>Reject Payment
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($enrollments->hasPages())
                    <div class="card-footer">
                        {{ $enrollments->links() }}
                    </div>
                    @endif
                    @else
                    <div class="text-center py-5">
                        <i class="isax isax-people fs-1 text-muted mb-3 d-block"></i>
                        <h5>No Enrollments Found</h5>
                        <p class="text-muted">
                            @if(request()->hasAny(['search', 'course', 'payment_status', 'payment_method']))
                            No enrollments match your filter criteria.
                            @else
                            You don't have any enrollments yet.
                            @endif
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
