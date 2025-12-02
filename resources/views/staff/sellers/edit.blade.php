@extends('staff.master')
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
                            <div class="col-md-6 mb-3">
                                <label for="business_name" class="form-label">Business Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="business_name" name="business_name" value="{{ $seller->business_name }}" required>
                                <label id="business_name-error" class="text-danger error" for="business_name" style="display: none"></label>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="owner_name" class="form-label">Owner Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="owner_name" name="owner_name" value="{{ $seller->owner_name }}" required>
                                <label id="owner_name-error" class="text-danger error" for="owner_name" style="display: none"></label>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ $seller->email }}" required>
                                <label id="email-error" class="text-danger error" for="email" style="display: none"></label>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="phone" name="phone" value="{{ $seller->phone }}" required>
                                <label id="phone-error" class="text-danger error" for="phone" style="display: none"></label>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password (Leave blank to keep current)</label>
                                <input type="password" class="form-control" id="password" name="password">
                                <label id="password-error" class="text-danger error" for="password" style="display: none"></label>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                <label id="password_confirmation-error" class="text-danger error" for="password_confirmation" style="display: none"></label>
                            </div>
                        </div>

                        <hr>

                        <!-- Business Details -->
                        <h5 class="mb-3 text-primary">Business Details</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="business_logo" class="form-label">Business Logo</label>
                                <input type="file" class="form-control" id="business_logo" name="business_logo" accept="image/*">
                                @if($seller->business_logo)
                                    <div class="mt-2">
                                        <a href="{{ $seller->business_logo }}" target="_blank">View Current Logo</a>
                                    </div>
                                @endif
                                <label id="business_logo-error" class="text-danger error" for="business_logo" style="display: none"></label>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="business_type" class="form-label">Business Type</label>
                                <select class="form-select" id="business_type" name="business_type">
                                    <option value="">Select Type</option>
                                    <option value="wholesale" {{ $seller->business_type == 'wholesale' ? 'selected' : '' }}>Wholesale</option>
                                    <option value="retail" {{ $seller->business_type == 'retail' ? 'selected' : '' }}>Retail</option>
                                    <option value="service_provider" {{ $seller->business_type == 'service_provider' ? 'selected' : '' }}>Service Provider</option>
                                    <option value="manufacturer" {{ $seller->business_type == 'manufacturer' ? 'selected' : '' }}>Manufacturer</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="category_id" class="form-label">Category</label>
                                <select class="form-select" id="category_id" name="category_id">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $seller->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="sub_category_id" class="form-label">Sub Category</label>
                                <select class="form-select" id="sub_category_id" name="sub_category_id">
                                    <option value="">Select Sub Category</option>
                                    <!-- Populate via AJAX based on Category -->
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" value="{{ $seller->username }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="bar_code" class="form-label">Bar Code</label>
                                <input type="text" class="form-control" id="bar_code" name="bar_code" value="{{ $seller->bar_code }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="store_link" class="form-label">Store Link</label>
                                <input type="text" class="form-control" id="store_link" name="store_link" value="{{ $seller->store_link }}">
                            </div>
                        </div>

                        <hr>

                        <!-- Personal & KYC -->
                        <h5 class="mb-3 text-primary">Personal & KYC</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="date_of_birth" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ $seller->date_of_birth ? $seller->date_of_birth->format('Y-m-d') : '' }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select" id="gender" name="gender">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ $seller->gender == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ $seller->gender == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ $seller->gender == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="aadhar_number" class="form-label">Aadhaar Number</label>
                                <input type="text" class="form-control" id="aadhar_number" name="aadhar_number" value="{{ $seller->aadhar_number }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="aadhaar_front" class="form-label">Aadhaar Front Image</label>
                                <input type="file" class="form-control" id="aadhaar_front" name="aadhaar_front" accept="image/*">
                                @if($seller->aadhaar_front)
                                    <div class="mt-2">
                                        <a href="{{ $seller->aadhaar_front }}" target="_blank">View Current Image</a>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="aadhaar_back" class="form-label">Aadhaar Back Image</label>
                                <input type="file" class="form-control" id="aadhaar_back" name="aadhaar_back" accept="image/*">
                                @if($seller->aadhaar_back)
                                    <div class="mt-2">
                                        <a href="{{ $seller->aadhaar_back }}" target="_blank">View Current Image</a>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="pancard_number" class="form-label">PAN Card Number</label>
                                <input type="text" class="form-control" id="pancard_number" name="pancard_number" value="{{ $seller->pancard_number }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="pancard_image" class="form-label">PAN Card Image</label>
                                <input type="file" class="form-control" id="pancard_image" name="pancard_image" accept="image/*">
                                @if($seller->pancard_image)
                                    <div class="mt-2">
                                        <a href="{{ $seller->pancard_image }}" target="_blank">View Current Image</a>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="gst_number" class="form-label">GST Number</label>
                                <input type="text" class="form-control" id="gst_number" name="gst_number" value="{{ $seller->gst_number }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="gst_certificate_image" class="form-label">GST Certificate Image</label>
                                <input type="file" class="form-control" id="gst_certificate_image" name="gst_certificate_image" accept="image/*">
                                @if($seller->gst_certificate_image)
                                    <div class="mt-2">
                                        <a href="{{ $seller->gst_certificate_image }}" target="_blank">View Current Image</a>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="kyc_document" class="form-label">Other KYC Document</label>
                                <input type="file" class="form-control" id="kyc_document" name="kyc_document">
                                @if($seller->kyc_document)
                                    <div class="mt-2">
                                        <a href="{{ $seller->kyc_document }}" target="_blank">View Current Document</a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <hr>

                        <!-- Bank Details -->
                        <h5 class="mb-3 text-primary">Bank Details</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="account_holder_name" class="form-label">Account Holder Name</label>
                                <input type="text" class="form-control" id="account_holder_name" name="account_holder_name" value="{{ $seller->account_holder_name }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="bank_account_number" class="form-label">Account Number</label>
                                <input type="text" class="form-control" id="bank_account_number" name="bank_account_number" value="{{ $seller->bank_account_number }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="ifsc_code" class="form-label">IFSC Code</label>
                                <input type="text" class="form-control" id="ifsc_code" name="ifsc_code" value="{{ $seller->ifsc_code }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="cancel_check_image" class="form-label">Cancelled Cheque Image</label>
                                <input type="file" class="form-control" id="cancel_check_image" name="cancel_check_image" accept="image/*">
                                @if($seller->cancel_check_image)
                                    <div class="mt-2">
                                        <a href="{{ $seller->cancel_check_image }}" target="_blank">View Current Image</a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <hr>

                        <!-- Address -->
                        <h5 class="mb-3 text-primary">Address</h5>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="2">{{ $seller->address }}</textarea>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control" id="city" name="city" value="{{ $seller->city }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="state" class="form-label">State</label>
                                <input type="text" class="form-control" id="state" name="state" value="{{ $seller->state }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="country" class="form-label">Country</label>
                                <input type="text" class="form-control" id="country" name="country" value="{{ $seller->country }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="pincode" class="form-label">Pincode</label>
                                <input type="text" class="form-control" id="pincode" name="pincode" value="{{ $seller->pincode }}">
                            </div>
                        </div>

                        <hr>

                        <!-- Social Media -->
                        <h5 class="mb-3 text-primary">Social Media</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="whatsapp" class="form-label">WhatsApp</label>
                                <input type="text" class="form-control" id="whatsapp" name="social_links[whatsapp]" value="{{ $seller->social_links['whatsapp'] ?? '' }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="website" class="form-label">Website</label>
                                <input type="text" class="form-control" id="website" name="social_links[website]" value="{{ $seller->social_links['website'] ?? '' }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="facebook" class="form-label">Facebook</label>
                                <input type="text" class="form-control" id="facebook" name="social_links[facebook]" value="{{ $seller->social_links['facebook'] ?? '' }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="instagram" class="form-label">Instagram</label>
                                <input type="text" class="form-control" id="instagram" name="social_links[instagram]" value="{{ $seller->social_links['instagram'] ?? '' }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="linkedin" class="form-label">LinkedIn</label>
                                <input type="text" class="form-control" id="linkedin" name="social_links[linkedin]" value="{{ $seller->social_links['linkedin'] ?? '' }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="youtube" class="form-label">YouTube</label>
                                <input type="text" class="form-control" id="youtube" name="social_links[youtube]" value="{{ $seller->social_links['youtube'] ?? '' }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="telegram" class="form-label">Telegram</label>
                                <input type="text" class="form-control" id="telegram" name="social_links[telegram]" value="{{ $seller->social_links['telegram'] ?? '' }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="twitter" class="form-label">Twitter</label>
                                <input type="text" class="form-control" id="twitter" name="social_links[twitter]" value="{{ $seller->social_links['twitter'] ?? '' }}">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" id="sellerEditButton">
                            <i class="bx bx-loader spinner me-2" style="display: none" id="sellerEditBtnSpinner"></i>Update Seller
                        </button>
                        <a href="{{ route('staff.sellers.index') }}" class="btn btn-secondary">Cancel</a>
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
                    url: "{{ route('staff.sellers.update', $seller->id) }}",
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
                                window.location.href = "{{ route('staff.sellers.index') }}";
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
