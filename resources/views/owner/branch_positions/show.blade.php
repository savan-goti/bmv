@extends('owner.master')
@section('title','View Branch Position')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Branch Position Details</h4>
                <div class="page-title-right">
                    <a href="{{ route('owner.branch-positions.edit', $branchPosition->id) }}" class="btn btn-primary">
                        <i class="bx bx-edit"></i> Edit
                    </a>
                    <a href="{{ route('owner.branch-positions.index') }}" class="btn btn-secondary">
                        <i class="bx bx-arrow-back"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Position Assignment Information</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <th class="ps-0" scope="row" style="width: 200px;">Branch:</th>
                                    <td class="text-muted">
                                        @if($branchPosition->branch)
                                            <a href="{{ route('owner.branches.show', $branchPosition->branch->id) }}">
                                                {{ $branchPosition->branch->name }} ({{ $branchPosition->branch->code }})
                                            </a>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Person Type:</th>
                                    <td class="text-muted">
                                        <span class="badge bg-info">{{ class_basename($branchPosition->positionable_type) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Person Name:</th>
                                    <td class="text-muted">
                                        @if($branchPosition->positionable)
                                            {{ $branchPosition->positionable->name }}
                                            @if($branchPosition->positionable->email)
                                                <br><small class="text-muted">{{ $branchPosition->positionable->email }}</small>
                                            @endif
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Job Position:</th>
                                    <td class="text-muted">
                                        @if($branchPosition->jobPosition)
                                            {{ $branchPosition->jobPosition->name }}
                                            @if($branchPosition->jobPosition->department)
                                                <br><small class="text-muted">{{ $branchPosition->jobPosition->department }}</small>
                                            @endif
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Status:</th>
                                    <td class="text-muted">
                                        <span class="badge bg-{{ $branchPosition->is_active ? 'success' : 'danger' }}">
                                            {{ $branchPosition->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Employment Details</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <th class="ps-0" scope="row" style="width: 200px;">Start Date:</th>
                                    <td class="text-muted">
                                        {{ $branchPosition->start_date ? \Carbon\Carbon::parse($branchPosition->start_date)->format('M d, Y') : 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">End Date:</th>
                                    <td class="text-muted">
                                        {{ $branchPosition->end_date ? \Carbon\Carbon::parse($branchPosition->end_date)->format('M d, Y') : 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Duration:</th>
                                    <td class="text-muted">
                                        @if($branchPosition->start_date)
                                            @php
                                                $start = \Carbon\Carbon::parse($branchPosition->start_date);
                                                $end = $branchPosition->end_date ? \Carbon\Carbon::parse($branchPosition->end_date) : \Carbon\Carbon::now();
                                                $diff = $start->diff($end);
                                            @endphp
                                            @if($diff->y > 0) {{ $diff->y }} year{{ $diff->y > 1 ? 's' : '' }} @endif
                                            @if($diff->m > 0) {{ $diff->m }} month{{ $diff->m > 1 ? 's' : '' }} @endif
                                            @if($diff->d > 0) {{ $diff->d }} day{{ $diff->d > 1 ? 's' : '' }} @endif
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Salary:</th>
                                    <td class="text-muted">
                                        @if($branchPosition->salary)
                                            <strong>${{ number_format($branchPosition->salary, 2) }}</strong>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @if($branchPosition->notes)
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Notes</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-0">{{ $branchPosition->notes }}</p>
                </div>
            </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Timeline</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <th class="ps-0" scope="row" style="width: 200px;">Created At:</th>
                                    <td class="text-muted">{{ $branchPosition->created_at->format('M d, Y h:i A') }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Last Updated:</th>
                                    <td class="text-muted">{{ $branchPosition->updated_at->format('M d, Y h:i A') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('owner.branch-positions.edit', $branchPosition->id) }}" class="btn btn-soft-primary">
                            <i class="bx bx-edit"></i> Edit Position
                        </a>
                        @if($branchPosition->branch)
                        <a href="{{ route('owner.branches.show', $branchPosition->branch->id) }}" class="btn btn-soft-info">
                            <i class="bx bx-building"></i> View Branch
                        </a>
                        @endif
                        <button type="button" class="btn btn-soft-danger delete-position" data-id="{{ $branchPosition->id }}">
                            <i class="bx bx-trash"></i> Delete Position
                        </button>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Summary</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="avatar-lg mx-auto mb-3">
                            <div class="avatar-title bg-soft-primary text-primary rounded-circle fs-1">
                                <i class="ri-user-location-line"></i>
                            </div>
                        </div>
                        <h5 class="mb-1">
                            @if($branchPosition->positionable)
                                {{ $branchPosition->positionable->name }}
                            @else
                                N/A
                            @endif
                        </h5>
                        <p class="text-muted mb-0">
                            @if($branchPosition->jobPosition)
                                {{ $branchPosition->jobPosition->name }}
                            @else
                                No Position
                            @endif
                        </p>
                        <p class="text-muted">
                            @if($branchPosition->branch)
                                at {{ $branchPosition->branch->name }}
                            @endif
                        </p>
                    </div>
                    
                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Type:</span>
                            <span class="badge bg-info">{{ class_basename($branchPosition->positionable_type) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Status:</span>
                            <span class="badge bg-{{ $branchPosition->is_active ? 'success' : 'danger' }}">
                                {{ $branchPosition->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        @if($branchPosition->salary)
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Salary:</span>
                            <strong>${{ number_format($branchPosition->salary, 2) }}</strong>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('.delete-position').click(function() {
            var id = $(this).data('id');
            var url = "{{ route('owner.branch-positions.destroy', ':id') }}".replace(':id', id);
            
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.status || response.success) {
                                Swal.fire(
                                    'Deleted!',
                                    response.message,
                                    'success'
                                ).then(() => {
                                    window.location.href = "{{ route('owner.branch-positions.index') }}";
                                });
                            } else {
                                Swal.fire('Error!', response.message, 'error');
                            }
                        },
                        error: function(xhr) {
                            Swal.fire('Error!', 'Something went wrong.', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
