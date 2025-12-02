@extends('admin.master')

@section('title','Seller Details')

@section('main')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Seller Details</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.sellers.index') }}">Sellers</a></li>
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
                                <div class="avatar-xl mx-auto mb-3">
                                    @if($seller->business_logo && $seller->business_logo != asset('assets/img/no_img.jpg'))
                                        <img src="{{ $seller->business_logo }}" alt="" class="img-thumbnail rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-24"
                                            style="line-height: 100px; font-size: 40px;">
                                            {{ strtoupper(substr($seller->business_name, 0, 1)) }}
                                        </span>
                                    @endif
                                </div>
                                <h5 class="mt-3 mb-1">{{ $seller->business_name }}</h5>
                                <p class="text-muted">{{ $seller->owner_name }}</p>
                                <div class="mb-2">
                                    <span class="badge bg-{{ $seller->status == 'active' ? 'success' : ($seller->status == 'pending' ? 'warning' : 'danger') }} font-size-12">
                                        {{ ucfirst($seller->status) }}
                                    </span>
                                </div>
                                @if ($seller->is_approved)
                                    <span class="badge bg-success font-size-12">Approved</span>
                                @else
                                    <span class="badge bg-danger font-size-12">Not Approved</span>
                                @endif
                            </div>

                            @if($seller->social_links)
                                <div class="mt-4">
                                    <h5 class="font-size-14 mb-3 text-center">Social Media</h5>
                                    <ul class="list-inline text-center">
                                        @foreach($seller->social_links as $platform => $link)
                                            @if($link)
                                                <li class="list-inline-item">
                                                    <a href="{{ $link }}" target="_blank" class="social-list-item bg-primary text-white border-primary" title="{{ ucfirst($platform) }}">
                                                        @if($platform === 'website')
                                                            <i class="bx bx-globe font-size-20 p-2"></i>
                                                        @else
                                                            <i class="bx bxl-{{ $platform }} font-size-20 p-2"></i>
                                                        @endif
                                                    </a>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="mt-4">
                                <div class="d-grid gap-2">
                                    @if (!$seller->is_approved)
                                        <form action="{{ route('admin.sellers.approve', $seller->id) }}" method="POST" class="d-block">
                                            @csrf
                                            <input type="hidden" name="action" value="approve">
                                            <button type="submit" class="btn btn-success w-100 mb-2">
                                                <i class="bx bx-check"></i> Approve Seller
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.sellers.approve', $seller->id) }}" method="POST" class="d-block">
                                            @csrf
                                            <input type="hidden" name="action" value="reject">
                                            <button type="submit" class="btn btn-warning w-100 mb-2">
                                                <i class="bx bx-x"></i> Reject Seller
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('admin.sellers.edit', $seller->id) }}" class="btn btn-primary w-100 mb-2">
                                        <i class="bx bx-edit"></i> Edit Seller
                                    </a>
                                    <a href="{{ route('admin.sellers.index') }}" class="btn btn-secondary w-100">
                                        <i class="bx bx-arrow-back"></i> Back to List
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-8">
                            <div class="mt-4 mt-xl-0">
                                <h5 class="mb-3 text-primary">Basic Information</h5>
                                <div class="table-responsive">
                                    <table class="table mb-0 table-bordered">
                                        <tbody>
                                            <tr>
                                                <th scope="row" style="width: 200px;">Email</th>
                                                <td>{{ $seller->email }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Phone</th>
                                                <td>{{ $seller->phone }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Username</th>
                                                <td>{{ $seller->username }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Address</th>
                                                <td>{{ $seller->address }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">City / State</th>
                                                <td>{{ $seller->city }} / {{ $seller->state }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Country / Pincode</th>
                                                <td>{{ $seller->country }} - {{ $seller->pincode }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <h5 class="mb-3 mt-4 text-primary">Business Details</h5>
                                <div class="table-responsive">
                                    <table class="table mb-0 table-bordered">
                                        <tbody>
                                            <tr>
                                                <th scope="row" style="width: 200px;">Business Type</th>
                                                <td>{{ ucfirst(str_replace('_', ' ', $seller->business_type)) }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Category</th>
                                                <td>{{ $seller->category ? $seller->category->name : 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Sub Category</th>
                                                <td>{{ $seller->subCategory ? $seller->subCategory->name : 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Bar Code</th>
                                                <td>{{ $seller->bar_code }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Store Link</th>
                                                <td><a href="{{ $seller->store_link }}" target="_blank">{{ $seller->store_link }}</a></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <h5 class="mb-3 mt-4 text-primary">Personal & KYC</h5>
                                <div class="table-responsive">
                                    <table class="table mb-0 table-bordered">
                                        <tbody>
                                            <tr>
                                                <th scope="row" style="width: 200px;">Date of Birth</th>
                                                <td>{{ $seller->date_of_birth ? $seller->date_of_birth->format('d M, Y') : 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Gender</th>
                                                <td>{{ ucfirst($seller->gender) }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Aadhaar Number</th>
                                                <td>{{ $seller->aadhar_number }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">PAN Card Number</th>
                                                <td>{{ $seller->pancard_number }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">GST Number</th>
                                                <td>{{ $seller->gst_number }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Documents</th>
                                                <td>
                                                    <div class="row">
                                                        @if($seller->aadhaar_front && $seller->aadhaar_front != asset('assets/img/no_img.jpg'))
                                                            <div class="col-md-3 mb-2">
                                                                <p class="mb-1">Aadhaar Front</p>
                                                                <a href="{{ $seller->aadhaar_front }}" target="_blank">
                                                                    <img src="{{ $seller->aadhaar_front }}" class="img-thumbnail" style="height: 80px;">
                                                                </a>
                                                            </div>
                                                        @endif
                                                        @if($seller->aadhaar_back && $seller->aadhaar_back != asset('assets/img/no_img.jpg'))
                                                            <div class="col-md-3 mb-2">
                                                                <p class="mb-1">Aadhaar Back</p>
                                                                <a href="{{ $seller->aadhaar_back }}" target="_blank">
                                                                    <img src="{{ $seller->aadhaar_back }}" class="img-thumbnail" style="height: 80px;">
                                                                </a>
                                                            </div>
                                                        @endif
                                                        @if($seller->pancard_image && $seller->pancard_image != asset('assets/img/no_img.jpg'))
                                                            <div class="col-md-3 mb-2">
                                                                <p class="mb-1">PAN Card</p>
                                                                <a href="{{ $seller->pancard_image }}" target="_blank">
                                                                    <img src="{{ $seller->pancard_image }}" class="img-thumbnail" style="height: 80px;">
                                                                </a>
                                                            </div>
                                                        @endif
                                                        @if($seller->gst_certificate_image && $seller->gst_certificate_image != asset('assets/img/no_img.jpg'))
                                                            <div class="col-md-3 mb-2">
                                                                <p class="mb-1">GST Cert</p>
                                                                <a href="{{ $seller->gst_certificate_image }}" target="_blank">
                                                                    <img src="{{ $seller->gst_certificate_image }}" class="img-thumbnail" style="height: 80px;">
                                                                </a>
                                                            </div>
                                                        @endif
                                                         @if($seller->kyc_document && $seller->kyc_document != asset('assets/img/no_img.jpg'))
                                                            <div class="col-md-3 mb-2">
                                                                <p class="mb-1">Other KYC</p>
                                                                <a href="{{ $seller->kyc_document }}" target="_blank" class="btn btn-sm btn-info">View Document</a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <h5 class="mb-3 mt-4 text-primary">Bank Details</h5>
                                <div class="table-responsive">
                                    <table class="table mb-0 table-bordered">
                                        <tbody>
                                            <tr>
                                                <th scope="row" style="width: 200px;">Account Holder</th>
                                                <td>{{ $seller->account_holder_name }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Bank Name</th>
                                                <td>{{ $seller->bank_name }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Account Number</th>
                                                <td>{{ $seller->bank_account_number }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">IFSC Code</th>
                                                <td>{{ $seller->ifsc_code }}</td>
                                            </tr>
                                            @if($seller->cancel_check_image && $seller->cancel_check_image != asset('assets/img/no_img.jpg'))
                                                <tr>
                                                    <th scope="row">Cancelled Cheque</th>
                                                    <td>
                                                        <a href="{{ $seller->cancel_check_image }}" target="_blank">
                                                            <img src="{{ $seller->cancel_check_image }}" class="img-thumbnail" style="height: 80px;">
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
