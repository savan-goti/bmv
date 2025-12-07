@extends('admin.master')
@section('title','Support Team Member Details')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Support Team Member Details</h4>
                <div class="page-title-right">
                    <a href="{{ route('admin.support-team.edit', $supportTeamMember->id) }}" class="btn btn-primary">Edit</a>
                    <a href="{{ route('admin.support-team.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <img src="{{ $supportTeamMember->profile_image }}" alt="Profile" class="img-thumbnail mb-3" style="max-width: 200px;">
                    <h5>{{ $supportTeamMember->name }}</h5>
                    <p class="text-muted">{{ $supportTeamMember->role->label() }}</p>
                    <span class="badge bg-{{ $supportTeamMember->status == 'active' ? 'success' : 'danger' }}">{{ ucfirst($supportTeamMember->status) }}</span>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Stats</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Tickets Assigned</small>
                        <h4>{{ $supportTeamMember->tickets_assigned }}</h4>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Open Tickets</small>
                        <h4>{{ $supportTeamMember->open_tickets }}</h4>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Avg Response Time</small>
                        <h4>{{ $supportTeamMember->avg_response_time ? number_format($supportTeamMember->avg_response_time, 2) . ' min' : 'N/A' }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Member Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Email:</strong></div>
                        <div class="col-md-8">{{ $supportTeamMember->email }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Phone:</strong></div>
                        <div class="col-md-8">{{ $supportTeamMember->phone ?? 'N/A' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Role:</strong></div>
                        <div class="col-md-8">{{ $supportTeamMember->role->label() }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Status:</strong></div>
                        <div class="col-md-8">
                            <span class="badge bg-{{ $supportTeamMember->status == 'active' ? 'success' : 'danger' }}">{{ ucfirst($supportTeamMember->status) }}</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Notification Method:</strong></div>
                        <div class="col-md-8">{{ ucfirst(str_replace('_', ' ', $supportTeamMember->notification_method)) }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Departments:</strong></div>
                        <div class="col-md-8">
                            @if($departments->count() > 0)
                                @foreach($departments as $department)
                                    <span class="badge bg-info me-1">{{ $department->name }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">No departments assigned</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Default Queues:</strong></div>
                        <div class="col-md-8">
                            @if($queues->count() > 0)
                                @foreach($queues as $queue)
                                    <span class="badge bg-primary me-1">{{ $queue->name }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">No queues assigned</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Email Verified:</strong></div>
                        <div class="col-md-8">
                            @if($supportTeamMember->email_verified_at)
                                <span class="badge bg-success">Verified</span>
                                <small class="text-muted">({{ $supportTeamMember->email_verified_at->format('M d, Y') }})</small>
                            @else
                                <span class="badge bg-warning">Not Verified</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Last Login:</strong></div>
                        <div class="col-md-8">
                            @if($supportTeamMember->last_login_at)
                                {{ $supportTeamMember->last_login_at->format('M d, Y H:i:s') }}
                                <br><small class="text-muted">IP: {{ $supportTeamMember->last_login_ip }}</small>
                            @else
                                <span class="text-muted">Never logged in</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Created:</strong></div>
                        <div class="col-md-8">{{ $supportTeamMember->created_at->format('M d, Y H:i:s') }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Updated:</strong></div>
                        <div class="col-md-8">{{ $supportTeamMember->updated_at->format('M d, Y H:i:s') }}</div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Audit Log</h5>
                </div>
                <div class="card-body">
                    @if($supportTeamMember->auditLogs->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>Description</th>
                                        <th>Performed By</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($supportTeamMember->auditLogs->sortByDesc('created_at')->take(10) as $log)
                                        <tr>
                                            <td><span class="badge bg-secondary">{{ $log->action }}</span></td>
                                            <td>{{ $log->description }}</td>
                                            <td>{{ $log->performed_by_type ? class_basename($log->performed_by_type) : 'System' }}</td>
                                            <td>{{ $log->created_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No audit logs available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
