@extends('admin.master')
@section('title','Create Seller')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Create Seller</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form id="sellerCreateForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Basic Info -->
                        <h5 class="mb-3 text-primary">Basic Information</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="business_name" class="form-label">Business Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="business_name" name="business_name" required>
                                <label id="business_name-error" class="text-danger error" for="business_name" style="display: none"></label>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="owner_name" class="form-label">Owner Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="owner_name" name="owner_name" required>
                                <label id="owner_name-error" class="text-danger error" for="owner_name" style="display: none"></label>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" required>
                                <label id="email-error" class="text-danger error" for="email" style="display: none"></label>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="phone" name="phone" required>
                                <label id="phone-error" class="text-danger error" for="phone" style="display: none"></label>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <label id="password-error" class="text-danger error" for="password" style="display: none"></label>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
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
                                <label id="business_logo-error" class="text-danger error" for="business_logo" style="display: none"></label>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="business_type" class="form-label">Business Type</label>
                                <select class="form-select" id="business_type" name="business_type">
                                    <option value="">Select Type</option>
                                    <option value="wholesale">Wholesale</option>
                                    <option value="retail">Retail</option>
                                    <option value="service_provider">Service Provider</option>
                                    <option value="manufacturer">Manufacturer</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="category_id" class="form-label">Category</label>
                                <select class="form-select" id="category_id" name="category_id">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
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
                                <input type="text" class="form-control" id="username" name="username">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="bar_code" class="form-label">Bar Code</label>
                                <input type="text" class="form-control" id="bar_code" name="bar_code">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="store_link" class="form-label">Store Link</label>
                                <input type="text" class="form-control" id="store_link" name="store_link">
                            </div>
                        </div>

                        <hr>

                        <!-- Personal & KYC -->
                        <h5 class="mb-3 text-primary">Personal & KYC</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="date_of_birth" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select" id="gender" name="gender">
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="aadhar_number" class="form-label">Aadhaar Number</label>
                                <input type="text" class="form-control" id="aadhar_number" name="aadhar_number">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="aadhaar_front" class="form-label">Aadhaar Front Image</label>
                                <input type="file" class="form-control" id="aadhaar_front" name="aadhaar_front" accept="image/*">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="aadhaar_back" class="form-label">Aadhaar Back Image</label>
                                <input type="file" class="form-control" id="aadhaar_back" name="aadhaar_back" accept="image/*">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="pancard_number" class="form-label">PAN Card Number</label>
                                <input type="text" class="form-control" id="pancard_number" name="pancard_number">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="pancard_image" class="form-label">PAN Card Image</label>
                                <input type="file" class="form-control" id="pancard_image" name="pancard_image" accept="image/*">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="gst_number" class="form-label">GST Number</label>
                                <input type="text" class="form-control" id="gst_number" name="gst_number">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="gst_certificate_image" class="form-label">GST Certificate Image</label>
                                <input type="file" class="form-control" id="gst_certificate_image" name="gst_certificate_image" accept="image/*">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="kyc_document" class="form-label">Other KYC Document</label>
                                <input type="file" class="form-control" id="kyc_document" name="kyc_document">
                            </div>
                        </div>

                        <hr>

                        <!-- Bank Details -->
                        <h5 class="mb-3 text-primary">Bank Details</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="account_holder_name" class="form-label">Account Holder Name</label>
                                <input type="text" class="form-control" id="account_holder_name" name="account_holder_name">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="bank_account_number" class="form-label">Account Number</label>
                                <input type="text" class="form-control" id="bank_account_number" name="bank_account_number">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="ifsc_code" class="form-label">IFSC Code</label>
                                <input type="text" class="form-control" id="ifsc_code" name="ifsc_code">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="cancel_check_image" class="form-label">Cancelled Cheque Image</label>
                                <input type="file" class="form-control" id="cancel_check_image" name="cancel_check_image" accept="image/*">
                            </div>
                        </div>

                        <hr>

                        <!-- Address -->
                        <h5 class="mb-3 text-primary">Address</h5>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="2"></textarea>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control" id="city" name="city">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="state" class="form-label">State</label>
                                <input type="text" class="form-control" id="state" name="state">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="country" class="form-label">Country</label>
                                <input type="text" class="form-control" id="country" name="country">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="pincode" class="form-label">Pincode</label>
                                <input type="text" class="form-control" id="pincode" name="pincode">
                            </div>
                        </div>

                        <hr>

                        <!-- Social Media -->
                        <h5 class="mb-3 text-primary">Social Media</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="whatsapp" class="form-label">WhatsApp</label>
                                <input type="text" class="form-control" id="whatsapp" name="social_links[whatsapp]">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="website" class="form-label">Website</label>
                                <input type="text" class="form-control" id="website" name="social_links[website]">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="facebook" class="form-label">Facebook</label>
                                <input type="text" class="form-control" id="facebook" name="social_links[facebook]">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="instagram" class="form-label">Instagram</label>
                                <input type="text" class="form-control" id="instagram" name="social_links[instagram]">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="linkedin" class="form-label">LinkedIn</label>
                                <input type="text" class="form-control" id="linkedin" name="social_links[linkedin]">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="youtube" class="form-label">YouTube</label>
                                <input type="text" class="form-control" id="youtube" name="social_links[youtube]">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="telegram" class="form-label">Telegram</label>
                                <input type="text" class="form-control" id="telegram" name="social_links[telegram]">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="twitter" class="form-label">Twitter</label>
                                <input type="text" class="form-control" id="twitter" name="social_links[twitter]">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" id="sellerCreateButton">
                            <i class="bx bx-loader spinner me-2" style="display: none" id="sellerCreateBtnSpinner"></i>Create Seller
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
        $("#sellerCreateForm").validate({
            rules: {
                business_name: { required: true },
                owner_name: { required: true },
                email: { required: true, email: true },
                phone: { required: true },
                password: { required: true, minlength: 8 },
                password_confirmation: { required: true, equalTo: "#password" }
            },
            messages: {
                business_name: { required: "The business name field is required" },
                owner_name: { required: "The owner name field is required" },
                email: { required: "The email field is required", email: "Please enter a valid email address" },
                phone: { required: "The phone field is required" },
                password: { required: "The password field is required", minlength: "Password must be at least 8 characters long" },
                password_confirmation: { required: "The confirm password field is required", equalTo: "Passwords do not match" }
            },
            errorPlacement: function (error, element) {
                element.after(error);
            },
            errorClass: "text-danger",
            submitHandler: function (form, e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('admin.sellers.store') }}",
                    method: "post",
                    dataType: "json",
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: function () {
                        $('#sellerCreateButton').attr('disabled', true);
                        $("#sellerCreateBtnSpinner").show();
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
                        $('#sellerCreateButton').attr('disabled', false);
                        $("#sellerCreateBtnSpinner").hide();
                    },
                });
            }
        });
    });
</script>
@endsection
