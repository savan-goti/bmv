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
                            <div class="col-md-6">
                                <x-input-field 
                                    name="business_name" 
                                    id="business_name" 
                                    label="Business Name" 
                                    placeholder="Enter business name" 
                                    required 
                                />
                            </div>
                            <div class="col-md-6">
                                <x-input-field 
                                    name="owner_name" 
                                    id="owner_name" 
                                    label="Owner Name" 
                                    placeholder="Enter owner name" 
                                    required 
                                />
                            </div>
                            <div class="col-md-6">
                                <x-input-field 
                                    type="email" 
                                    name="email" 
                                    id="email" 
                                    label="Email" 
                                    placeholder="Enter email" 
                                    required 
                                />
                            </div>
                            <div class="col-md-6">
                                <x-input-field 
                                    name="phone" 
                                    id="phone" 
                                    label="Phone" 
                                    placeholder="Enter phone" 
                                    required 
                                />
                            </div>
                            <div class="col-md-6">
                                <x-input-field 
                                    type="password" 
                                    name="password" 
                                    id="password" 
                                    label="Password" 
                                    placeholder="Enter password" 
                                    required 
                                />
                            </div>
                            <div class="col-md-6">
                                <x-input-field 
                                    type="password" 
                                    name="password_confirmation" 
                                    id="password_confirmation" 
                                    label="Confirm Password" 
                                    placeholder="Confirm password" 
                                    required 
                                />
                            </div>
                        </div>

                        <hr>

                        <!-- Business Details -->
                        <h5 class="mb-3 text-primary">Business Details</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <x-input-field 
                                    type="file" 
                                    name="business_logo" 
                                    id="business_logo" 
                                    label="Business Logo" 
                                    accept="image/*" 
                                />
                            </div>
                            <div class="col-md-6">
                                <x-input-field 
                                    type="select" 
                                    name="business_type" 
                                    id="business_type" 
                                    label="Business Type"
                                >
                                    <option value="">Select Type</option>
                                    <option value="wholesale">Wholesale</option>
                                    <option value="retail">Retail</option>
                                    <option value="service_provider">Service Provider</option>
                                    <option value="manufacturer">Manufacturer</option>
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
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
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
                                    placeholder="Enter username" 
                                />
                            </div>
                            <div class="col-md-6">
                                <x-input-field 
                                    name="bar_code" 
                                    id="bar_code" 
                                    label="Bar Code" 
                                    placeholder="Enter bar code" 
                                />
                            </div>
                            <div class="col-md-6">
                                <x-input-field 
                                    name="store_link" 
                                    id="store_link" 
                                    label="Store Link" 
                                    placeholder="Enter store link" 
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
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </x-input-field>
                            </div>
                            <div class="col-md-4">
                                <x-input-field 
                                    name="aadhar_number" 
                                    id="aadhar_number" 
                                    label="Aadhaar Number" 
                                    placeholder="Enter aadhaar number" 
                                />
                            </div>
                            <div class="col-md-6">
                                <x-input-field 
                                    type="file" 
                                    name="aadhaar_front" 
                                    id="aadhaar_front" 
                                    label="Aadhaar Front Image" 
                                    accept="image/*" 
                                />
                            </div>
                            <div class="col-md-6">
                                <x-input-field 
                                    type="file" 
                                    name="aadhaar_back" 
                                    id="aadhaar_back" 
                                    label="Aadhaar Back Image" 
                                    accept="image/*" 
                                />
                            </div>
                            <div class="col-md-6">
                                <x-input-field 
                                    name="pancard_number" 
                                    id="pancard_number" 
                                    label="PAN Card Number" 
                                    placeholder="Enter PAN number" 
                                />
                            </div>
                            <div class="col-md-6">
                                <x-input-field 
                                    type="file" 
                                    name="pancard_image" 
                                    id="pancard_image" 
                                    label="PAN Card Image" 
                                    accept="image/*" 
                                />
                            </div>
                            <div class="col-md-6">
                                <x-input-field 
                                    name="gst_number" 
                                    id="gst_number" 
                                    label="GST Number" 
                                    placeholder="Enter GST number" 
                                />
                            </div>
                            <div class="col-md-6">
                                <x-input-field 
                                    type="file" 
                                    name="gst_certificate_image" 
                                    id="gst_certificate_image" 
                                    label="GST Certificate Image" 
                                    accept="image/*" 
                                />
                            </div>
                            <div class="col-md-6">
                                <x-input-field 
                                    type="file" 
                                    name="kyc_document" 
                                    id="kyc_document" 
                                    label="Other KYC Document" 
                                />
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
                                    placeholder="Enter account holder name" 
                                />
                            </div>
                            <div class="col-md-6">
                                <x-input-field 
                                    name="bank_account_number" 
                                    id="bank_account_number" 
                                    label="Account Number" 
                                    placeholder="Enter account number" 
                                />
                            </div>
                            <div class="col-md-6">
                                <x-input-field 
                                    name="ifsc_code" 
                                    id="ifsc_code" 
                                    label="IFSC Code" 
                                    placeholder="Enter IFSC code" 
                                />
                            </div>
                            <div class="col-md-6">
                                <x-input-field 
                                    type="file" 
                                    name="cancel_check_image" 
                                    id="cancel_check_image" 
                                    label="Cancelled Cheque Image" 
                                    accept="image/*" 
                                />
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
                                    placeholder="Enter address" 
                                    rows="2" 
                                />
                            </div>
                            <div class="col-md-3">
                                <x-input-field 
                                    name="city" 
                                    id="city" 
                                    label="City" 
                                    placeholder="Enter city" 
                                />
                            </div>
                            <div class="col-md-3">
                                <x-input-field 
                                    name="state" 
                                    id="state" 
                                    label="State" 
                                    placeholder="Enter state" 
                                />
                            </div>
                            <div class="col-md-3">
                                <x-input-field 
                                    name="country" 
                                    id="country" 
                                    label="Country" 
                                    placeholder="Enter country" 
                                />
                            </div>
                            <div class="col-md-3">
                                <x-input-field 
                                    name="pincode" 
                                    id="pincode" 
                                    label="Pincode" 
                                    placeholder="Enter pincode" 
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
                                    placeholder="Enter WhatsApp" 
                                    icon="ri-whatsapp-line"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-input-field 
                                    name="social_links[website]" 
                                    id="website" 
                                    label="Website" 
                                    placeholder="Enter website" 
                                    icon="ri-global-line"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-input-field 
                                    name="social_links[facebook]" 
                                    id="facebook" 
                                    label="Facebook" 
                                    placeholder="Enter Facebook" 
                                    icon="ri-facebook-box-line"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-input-field 
                                    name="social_links[instagram]" 
                                    id="instagram" 
                                    label="Instagram" 
                                    placeholder="Enter Instagram" 
                                    icon="ri-instagram-line"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-input-field 
                                    name="social_links[linkedin]" 
                                    id="linkedin" 
                                    label="LinkedIn" 
                                    placeholder="Enter LinkedIn" 
                                    icon="ri-linkedin-box-line"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-input-field 
                                    name="social_links[youtube]" 
                                    id="youtube" 
                                    label="YouTube" 
                                    placeholder="Enter YouTube" 
                                    icon="ri-youtube-line"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-input-field 
                                    name="social_links[telegram]" 
                                    id="telegram" 
                                    label="Telegram" 
                                    placeholder="Enter Telegram" 
                                    icon="ri-telegram-line"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-input-field 
                                    name="social_links[twitter]" 
                                    id="twitter" 
                                    label="Twitter" 
                                    placeholder="Enter Twitter" 
                                    icon="ri-twitter-line"
                                />
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
