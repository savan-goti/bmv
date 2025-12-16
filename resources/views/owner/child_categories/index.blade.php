@extends('owner.master')
@section('title','Child Categories')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Child Categories</h4>
                <div class="page-title-right">
                    <a href="{{ route('owner.child-categories.create') }}" class="btn btn-primary">Create New Child Category</a>
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
                                    <th>Image</th>
                                    <th>Category</th>
                                    <th>Sub Category</th>
                                    <th>Name</th>
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
                url: "{{ route('owner.child-categories.ajaxData') }}"
            },
            columns: [
                { data: 'id', name: 'id', title: 'ID' },
                { data: 'image', name: 'image', title: 'Image', orderable: false, searchable: false },
                { data: 'category', name: 'category.name', title: 'Category' },
                { data: 'sub_category', name: 'subCategory.name', title: 'Sub Category' },
                { data: 'name', name: 'name', title: 'Name' },
                { data: 'status', name: 'status', title: 'Status', orderable: false },
                { data: 'action', name: 'action', title: 'Action', orderable: false, searchable: false },
            ],
            drawCallback: function() {
                document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
                    new bootstrap.Tooltip(el);
                });
            }
        });

        // Status toggle
        $(document).on('change', '.status-toggle', function() {
            var id = $(this).data('id');
            var url = "{{ route('owner.child-categories.status', ':id') }}".replace(':id', id);
            
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                    } else {
                        toastr.error('Something went wrong.');
                    }
                },
                error: function(xhr) {
                    toastr.error('Something went wrong.');
                }
            });
        });

        // Delete
        $(document).on('click', '.delete-btn', function() {
            var id = $(this).data('id');
            var url = "{{ route('owner.child-categories.destroy', ':id') }}".replace(':id', id);
            
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
                            if (response.success) {
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
                            var message = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Something went wrong.';
                            Swal.fire(
                                'Error!',
                                message,
                                'error'
                            );
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
