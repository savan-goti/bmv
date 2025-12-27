@extends('owner.master')
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
                            <div class="col-md-6"><x-input-field name="business_name" label="Business Name" placeholder="Enter business name" required /></div>
                            <div class="col-md-6"><x-input-field name="owner_name" label="Owner Name" placeholder="Enter owner name" required /></div>
                            <div class="col-md-6"><x-input-field type="email" name="email" label="Email" placeholder="Enter email" required /></div>
                            <div class="col-md-6"><x-input-field name="phone" label="Phone" placeholder="Enter phone" required /></div>
                            <div class="col-md-6"><x-input-field type="password" name="password" label="Password" placeholder="Enter password" required /></div>
                            <div class="col-md-6"><x-input-field type="password" name="password_confirmation" label="Confirm Password" placeholder="Confirm password" required /></div>
                        </div>

                        <hr>

                        <!-- Business Details -->
                        <h5 class="mb-3 text-primary">Business Details</h5>
                        <div class="row">
                            <div class="col-md-6"><x-input-field type="file" name="business_logo" label="Business Logo" accept="image/*" /></div>
                            <div class="col-md-6"><x-input-field type="select" name="business_type" label="Business Type" placeholder="Select Type">
                                <option value="wholesale">Wholesale</option>
                                <option value="retail">Retail</option>
                                <option value="service_provider">Service Provider</option>
                                <option value="manufacturer">Manufacturer</option>
                            </x-input-field></div>
                            <div class="col-md-6"><x-input-field type="select" name="category_id" label="Category" placeholder="Select Category">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </x-input-field></div>
                            <div class="col-md-6"><x-input-field type="select" name="sub_category_id" label="Sub Category" placeholder="Select Sub Category"></x-input-field></div>
                            <div class="col-md-6"><x-input-field name="username" label="Username" placeholder="Enter username" /></div>
                            <div class="col-md-6"><x-input-field name="bar_code" label="Bar Code" placeholder="Enter bar code" /></div>
                            <div class="col-md-6"><x-input-field name="store_link" label="Store Link" placeholder="Enter store link" /></div>
                        </div>

                        <hr>

                        <!-- Personal & KYC -->
                        <h5 class="mb-3 text-primary">Personal & KYC</h5>
                        <div class="row">
                            <div class="col-md-4"><x-input-field type="date" name="date_of_birth" label="Date of Birth" /></div>
                            <div class="col-md-4"><x-input-field type="select" name="gender" label="Gender" placeholder="Select Gender">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </x-input-field></div>
                            <div class="col-md-4"><x-input-field name="aadhar_number" label="Aadhaar Number" placeholder="Enter aadhaar number" /></div>
                            <div class="col-md-6"><x-input-field type="file" name="aadhaar_front" label="Aadhaar Front Image" accept="image/*" /></div>
                            <div class="col-md-6"><x-input-field type="file" name="aadhaar_back" label="Aadhaar Back Image" accept="image/*" /></div>
                            <div class="col-md-6"><x-input-field name="pancard_number" label="PAN Card Number" placeholder="Enter PAN number" /></div>
                            <div class="col-md-6"><x-input-field type="file" name="pancard_image" label="PAN Card Image" accept="image/*" /></div>
                            <div class="col-md-6"><x-input-field name="gst_number" label="GST Number" placeholder="Enter GST number" /></div>
                            <div class="col-md-6"><x-input-field type="file" name="gst_certificate_image" label="GST Certificate Image" accept="image/*" /></div>
                            <div class="col-md-6"><x-input-field type="file" name="kyc_document" label="Other KYC Document" /></div>
                        </div>

                        <hr>

                        <!-- Bank Details -->
                        <h5 class="mb-3 text-primary">Bank Details</h5>
                        <div class="row">
                            <div class="col-md-6"><x-input-field name="account_holder_name" label="Account Holder Name" placeholder="Enter account holder name" /></div>
                            <div class="col-md-6"><x-input-field name="bank_account_number" label="Account Number" placeholder="Enter account number" /></div>
                            <div class="col-md-6"><x-input-field name="ifsc_code" label="IFSC Code" placeholder="Enter IFSC code" /></div>
                            <div class="col-md-6"><x-input-field type="file" name="cancel_check_image" label="Cancelled Cheque Image" accept="image/*" /></div>
                        </div>

                        <hr>

                        <!-- Address -->
                        <h5 class="mb-3 text-primary">Address</h5>
                        <div class="row">
                            <div class="col-md-12"><x-input-field type="textarea" name="address" label="Address" placeholder="Enter address" rows="2" /></div>
                            <div class="col-md-3"><x-input-field name="city" label="City" placeholder="Enter city" /></div>
                            <div class="col-md-3"><x-input-field name="state" label="State" placeholder="Enter state" /></div>
                            <div class="col-md-3"><x-input-field name="country" label="Country" placeholder="Enter country" /></div>
                            <div class="col-md-3"><x-input-field name="pincode" label="Pincode" placeholder="Enter pincode" /></div>
                        </div>

                        <hr>

                        <!-- Social Media -->
                        <h5 class="mb-3 text-primary">Social Media</h5>
                        <div class="row">
                            <div class="col-md-4"><x-input-field name="social_links[whatsapp]" label="WhatsApp" placeholder="Enter WhatsApp" /></div>
                            <div class="col-md-4"><x-input-field name="social_links[website]" label="Website" placeholder="Enter website" /></div>
                            <div class="col-md-4"><x-input-field name="social_links[facebook]" label="Facebook" placeholder="Enter Facebook" /></div>
                            <div class="col-md-4"><x-input-field name="social_links[instagram]" label="Instagram" placeholder="Enter Instagram" /></div>
                            <div class="col-md-4"><x-input-field name="social_links[linkedin]" label="LinkedIn" placeholder="Enter LinkedIn" /></div>
                            <div class="col-md-4"><x-input-field name="social_links[youtube]" label="YouTube" placeholder="Enter YouTube" /></div>
                            <div class="col-md-4"><x-input-field name="social_links[telegram]" label="Telegram" placeholder="Enter Telegram" /></div>
                            <div class="col-md-4"><x-input-field name="social_links[twitter]" label="Twitter" placeholder="Enter Twitter" /></div>
                        </div>

                        <button type="submit" class="btn btn-primary" id="sellerCreateButton">
                            <i class="bx bx-loader spinner me-2" style="display: none" id="sellerCreateBtnSpinner"></i>Create Seller
                        </button>
                        <a href="{{ route('owner.sellers.index') }}" class="btn btn-secondary">Cancel</a>
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
                    url: "{{ route('owner.sellers.store') }}",
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
                                window.location.href = "{{ route('owner.sellers.index') }}";
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
