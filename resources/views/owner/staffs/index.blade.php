@extends('owner.master')
@section('title','Staffs')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Staff</h4>
                <div class="page-title-right">
                    <a href="{{ route('owner.staffs.create') }}" class="btn btn-primary">Create New Staff</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <select name="status" id="filter-status" class="form-select">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="ajax-datatables" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Role</th>
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
        var table = $('#ajax-datatables').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('owner.staffs.ajaxData') }}",
                data: function (d) {
                    d.status = $('#filter-status').val();
                }
            },
            columns: [
                { data: 'id', name: 'id', title: 'ID' },
                { data: 'name', name: 'name', title: 'Name' },
                { data: 'email', name: 'email', title: 'Email' },
                { data: 'phone', name: 'phone', title: 'Phone' },
                { data: 'assigned_role', name: 'assigned_role', title: 'Role' },
                { data: 'status', name: 'status', title: 'Status' },
                { data: 'action', name: 'action', title: 'Action', orderable: false, searchable: false },
            ],
            drawCallback: function() {
                document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
                    new bootstrap.Tooltip(el);
                });
            }
        });

        $('#filter-status').change(function(){
            table.draw();
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
