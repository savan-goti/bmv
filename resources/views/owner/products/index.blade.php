@extends('owner.master')
@section('title','Products')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Products</h4>
                <div class="page-title-right">
                    <a href="{{ route('owner.products.create') }}" class="btn btn-primary">Create New Product</a>
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
                            <select name="is_active" id="filter-status" class="form-select">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="product_status" id="filter-product-status" class="form-select">
                                <option value="">All Product Status</option>
                                <option value="draft">Draft</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>

                    </div>

                    <div class="table-responsive">
                        <table id="ajax-datatables" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>SKU</th>
                                    <th>Category</th>
                                    <th>Brand</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Product Status</th>
                                    <th>Active Status</th>
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
                url: "{{ route('owner.products.ajaxData') }}",
                data: function (d) {
                    d.is_active = $('#filter-status').val();
                    d.product_status = $('#filter-product-status').val();
                }
            },
            columns: [
                { data: 'id', name: 'id', title: 'ID' },
                { data: 'thumbnail_image', name: 'thumbnail_image', title: 'Image', orderable: false, searchable: false },
                { data: 'product_name', name: 'product_name', title: 'Name' },
                { data: 'sku', name: 'sku', title: 'SKU' },
                { data: 'category_name', name: 'category.name', title: 'Category' },
                { 
                    data: 'brand', 
                    name: 'brand.name', 
                    title: 'Brand',
                    render: function(data, type, row) {
                        return data && data.name ? data.name : 'N/A';
                    }
                },
                { data: 'sell_price', name: 'sell_price', title: 'Price' },
                { data: 'total_stock', name: 'total_stock', title: 'Stock' },
                { data: 'product_status', name: 'product_status', title: 'Product Status', orderable: false },
                { data: 'is_active', name: 'is_active', title: 'Active Status', orderable: false },
                { data: 'action', name: 'action', title: 'Action', orderable: false, searchable: false },
            ],
            drawCallback: function() {
                document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
                    new bootstrap.Tooltip(el);
                });
            }
        });

        $('#filter-status, #filter-product-status').change(function(){
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
