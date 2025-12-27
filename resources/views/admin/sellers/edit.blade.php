@extends('admin.master')
@section('title','Edit Seller')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Edit Seller</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form id="sellerEditForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Basic Info -->
                        <h5 class="mb-3 text-primary">Basic Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <x-input-field 
                                    name="business_name" 
                                    id="business_name" 
                                    label="Business Name" 
                                    value="{{ $seller->business_name }}" 
                                    required 
                                />
                            </div>
                            <div class="col-md-6">
                                <x-input-field 
                                    name="owner_name" 
                                    id="owner_name" 
                                    label="Owner Name" 
                                    value="{{ $seller->owner_name }}" 
                                    required 
                                />
                            </div>
                            <div class="col-md-6">
                                <x-input-field 
                                    type="email" 
                                    name="email" 
                                    id="email" 
                                    label="Email" 
                                    value="{{ $seller->email }}" 
                                    required 
                                />
                            </div>
                            <div class="col-md-6">
                                <x-input-field 
                                    name="phone" 
                                    id="phone" 
                                    label="Phone" 
                                    value="{{ $seller->phone }}" 
                                    required 
                                />
                            </div>
                            <div class="col-md-6">
                                <x-input-field 
                                    type="password" 
                                    name="password" 
                                    id="password" 
                                    label="Password (Leave blank to keep current)" 
                                />
                            </div>
                            <div class="col-md-6">
                                <x-input-field 
                                    type="password" 
                                    name="password_confirmation" 
                                    id="password_confirmation" 
                                    label="Confirm Password" 
                                />
                            </div>
                        </div>

                        <hr>

                        <!-- Business Details -->
                        <h5 class="mb-3 text-primary">Business Details</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <x-input-field 
                                    type="file" 
                                    name="business_logo" 
                                    id="business_logo" 
                                    label="Business Logo" 
                                    accept="image/*" 
                                />
                                @if($seller->business_logo)
                                    <div class="mt-2">
                                        <a href="{{ $seller->business_logo }}" target="_blank" class="btn btn-sm btn-info">View Current Logo</a>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <x-input-field 
                                    type="select" 
                                    name="business_type" 
                                    id="business_type" 
                                    label="Business Type"
                                >
                                    <option value="">Select Type</option>
                                    <option value="wholesale" {{ $seller->business_type == 'wholesale' ? 'selected' : '' }}>Wholesale</option>
                                    <option value="retail" {{ $seller->business_type == 'retail' ? 'selected' : '' }}>Retail</option>
                                    <option value="service_provider" {{ $seller->business_type == 'service_provider' ? 'selected' : '' }}>Service Provider</option>
                                    <option value="manufacturer" {{ $seller->business_type == 'manufacturer' ? 'selected' : '' }}>Manufacturer</option>
                                </x-input-field>
                            </div>
                            <div class="col-md-6">
                                <x-input-field 
                                    type="select" 
                                    name="category_id" 
                                    id="category_id" 
                                    label="Category"
                                >
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $seller->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </x-input-field>
                            </div>
                            <div class="col-md-6">
                                <x-input-field 
                                    type="select" 
                                    name="sub_category_id" 
                                    id="sub_category_id" 
                                    label="Sub Category"
                                >
                                    <option value="">Select Sub Category</option>
                                </x-input-field>
                            </div>
                            <div class="col-md-6">
                                <x-input-field 
                                    name="username" 
                                    id="username" 
                                    label="Username" 
                                    value="{{ $seller->username }}" 
                                />
                            </div>
                            <div class="col-md-6">
                                <x-input-field 
                                    name="bar_code" 
                                    id="bar_code" 
                                    label="Bar Code" 
                                    value="{{ $seller->bar_code }}" 
                                />
                            </div>
                            <div class="col-md-6">
                                <x-input-field 
                                    name="store_link" 
                                    id="store_link" 
                                    label="Store Link" 
                                    value="{{ $seller->store_link }}" 
                                />
                            </div>
                        </div>

                        <hr>

                        <!-- Personal & KYC -->
                        <h5 class="mb-3 text-primary">Personal & KYC</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <x-input-field 
                                    type="date" 
                                    name="date_of_birth" 
                                    id="date_of_birth" 
                                    label="Date of Birth" 
                                    value="{{ $seller->date_of_birth ? $seller->date_of_birth->format('Y-m-d') : '' }}" 
                                />
                            </div>
                            <div class="col-md-4">
                                <x-input-field 
                                    type="select" 
                                    name="gender" 
                                    id="gender" 
                                    label="Gender"
                                >
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ $seller->gender == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ $seller->gender == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ $seller->gender == 'other' ? 'selected' : '' }}>Other</option>
                                </x-input-field>
                            </div>
                            <div class="col-md-4">
                                <x-input-field 
                                    name="aadhar_number" 
                                    id="aadhar_number" 
                                    label="Aadhaar Number" 
                                    value="{{ $seller->aadhar_number }}" 
                                />
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-input-field 
                                    type="file" 
                                    name="aadhaar_front" 
                                    id="aadhaar_front" 
                                    label="Aadhaar Front Image" 
                                    accept="image/*" 
                                />
                                @if($seller->aadhaar_front)
                                    <div class="mt-2">
                                        <a href="{{ $seller->aadhaar_front }}" target="_blank" class="btn btn-sm btn-info">View Current Image</a>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-input-field 
                                    type="file" 
                                    name="aadhaar_back" 
                                    id="aadhaar_back" 
                                    label="Aadhaar Back Image" 
                                    accept="image/*" 
                                />
                                @if($seller->aadhaar_back)
                                    <div class="mt-2">
                                        <a href="{{ $seller->aadhaar_back }}" target="_blank" class="btn btn-sm btn-info">View Current Image</a>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <x-input-field 
                                    name="pancard_number" 
                                    id="pancard_number" 
                                    label="PAN Card Number" 
                                    value="{{ $seller->pancard_number }}" 
                                />
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-input-field 
                                    type="file" 
                                    name="pancard_image" 
                                    id="pancard_image" 
                                    label="PAN Card Image" 
                                    accept="image/*" 
                                />
                                @if($seller->pancard_image)
                                    <div class="mt-2">
                                        <a href="{{ $seller->pancard_image }}" target="_blank" class="btn btn-sm btn-info">View Current Image</a>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <x-input-field 
                                    name="gst_number" 
                                    id="gst_number" 
                                    label="GST Number" 
                                    value="{{ $seller->gst_number }}" 
                                />
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-input-field 
                                    type="file" 
                                    name="gst_certificate_image" 
                                    id="gst_certificate_image" 
                                    label="GST Certificate Image" 
                                    accept="image/*" 
                                />
                                @if($seller->gst_certificate_image)
                                    <div class="mt-2">
                                        <a href="{{ $seller->gst_certificate_image }}" target="_blank" class="btn btn-sm btn-info">View Current Image</a>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-input-field 
                                    type="file" 
                                    name="kyc_document" 
                                    id="kyc_document" 
                                    label="Other KYC Document" 
                                />
                                @if($seller->kyc_document)
                                    <div class="mt-2">
                                        <a href="{{ $seller->kyc_document }}" target="_blank" class="btn btn-sm btn-info">View Current Document</a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <hr>

                        <!-- Bank Details -->
                        <h5 class="mb-3 text-primary">Bank Details</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <x-input-field 
                                    name="account_holder_name" 
                                    id="account_holder_name" 
                                    label="Account Holder Name" 
                                    value="{{ $seller->account_holder_name }}" 
                                />
                            </div>
                            <div class="col-md-6">
                                <x-input-field 
                                    name="bank_account_number" 
                                    id="bank_account_number" 
                                    label="Account Number" 
                                    value="{{ $seller->bank_account_number }}" 
                                />
                            </div>
                            <div class="col-md-6">
                                <x-input-field 
                                    name="ifsc_code" 
                                    id="ifsc_code" 
                                    label="IFSC Code" 
                                    value="{{ $seller->ifsc_code }}" 
                                />
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-input-field 
                                    type="file" 
                                    name="cancel_check_image" 
                                    id="cancel_check_image" 
                                    label="Cancelled Cheque Image" 
                                    accept="image/*" 
                                />
                                @if($seller->cancel_check_image)
                                    <div class="mt-2">
                                        <a href="{{ $seller->cancel_check_image }}" target="_blank" class="btn btn-sm btn-info">View Current Image</a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <hr>

                        <!-- Address -->
                        <h5 class="mb-3 text-primary">Address</h5>
                        <div class="row">
                            <div class="col-md-12">
                                <x-input-field 
                                    type="textarea" 
                                    name="address" 
                                    id="address" 
                                    label="Address" 
                                    rows="2"
                                    value="{{ $seller->address }}"
                                />
                            </div>
                            <div class="col-md-3">
                                <x-input-field 
                                    name="city" 
                                    id="city" 
                                    label="City" 
                                    value="{{ $seller->city }}" 
                                />
                            </div>
                            <div class="col-md-3">
                                <x-input-field 
                                    name="state" 
                                    id="state" 
                                    label="State" 
                                    value="{{ $seller->state }}" 
                                />
                            </div>
                            <div class="col-md-3">
                                <x-input-field 
                                    name="country" 
                                    id="country" 
                                    label="Country" 
                                    value="{{ $seller->country }}" 
                                />
                            </div>
                            <div class="col-md-3">
                                <x-input-field 
                                    name="pincode" 
                                    id="pincode" 
                                    label="Pincode" 
                                    value="{{ $seller->pincode }}" 
                                />
                            </div>
                        </div>

                        <hr>

                        <!-- Social Media -->
                        <h5 class="mb-3 text-primary">Social Media</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <x-input-field 
                                    name="social_links[whatsapp]" 
                                    id="whatsapp" 
                                    label="WhatsApp" 
                                    value="{{ $seller->social_links['whatsapp'] ?? '' }}" 
                                    icon="ri-whatsapp-line"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-input-field 
                                    name="social_links[website]" 
                                    id="website" 
                                    label="Website" 
                                    value="{{ $seller->social_links['website'] ?? '' }}" 
                                    icon="ri-global-line"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-input-field 
                                    name="social_links[facebook]" 
                                    id="facebook" 
                                    label="Facebook" 
                                    value="{{ $seller->social_links['facebook'] ?? '' }}" 
                                    icon="ri-facebook-box-line"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-input-field 
                                    name="social_links[instagram]" 
                                    id="instagram" 
                                    label="Instagram" 
                                    value="{{ $seller->social_links['instagram'] ?? '' }}" 
                                    icon="ri-instagram-line"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-input-field 
                                    name="social_links[linkedin]" 
                                    id="linkedin" 
                                    label="LinkedIn" 
                                    value="{{ $seller->social_links['linkedin'] ?? '' }}" 
                                    icon="ri-linkedin-box-line"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-input-field 
                                    name="social_links[youtube]" 
                                    id="youtube" 
                                    label="YouTube" 
                                    value="{{ $seller->social_links['youtube'] ?? '' }}" 
                                    icon="ri-youtube-line"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-input-field 
                                    name="social_links[telegram]" 
                                    id="telegram" 
                                    label="Telegram" 
                                    value="{{ $seller->social_links['telegram'] ?? '' }}" 
                                    icon="ri-telegram-line"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-input-field 
                                    name="social_links[twitter]" 
                                    id="twitter" 
                                    label="Twitter" 
                                    value="{{ $seller->social_links['twitter'] ?? '' }}" 
                                    icon="ri-twitter-line"
                                />
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" id="sellerEditButton">
                            <i class="bx bx-loader spinner me-2" style="display: none" id="sellerEditBtnSpinner"></i>Update Seller
                        </button>
                        <a href="{{ route('admin.sellers.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $("#sellerEditForm").validate({
            rules: {
                business_name: { required: true },
                owner_name: { required: true },
                email: { required: true, email: true },
                phone: { required: true },
                password: { minlength: 8 },
                password_confirmation: { equalTo: "#password" }
            },
            messages: {
                business_name: { required: "The business name field is required" },
                owner_name: { required: "The owner name field is required" },
                email: { required: "The email field is required", email: "Please enter a valid email address" },
                phone: { required: "The phone field is required" },
                password: { minlength: "Password must be at least 8 characters long" },
                password_confirmation: { equalTo: "Passwords do not match" }
            },
            errorPlacement: function (error, element) {
                element.after(error);
            },
            errorClass: "text-danger",
            submitHandler: function (form, e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('admin.sellers.update', $seller->id) }}",
                    method: "post",
                    dataType: "json",
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: function () {
                        $('#sellerEditButton').attr('disabled', true);
                        $("#sellerEditBtnSpinner").show();
                    },
                    success: function (result) {
                        if(result.status){
                             sendSuccess(result.message);
                             setTimeout(function() {
                                window.location.href = "{{ route('admin.sellers.index') }}";
                             }, 1000);
                        }else{
                             sendError(result.message);
                        }
                    },
                    error: function (xhr) {
                        let data = xhr.responseJSON;
                        if (data.hasOwnProperty('error')) {
                             // Display errors for specific fields if needed
                             $.each(data.error, function(key, value){
                                 $("#"+key+"-error").html(value).show();
                             });
                        } else if (data.hasOwnProperty('message')) {
                            actionError(xhr, data.message)
                        } else {
                            actionError(xhr);
                        }
                    },
                    complete: function () {
                        $('#sellerEditButton').attr('disabled', false);
                        $("#sellerEditBtnSpinner").hide();
                    },
                });
            }
        });
    });
</script>
@endsection
