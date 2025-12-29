@extends('owner.master')
@section('title','HSN/SAC Codes')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">HSN/SAC Codes</h4>
                <div class="page-title-right">
                    <a href="{{ route('owner.master.hsn-sacs.create') }}" class="btn btn-primary">Create New HSN/SAC</a>
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
                url: "{{ route('owner.master.hsn-sacs.ajaxData') }}",
            },
            columns: [
                { data: 'id', name: 'id', title: 'ID' },
                { data: 'code', name: 'code', title: 'Code' },
                { data: 'description', name: 'description', title: 'Description' },
                { data: 'type', name: 'type', title: 'Type' },
                { data: 'status', name: 'status', title: 'Status', orderable: false, searchable: false },
                { data: 'action', name: 'action', title: 'Action', orderable: false, searchable: false },
            ]
        });

        $(document).on('change', '.status-toggle', function() {
            var url = $(this).data('url');
            var status = $(this).prop('checked');
            $.ajax({
                url: url,
                type: 'POST',
                data: { _token: '{{ csrf_token() }}', status: status },
                success: function(response) {
                    if (response.success) toastr.success(response.message);
                    else toastr.error(response.message);
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
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            if (response.status || response.success) {
                                Swal.fire('Deleted!', response.message, 'success').then(() => { table.draw(); });
                            } else { Swal.fire('Error!', response.message, 'error'); }
                        }
                    });
                }
            })
        });
    });
</script>
@endsection
