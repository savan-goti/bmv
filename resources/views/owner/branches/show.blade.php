@extends('owner.master')
@section('title','View Branch')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Branch Details</h4>
                <div class="page-title-right">
                    <a href="{{ route('owner.branch-positions.index', ['branch_id' => $branch->id]) }}" class="btn btn-success">
                        <i class="bx bx-user-plus"></i> Manage Positions
                    </a>
                    <a href="{{ route('owner.branches.edit', $branch->id) }}" class="btn btn-primary">
                        <i class="bx bx-edit"></i> Edit
                    </a>
                    <a href="{{ route('owner.branches.index') }}" class="btn btn-secondary">
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
                    <h5 class="card-title mb-0">Branch Information</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <th class="ps-0" scope="row" style="width: 200px;">Branch Name:</th>
                                    <td class="text-muted">{{ $branch->name }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Branch Code:</th>
                                    <td class="text-muted"><span class="badge bg-primary">{{ $branch->code }}</span></td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Branch Type:</th>
                                    <td class="text-muted">
                                        <span class="badge bg-{{ $branch->type == 'product' ? 'info' : 'warning' }}">
                                            {{ ucfirst($branch->type) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Username:</th>
                                    <td class="text-muted"><code>{{ $branch->username ?? 'N/A' }}</code></td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Branch Link:</th>
                                    <td class="text-muted">
                                        @if($branch->branch_link)
                                            <a href="{{ $branch->branch_link }}" target="_blank" class="text-primary">
                                                {{ $branch->branch_link }}
                                                <i class="bx bx-link-external"></i>
                                            </a>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Email:</th>
                                    <td class="text-muted">{{ $branch->email ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Phone:</th>
                                    <td class="text-muted">{{ $branch->phone ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Status:</th>
                                    <td class="text-muted">
                                        <span class="badge bg-{{ $branch->status == 'active' ? 'success' : 'danger' }}">
                                            {{ ucfirst($branch->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Opening Date:</th>
                                    <td class="text-muted">
                                        {{ $branch->opening_date ? \Carbon\Carbon::parse($branch->opening_date)->format('M d, Y') : 'N/A' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Location Details</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <th class="ps-0" scope="row" style="width: 200px;">Address:</th>
                                    <td class="text-muted">{{ $branch->address ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">City:</th>
                                    <td class="text-muted">{{ $branch->city ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">State:</th>
                                    <td class="text-muted">{{ $branch->state ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Country:</th>
                                    <td class="text-muted">{{ $branch->country ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Postal Code:</th>
                                    <td class="text-muted">{{ $branch->postal_code ?? 'N/A' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Manager Information</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <th class="ps-0" scope="row" style="width: 200px;">Manager Name:</th>
                                    <td class="text-muted">{{ $branch->manager_name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Manager Phone:</th>
                                    <td class="text-muted">{{ $branch->manager_phone ?? 'N/A' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @if($branch->social_media && count(array_filter($branch->social_media)) > 0)
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Social Media Links</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                @if(!empty($branch->social_media['facebook_url']))
                                <tr>
                                    <th class="ps-0" scope="row" style="width: 200px;"><i class="bx bxl-facebook text-primary"></i> Facebook:</th>
                                    <td class="text-muted">
                                        <a href="{{ $branch->social_media['facebook_url'] }}" target="_blank" class="text-primary">
                                            {{ $branch->social_media['facebook_url'] }}
                                        </a>
                                    </td>
                                </tr>
                                @endif

                                @if(!empty($branch->social_media['instagram_url']))
                                <tr>
                                    <th class="ps-0" scope="row"><i class="bx bxl-instagram text-danger"></i> Instagram:</th>
                                    <td class="text-muted">
                                        <a href="{{ $branch->social_media['instagram_url'] }}" target="_blank" class="text-primary">
                                            {{ $branch->social_media['instagram_url'] }}
                                        </a>
                                    </td>
                                </tr>
                                @endif

                                @if(!empty($branch->social_media['twitter_url']))
                                <tr>
                                    <th class="ps-0" scope="row"><i class="bx bxl-twitter text-info"></i> Twitter:</th>
                                    <td class="text-muted">
                                        <a href="{{ $branch->social_media['twitter_url'] }}" target="_blank" class="text-primary">
                                            {{ $branch->social_media['twitter_url'] }}
                                        </a>
                                    </td>
                                </tr>
                                @endif

                                @if(!empty($branch->social_media['linkedin_url']))
                                <tr>
                                    <th class="ps-0" scope="row"><i class="bx bxl-linkedin text-primary"></i> LinkedIn:</th>
                                    <td class="text-muted">
                                        <a href="{{ $branch->social_media['linkedin_url'] }}" target="_blank" class="text-primary">
                                            {{ $branch->social_media['linkedin_url'] }}
                                        </a>
                                    </td>
                                </tr>
                                @endif

                                @if(!empty($branch->social_media['youtube_url']))
                                <tr>
                                    <th class="ps-0" scope="row"><i class="bx bxl-youtube text-danger"></i> YouTube:</th>
                                    <td class="text-muted">
                                        <a href="{{ $branch->social_media['youtube_url'] }}" target="_blank" class="text-primary">
                                            {{ $branch->social_media['youtube_url'] }}
                                        </a>
                                    </td>
                                </tr>
                                @endif

                                @if(!empty($branch->social_media['pinterest_url']))
                                <tr>
                                    <th class="ps-0" scope="row"><i class="bx bxl-pinterest text-danger"></i> Pinterest:</th>
                                    <td class="text-muted">
                                        <a href="{{ $branch->social_media['pinterest_url'] }}" target="_blank" class="text-primary">
                                            {{ $branch->social_media['pinterest_url'] }}
                                        </a>
                                    </td>
                                </tr>
                                @endif

                                @if(!empty($branch->social_media['tiktok_url']))
                                <tr>
                                    <th class="ps-0" scope="row"><i class="bx bxl-tiktok text-dark"></i> TikTok:</th>
                                    <td class="text-muted">
                                        <a href="{{ $branch->social_media['tiktok_url'] }}" target="_blank" class="text-primary">
                                            {{ $branch->social_media['tiktok_url'] }}
                                        </a>
                                    </td>
                                </tr>
                                @endif

                                @if(!empty($branch->social_media['whatsapp_url']))
                                <tr>
                                    <th class="ps-0" scope="row"><i class="bx bxl-whatsapp text-success"></i> WhatsApp:</th>
                                    <td class="text-muted">
                                        <a href="{{ $branch->social_media['whatsapp_url'] }}" target="_blank" class="text-primary">
                                            {{ $branch->social_media['whatsapp_url'] }}
                                        </a>
                                    </td>
                                </tr>
                                @endif

                                @if(!empty($branch->social_media['telegram_url']))
                                <tr>
                                    <th class="ps-0" scope="row"><i class="bx bxl-telegram text-info"></i> Telegram:</th>
                                    <td class="text-muted">
                                        <a href="{{ $branch->social_media['telegram_url'] }}" target="_blank" class="text-primary">
                                            {{ $branch->social_media['telegram_url'] }}
                                        </a>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            @if($branch->positions && $branch->positions->count() > 0)
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Assigned Positions ({{ $branch->positions->count() }})</h5>
                    <a href="{{ route('owner.branch-positions.create', ['branch_id' => $branch->id]) }}" class="btn btn-sm btn-primary">
                        <i class="bx bx-plus"></i> Add Position
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Person</th>
                                    <th>Type</th>
                                    <th>Job Position</th>
                                    <th>Start Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($branch->positions as $position)
                                <tr>
                                    <td>{{ $position->positionable ? $position->positionable->name : 'N/A' }}</td>
                                    <td><span class="badge bg-info">{{ class_basename($position->positionable_type) }}</span></td>
                                    <td>{{ $position->jobPosition ? $position->jobPosition->name : 'N/A' }}</td>
                                    <td>{{ $position->start_date ? \Carbon\Carbon::parse($position->start_date)->format('M d, Y') : 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $position->is_active ? 'success' : 'danger' }}">
                                            {{ $position->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            @if($branch->branch_link)
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Branch QR Code</h5>
                </div>
                <div class="card-body text-center">
                    <div id="qrcode" class="mb-3"></div>
                    <p class="text-muted mb-2">Scan to visit branch page</p>
                    <a href="{{ $branch->branch_link }}" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="bx bx-link-external"></i> Visit Branch Page
                    </a>
                </div>
            </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('owner.branch-positions.create', ['branch_id' => $branch->id]) }}" class="btn btn-soft-success">
                            <i class="bx bx-user-plus"></i> Assign Position
                        </a>
                        <a href="{{ route('owner.branches.edit', $branch->id) }}" class="btn btn-soft-primary">
                            <i class="bx bx-edit"></i> Edit Branch
                        </a>
                        <button type="button" class="btn btn-soft-danger delete-branch" data-id="{{ $branch->id }}">
                            <i class="bx bx-trash"></i> Delete Branch
                        </button>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <i class="ri-team-line display-6 text-primary"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">{{ $branch->positions ? $branch->positions->count() : 0 }}</h6>
                            <p class="text-muted mb-0">Total Positions</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ri-user-follow-line display-6 text-success"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">{{ $branch->positions ? $branch->positions->where('is_active', true)->count() : 0 }}</h6>
                            <p class="text-muted mb-0">Active Positions</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Timeline</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="text-muted mb-1">Created</p>
                        <h6 class="mb-0">{{ $branch->created_at->format('M d, Y h:i A') }}</h6>
                    </div>
                    <div>
                        <p class="text-muted mb-1">Last Updated</p>
                        <h6 class="mb-0">{{ $branch->updated_at->format('M d, Y h:i A') }}</h6>
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
        $('.delete-branch').click(function() {
            var id = $(this).data('id');
            var url = "{{ route('owner.branches.destroy', ':id') }}".replace(':id', id);
            
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this! All associated positions will also be deleted.",
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
                                    window.location.href = "{{ route('owner.branches.index') }}";
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

        // Generate QR Code for branch link
        @if($branch->branch_link)
        var qrcode = new QRCode(document.getElementById("qrcode"), {
            text: "{{ $branch->branch_link }}",
            width: 200,
            height: 200,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });
        @endif
    });
</script>

<!-- QR Code Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

@endsection
