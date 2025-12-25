@extends('owner.master')
@section('title','Categories')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Categories</h4>
                <div class="page-title-right">
                    <a href="{{ route('owner.categories.create') }}" class="btn btn-primary">Create New Category</a>
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
                            <select name="category_type" id="filter-category-type" class="form-select">
                                <option value="">All Category Types</option>
                                <option value="product">Product</option>
                                <option value="service">Service</option>
                                <option value="digital">Digital</option>
                                <option value="mixed">Mixed</option>
                            </select>
                        </div>
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
                url: "{{ route('owner.categories.ajaxData') }}",
                data: function (d) {
                    d.category_type = $('#filter-category-type').val();
                    d.status = $('#filter-status').val();
                }
            },
            columns: [
                { data: 'id', name: 'id', title: 'ID' },
                { data: 'image', name: 'image', title: 'Image', orderable: false, searchable: false },
                { data: 'name', name: 'name', title: 'Name' },
                { data: 'category_type', name: 'category_type', title: 'Category Type' },
                { data: 'status', name: 'status', title: 'Status' },
                { data: 'action', name: 'action', title: 'Action', orderable: false, searchable: false },
            ],
            drawCallback: function() {
                document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
                    new bootstrap.Tooltip(el);
                });
            }
        });

        $('#filter-category-type').change(function(){
            table.draw();
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

        $(document).on('change', '.status-toggle', function() {
            var url = $(this).data('url');
            var status = $(this).prop('checked');
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: status
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
    });
</script>
@endsection
