@extends('owner.master')

@section('title','Customer Details')

@section('main')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Customer Details</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('owner.customers.index') }}">Customers</a></li>
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
                                @if ($customer->profile_image)
                                    <img src="{{ asset($customer->profile_image) }}" alt="{{ $customer->name }}"
                                        class="img-fluid rounded-circle mb-3" style="width: 250px; height: 250px; object-fit: cover;">
                                @else
                                    <div class="avatar-xl mx-auto mb-3">
                                        <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-24"
                                            style="line-height: 250px; font-size: 100px;">
                                            {{ strtoupper(substr($customer->name, 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                                <h5 class="mt-3 mb-1">{{ $customer->name }}</h5>
                                @if($customer->username)
                                    <p class="text-muted mb-1">{{ '@' . $customer->username }}</p>
                                @endif
                                <p class="text-muted">{{ $customer->email }}</p>
                                <span class="badge bg-{{ $customer->status == 'active' ? 'success' : 'danger' }} font-size-12">
                                    {{ ucfirst($customer->status) }}
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
                                                <td>{{ $customer->name }}</td>
                                            </tr>
                                            @if($customer->username)
                                                <tr>
                                                    <th scope="row">Username</th>
                                                    <td>{{ $customer->username }}</td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <th scope="row">Email</th>
                                                <td>{{ $customer->email }}</td>
                                            </tr>
                                            @if ($customer->phone)
                                                <tr>
                                                    <th scope="row">Phone</th>
                                                    <td>
                                                        @if($customer->country_code)
                                                            {{ $customer->country_code }} 
                                                        @endif
                                                        {{ $customer->phone }}
                                                    </td>
                                                </tr>
                                            @endif
                                            @if ($customer->dob)
                                                <tr>
                                                    <th scope="row">Date of Birth</th>
                                                    <td>{{ \Carbon\Carbon::parse($customer->dob)->format('d M, Y') }}</td>
                                                </tr>
                                            @endif
                                            @if ($customer->gender)
                                                <tr>
                                                    <th scope="row">Gender</th>
                                                    <td>{{ ucfirst($customer->gender) }}</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="mt-4">
                                <h5 class="mb-3">Location Details</h5>
                                <div class="table-responsive">
                                    <table class="table mb-0 table-bordered">
                                        <tbody>
                                            @if ($customer->address)
                                                <tr>
                                                    <th scope="row" style="width: 300px;">Address</th>
                                                    <td>{{ $customer->address }}</td>
                                                </tr>
                                            @endif
                                            @if ($customer->city)
                                                <tr>
                                                    <th scope="row">City</th>
                                                    <td>{{ $customer->city }}</td>
                                                </tr>
                                            @endif
                                            @if ($customer->state)
                                                <tr>
                                                    <th scope="row">State</th>
                                                    <td>{{ $customer->state }}</td>
                                                </tr>
                                            @endif
                                            @if ($customer->country)
                                                <tr>
                                                    <th scope="row">Country</th>
                                                    <td>{{ $customer->country }}</td>
                                                </tr>
                                            @endif
                                            @if ($customer->pincode)
                                                <tr>
                                                    <th scope="row">Pincode</th>
                                                    <td>{{ $customer->pincode }}</td>
                                                </tr>
                                            @endif
                                            @if ($customer->latitude && $customer->longitude)
                                                <tr>
                                                    <th scope="row">Coordinates</th>
                                                    <td>{{ $customer->latitude }}, {{ $customer->longitude }}</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            @if($customer->social_links && count($customer->social_links) > 0)
                                <div class="mt-4">
                                    <h5 class="mb-3">Social Links</h5>
                                    <div class="table-responsive">
                                        <table class="table mb-0 table-bordered">
                                            <tbody>
                                                @foreach($customer->social_links as $platform => $link)
                                                    <tr>
                                                        <th scope="row" style="width: 300px;">{{ ucfirst($platform) }}</th>
                                                        <td><a href="{{ $link }}" target="_blank">{{ $link }}</a></td>
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
                                                    <span class="badge bg-{{ $customer->status == 'active' ? 'success' : 'danger' }}">
                                                        {{ ucfirst($customer->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Registered At</th>
                                                <td>{{ $customer->created_at->format('d M, Y h:i A') }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Last Updated</th>
                                                <td>{{ $customer->updated_at->format('d M, Y h:i A') }}</td>
                                            </tr>
                                            <!-- @if($customer->canonical)
                                                <tr>
                                                    <th scope="row">Profile Link</th>
                                                    <td><a href="{{ $customer->canonical }}" target="_blank">{{ $customer->canonical }}</a></td>
                                                </tr>
                                            @endif -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="mt-4">
                                <a href="{{ route('owner.customers.edit', $customer->id) }}" class="btn btn-primary me-2">
                                    <i class="bx bx-edit"></i> Edit Customer
                                </a>
                                <a href="{{ route('owner.customers.index') }}" class="btn btn-secondary">
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
