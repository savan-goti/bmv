@extends('owner.master')
@section('title','Collections')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Collections</h4>
                <div class="page-title-right">
                    <a href="{{ route('owner.collections.create') }}" class="btn btn-primary">Create New Collection</a>
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
                                    <th>Name</th>
                                    <th>Products</th>
                                    <th>Dates</th>
                                    <th>Featured</th>
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
                url: "{{ route('owner.collections.ajaxData') }}"
            },
            columns: [
                { data: 'id', name: 'id', title: 'ID' },
                { data: 'image', name: 'image', title: 'Image', orderable: false, searchable: false },
                { data: 'name', name: 'name', title: 'Name' },
                { data: 'products_count', name: 'products_count', title: 'Products', orderable: false, searchable: false },
                { data: 'dates', name: 'dates', title: 'Dates', orderable: false },
                { data: 'featured', name: 'is_featured', title: 'Featured' },
                { data: 'status', name: 'status', title: 'Status', orderable: false },
                { data: 'action', name: 'action', title: 'Action', orderable: false, searchable: false },
            ]
        });

        // Status toggle
        $(document).on('change', '.status-toggle', function() {
            var id = $(this).data('id');
            var url = "{{ route('owner.collections.status', ':id') }}".replace(':id', id);
            
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
            var url = "{{ route('owner.collections.destroy', ':id') }}".replace(':id', id);
            
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
