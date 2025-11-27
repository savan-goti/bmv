@extends('owner.master')

@section('title','Admin Details')

@section('main')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Admin Details</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('owner.admins.index') }}">Admins</a></li>
                        <li class="breadcrumb-item active">Details</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-4">
                            <div class="text-center">
                                @if ($admin->profile_image)
                                    <img src="{{ $admin->profile_image }}" alt="{{ $admin->name }}"
                                        class="img-fluid rounded-circle" style="max-width: 250px;">
                                @else
                                    <div class="avatar-xl mx-auto mb-3">
                                        <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-24"
                                            style="line-height: 250px; font-size: 100px;">
                                            {{ strtoupper(substr($admin->name, 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                                <h5 class="mt-3 mb-1">{{ $admin->name }}</h5>
                                <p class="text-muted">{{ ucfirst($admin->role) }}</p>
                                <span class="badge bg-{{ $admin->status == 'active' ? 'success' : 'danger' }} font-size-12">
                                    {{ ucfirst($admin->status) }}
                                </span>
                            </div>
                        </div>

                        <div class="col-xl-8">
                            <div class="mt-4 mt-xl-3">
                                <h5 class="mb-3">Personal Information</h5>
                                <div class="table-responsive">
                                    <table class="table mb-0 table-bordered">
                                        <tbody>
                                            <tr>
                                                <th scope="row" style="width: 300px;">Full Name</th>
                                                <td>{{ $admin->name }}</td>
                                            </tr>
                                            @if ($admin->father_name)
                                                <tr>
                                                    <th scope="row">Father's Name</th>
                                                    <td>{{ $admin->father_name }}</td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <th scope="row">Email</th>
                                                <td>{{ $admin->email }}</td>
                                            </tr>
                                            @if ($admin->phone)
                                                <tr>
                                                    <th scope="row">Phone</th>
                                                    <td>{{ $admin->phone }}</td>
                                                </tr>
                                            @endif
                                            @if ($admin->date_of_birth)
                                                <tr>
                                                    <th scope="row">Date of Birth</th>
                                                    <td>{{ \Carbon\Carbon::parse($admin->date_of_birth)->format('d M, Y') }}</td>
                                                </tr>
                                            @endif
                                            @if ($admin->gender)
                                                <tr>
                                                    <th scope="row">Gender</th>
                                                    <td>{{ ucfirst($admin->gender) }}</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="mt-4">
                                <h5 class="mb-3">Professional Details</h5>
                                <div class="table-responsive">
                                    <table class="table mb-0 table-bordered">
                                        <tbody>
                                            <tr>
                                                <th scope="row" style="width: 300px;">Role</th>
                                                <td>{{ ucfirst($admin->role) }}</td>
                                            </tr>
                                            @if ($admin->jobPosition)
                                                <tr>
                                                    <th scope="row">Position</th>
                                                    <td>{{ $admin->jobPosition->name }}</td>
                                                </tr>
                                            @endif
                                            @if ($admin->education)
                                                <tr>
                                                    <th scope="row">Education</th>
                                                    <td>{{ $admin->education }}</td>
                                                </tr>
                                            @endif
                                            @if ($admin->address)
                                                <tr>
                                                    <th scope="row">Address</th>
                                                    <td>{{ $admin->address }}</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            @if($admin->branchPositions->count() > 0)
                            <div class="mt-4">
                                <h5 class="mb-3">Assigned Positions</h5>
                                <div class="table-responsive">
                                    <table class="table mb-0 table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Branch</th>
                                                <th>Position</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Salary</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($admin->branchPositions as $branchPosition)
                                            <tr>
                                                <td>{{ $branchPosition->branch->name ?? 'N/A' }}</td>
                                                <td>{{ $branchPosition->jobPosition->name ?? 'N/A' }}</td>
                                                <td>{{ $branchPosition->start_date ? \Carbon\Carbon::parse($branchPosition->start_date)->format('d M, Y') : 'N/A' }}</td>
                                                <td>{{ $branchPosition->end_date ? \Carbon\Carbon::parse($branchPosition->end_date)->format('d M, Y') : 'Ongoing' }}</td>
                                                <td>{{ $branchPosition->salary ? '₹' . number_format($branchPosition->salary, 2) : 'N/A' }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $branchPosition->is_active ? 'success' : 'secondary' }}">
                                                        {{ $branchPosition->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endif

                            <div class="mt-4">
                                <h5 class="mb-3">Account Information</h5>
                                <div class="table-responsive">
                                    <table class="table mb-0 table-bordered">
                                        <tbody>
                                            <tr>
                                                <th scope="row" style="width: 300px;">Status</th>
                                                <td>
                                                    <span class="badge bg-{{ $admin->status == 'active' ? 'success' : 'danger' }}">
                                                        {{ ucfirst($admin->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Created At</th>
                                                <td>{{ $admin->created_at->format('d M, Y h:i A') }}</td>
                                            </tr>
                                            @if ($admin->resignation_date)
                                                <tr>
                                                    <th scope="row">Resignation Date</th>
                                                    <td>{{ \Carbon\Carbon::parse($admin->resignation_date)->format('d M, Y') }}</td>
                                                </tr>
                                            @endif
                                            @if ($admin->purpose)
                                                <tr>
                                                    <th scope="row">Purpose</th>
                                                    <td>{{ $admin->purpose }}</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="mt-4">
                                <a href="{{ route('owner.admins.edit', $admin->id) }}" class="btn btn-primary me-2">
                                    <i class="bx bx-edit"></i> Edit Admin
                                </a>
                                <a href="{{ route('owner.admins.index') }}" class="btn btn-secondary">
                                    <i class="bx bx-arrow-back"></i> Back to List
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
