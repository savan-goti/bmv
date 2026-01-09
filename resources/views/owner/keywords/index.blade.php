@extends('owner.master')
@section('title','Keywords')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Keywords</h4>
                <div class="page-title-right">
                    <a href="{{ route('owner.master.keywords.create') }}" class="btn btn-primary">Create New Keyword</a>
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
                url: "{{ route('owner.master.keywords.ajaxData') }}"
            },
            columns: [
                { data: 'id', name: 'id', title: 'ID' },
                { data: 'name', name: 'name', title: 'Name' },
                { data: 'type', name: 'type', title: 'Type' },
                { data: 'description', name: 'description', title: 'Description' },
                { data: 'status', name: 'status', title: 'Status', orderable: false },
                { data: 'action', name: 'action', title: 'Action', orderable: false, searchable: false },
            ]
        });

        // Status toggle
        $(document).on('change', '.status-toggle', function() {
            var id = $(this).data('id');
            var url = "{{ route('owner.master.keywords.status', ':id') }}".replace(':id', id);
            
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
            var url = "{{ route('owner.master.keywords.destroy', ':id') }}".replace(':id', id);
            
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
