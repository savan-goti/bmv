@extends('owner.master')
@section('title','Suppliers')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Suppliers</h4>
                <div class="page-title-right">
                    <a href="{{ route('owner.master.suppliers.create') }}" class="btn btn-primary">Create New Supplier</a>
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
                url: "{{ route('owner.master.suppliers.ajaxData') }}",
            },
            columns: [
                { data: 'id', name: 'id', title: 'ID' },
                { data: 'name', name: 'name', title: 'Name' },
                { data: 'email', name: 'email', title: 'Email' },
                { data: 'phone', name: 'phone', title: 'Phone' },
                { data: 'status', name: 'status', title: 'Status', orderable: false, searchable: false },
                { data: 'action', name: 'action', title: 'Action', orderable: false, searchable: false },
            ]
        });

        $(document).on('change', '.status-toggle', function() {
            var id = $(this).data('id');
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
                        sendToast(response.message, 'success');
                    } else {
                        sendToast(response.message, 'danger');
                    }
                }
            });
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
                                Swal.fire('Deleted!', response.message, 'success').then(() => {
                                    table.draw();
                                });
                            } else {
                                Swal.fire('Error!', response.message, 'error');
                            }
                        }
                    });
                }
            })
        });
    });
</script>
@endsection
