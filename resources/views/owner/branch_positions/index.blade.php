@extends('owner.master')
@section('title','Branch Positions')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Branch Positions @if($branch) - {{ $branch->name }} @endif</h4>
                <div class="page-title-right">
                    <a href="{{ route('owner.branch-positions.create', $branch ? ['branch_id' => $branch->id] : []) }}" class="btn btn-primary">Assign New Position</a>
                    @if($branch)
                        <a href="{{ route('owner.branches.index') }}" class="btn btn-secondary">Back to Branches</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="ajax-datatables" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    @if(!$branch)<th>Branch</th>@endif
                                    <th>Job Position</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
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
        var columns = [
            { data: 'id', name: 'id', title: 'ID' },
            @if(!$branch)
            { data: 'branch_name', name: 'branch.name', title: 'Branch' },
            @endif
            { data: 'job_position_name', name: 'jobPosition.name', title: 'Job Position' },
            { data: 'is_active', name: 'is_active', title: 'Status' },
            { data: 'action', name: 'action', title: 'Action', orderable: false, searchable: false },
        ];

        var table = $('#ajax-datatables').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('owner.branch-positions.ajaxData') }}",
                data: function (d) {
                    @if($branch)
                    d.branch_id = {{ $branch->id }};
                    @endif
                }
            },
            columns: columns,
            drawCallback: function() {
                document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
                    new bootstrap.Tooltip(el);
                });
            }
        });

        $(document).on('click', '.delete-item', function() {
            var url = $(this).data('url');
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
                                    table.draw();
                                });
                            } else {
                                Swal.fire(
                                    'Error!',
                                    response.message,
                                    'error'
                                );
                            }
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error!',
                                'Something went wrong.',
                                'error'
                            );
                        }
                    });
                }
            })
        });
    });
</script>
@endsection
