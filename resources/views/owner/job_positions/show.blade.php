@extends('owner.master')
@section('title','View Job Position')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Job Position Details</h4>
                <div class="page-title-right">
                    <a href="{{ route('owner.job-positions.edit', $jobPosition->id) }}" class="btn btn-primary">
                        <i class="bx bx-edit"></i> Edit
                    </a>
                    <a href="{{ route('owner.job-positions.index') }}" class="btn btn-secondary">
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
                    <h5 class="card-title mb-0">Position Information</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <th class="ps-0" scope="row" style="width: 200px;">Position Name:</th>
                                    <td class="text-muted">{{ $jobPosition->name }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Department:</th>
                                    <td class="text-muted">{{ $jobPosition->department ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Level:</th>
                                    <td class="text-muted">
                                        @if($jobPosition->level)
                                            <span class="badge bg-info">{{ ucfirst($jobPosition->level) }}</span>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Status:</th>
                                    <td class="text-muted">
                                        <span class="badge bg-{{ $jobPosition->status == 'active' ? 'success' : 'danger' }}">
                                            {{ ucfirst($jobPosition->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Created At:</th>
                                    <td class="text-muted">{{ $jobPosition->created_at->format('M d, Y h:i A') }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Last Updated:</th>
                                    <td class="text-muted">{{ $jobPosition->updated_at->format('M d, Y h:i A') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @if($jobPosition->description)
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Description</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-0">{{ $jobPosition->description }}</p>
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('owner.job-positions.edit', $jobPosition->id) }}" class="btn btn-soft-primary">
                            <i class="bx bx-edit"></i> Edit Position
                        </a>
                        <button type="button" class="btn btn-soft-danger delete-position" data-id="{{ $jobPosition->id }}">
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
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <i class="ri-briefcase-line display-6 text-muted"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">{{ $jobPosition->name }}</h6>
                            <p class="text-muted mb-0">
                                @if($jobPosition->department)
                                    {{ $jobPosition->department }}
                                    @if($jobPosition->level)
                                        - {{ ucfirst($jobPosition->level) }}
                                    @endif
                                @else
                                    No department assigned
                                @endif
                            </p>
                        </div>
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
            var url = "{{ route('owner.job-positions.destroy', ':id') }}".replace(':id', id);
            
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
                                    window.location.href = "{{ route('owner.job-positions.index') }}";
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
