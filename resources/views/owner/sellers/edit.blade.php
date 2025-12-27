@extends('owner.master')
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
                            <div class="col-md-6"><x-input-field name="business_name" label="Business Name" placeholder="Enter business name" value="{{ $seller->business_name }}" required /></div>
                            <div class="col-md-6"><x-input-field name="owner_name" label="Owner Name" placeholder="Enter owner name" value="{{ $seller->owner_name }}" required /></div>
                            <div class="col-md-6"><x-input-field type="email" name="email" label="Email" placeholder="Enter email" value="{{ $seller->email }}" required /></div>
                            <div class="col-md-6"><x-input-field name="phone" label="Phone" placeholder="Enter phone" value="{{ $seller->phone }}" required /></div>
                            <div class="col-md-6"><x-input-field type="password" name="password" label="Password (Leave blank to keep current)" placeholder="Enter new password" /></div>
                            <div class="col-md-6"><x-input-field type="password" name="password_confirmation" label="Confirm Password" placeholder="Confirm password" /></div>
                        </div>

                        <hr>

                        <!-- Business Details -->
                        <h5 class="mb-3 text-primary">Business Details</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <x-input-field type="file" name="business_logo" label="Business Logo" accept="image/*" />
                                @if($seller->business_logo)
                                    <div class="mt-2">
                                        <a href="{{ $seller->business_logo }}" target="_blank">View Current Logo</a>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6"><x-input-field type="select" name="business_type" label="Business Type" placeholder="Select Type">
                                <option value="wholesale" {{ $seller->business_type == 'wholesale' ? 'selected' : '' }}>Wholesale</option>
                                <option value="retail" {{ $seller->business_type == 'retail' ? 'selected' : '' }}>Retail</option>
                                <option value="service_provider" {{ $seller->business_type == 'service_provider' ? 'selected' : '' }}>Service Provider</option>
                                <option value="manufacturer" {{ $seller->business_type == 'manufacturer' ? 'selected' : '' }}>Manufacturer</option>
                            </x-input-field></div>
                            <div class="col-md-6"><x-input-field type="select" name="category_id" label="Category" placeholder="Select Category">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $seller->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </x-input-field></div>
                            <div class="col-md-6"><x-input-field type="select" name="sub_category_id" label="Sub Category" placeholder="Select Sub Category">
                                <!-- Populate via AJAX based on Category -->
                            </x-input-field></div>
                            <div class="col-md-6"><x-input-field name="username" label="Username" placeholder="Enter username" value="{{ $seller->username }}" /></div>
                            <div class="col-md-6"><x-input-field name="bar_code" label="Bar Code" placeholder="Enter bar code" value="{{ $seller->bar_code }}" /></div>
                            <div class="col-md-6"><x-input-field name="store_link" label="Store Link" placeholder="Enter store link" value="{{ $seller->store_link }}" /></div>
                        </div>

                        <hr>

                        <!-- Personal & KYC -->
                        <h5 class="mb-3 text-primary">Personal & KYC</h5>
                        <div class="row">
                            <div class="col-md-4"><x-input-field type="date" name="date_of_birth" label="Date of Birth" value="{{ $seller->date_of_birth ? $seller->date_of_birth->format('Y-m-d') : '' }}" /></div>
                            <div class="col-md-4"><x-input-field type="select" name="gender" label="Gender" placeholder="Select Gender">
                                <option value="male" {{ $seller->gender == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ $seller->gender == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ $seller->gender == 'other' ? 'selected' : '' }}>Other</option>
                            </x-input-field></div>
                            <div class="col-md-4"><x-input-field name="aadhar_number" label="Aadhaar Number" placeholder="Enter aadhaar number" value="{{ $seller->aadhar_number }}" /></div>
                            <div class="col-md-6">
                                <x-input-field type="file" name="aadhaar_front" label="Aadhaar Front Image" accept="image/*" />
                                @if($seller->aadhaar_front)
                                    <div class="mt-2">
                                        <a href="{{ $seller->aadhaar_front }}" target="_blank">View Current Image</a>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <x-input-field type="file" name="aadhaar_back" label="Aadhaar Back Image" accept="image/*" />
                                @if($seller->aadhaar_back)
                                    <div class="mt-2">
                                        <a href="{{ $seller->aadhaar_back }}" target="_blank">View Current Image</a>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6"><x-input-field name="pancard_number" label="PAN Card Number" placeholder="Enter PAN number" value="{{ $seller->pancard_number }}" /></div>
                            <div class="col-md-6">
                                <x-input-field type="file" name="pancard_image" label="PAN Card Image" accept="image/*" />
                                @if($seller->pancard_image)
                                    <div class="mt-2">
                                        <a href="{{ $seller->pancard_image }}" target="_blank">View Current Image</a>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6"><x-input-field name="gst_number" label="GST Number" placeholder="Enter GST number" value="{{ $seller->gst_number }}" /></div>
                            <div class="col-md-6">
                                <x-input-field type="file" name="gst_certificate_image" label="GST Certificate Image" accept="image/*" />
                                @if($seller->gst_certificate_image)
                                    <div class="mt-2">
                                        <a href="{{ $seller->gst_certificate_image }}" target="_blank">View Current Image</a>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <x-input-field type="file" name="kyc_document" label="Other KYC Document" />
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
                            <div class="col-md-6"><x-input-field name="account_holder_name" label="Account Holder Name" placeholder="Enter account holder name" value="{{ $seller->account_holder_name }}" /></div>
                            <div class="col-md-6"><x-input-field name="bank_account_number" label="Account Number" placeholder="Enter account number" value="{{ $seller->bank_account_number }}" /></div>
                            <div class="col-md-6"><x-input-field name="ifsc_code" label="IFSC Code" placeholder="Enter IFSC code" value="{{ $seller->ifsc_code }}" /></div>
                            <div class="col-md-6">
                                <x-input-field type="file" name="cancel_check_image" label="Cancelled Cheque Image" accept="image/*" />
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
                            <div class="col-md-12"><x-input-field type="textarea" name="address" label="Address" placeholder="Enter address" value="{{ $seller->address }}" rows="2" /></div>
                            <div class="col-md-3"><x-input-field name="city" label="City" placeholder="Enter city" value="{{ $seller->city }}" /></div>
                            <div class="col-md-3"><x-input-field name="state" label="State" placeholder="Enter state" value="{{ $seller->state }}" /></div>
                            <div class="col-md-3"><x-input-field name="country" label="Country" placeholder="Enter country" value="{{ $seller->country }}" /></div>
                            <div class="col-md-3"><x-input-field name="pincode" label="Pincode" placeholder="Enter pincode" value="{{ $seller->pincode }}" /></div>
                        </div>

                        <hr>

                        <!-- Social Media -->
                        <h5 class="mb-3 text-primary">Social Media</h5>
                        <div class="row">
                            <div class="col-md-4"><x-input-field name="social_links[whatsapp]" label="WhatsApp" placeholder="Enter WhatsApp" value="{{ $seller->social_links['whatsapp'] ?? '' }}" /></div>
                            <div class="col-md-4"><x-input-field name="social_links[website]" label="Website" placeholder="Enter website" value="{{ $seller->social_links['website'] ?? '' }}" /></div>
                            <div class="col-md-4"><x-input-field name="social_links[facebook]" label="Facebook" placeholder="Enter Facebook" value="{{ $seller->social_links['facebook'] ?? '' }}" /></div>
                            <div class="col-md-4"><x-input-field name="social_links[instagram]" label="Instagram" placeholder="Enter Instagram" value="{{ $seller->social_links['instagram'] ?? '' }}" /></div>
                            <div class="col-md-4"><x-input-field name="social_links[linkedin]" label="LinkedIn" placeholder="Enter LinkedIn" value="{{ $seller->social_links['linkedin'] ?? '' }}" /></div>
                            <div class="col-md-4"><x-input-field name="social_links[youtube]" label="YouTube" placeholder="Enter YouTube" value="{{ $seller->social_links['youtube'] ?? '' }}" /></div>
                            <div class="col-md-4"><x-input-field name="social_links[telegram]" label="Telegram" placeholder="Enter Telegram" value="{{ $seller->social_links['telegram'] ?? '' }}" /></div>
                            <div class="col-md-4"><x-input-field name="social_links[twitter]" label="Twitter" placeholder="Enter Twitter" value="{{ $seller->social_links['twitter'] ?? '' }}" /></div>
                        </div>

                        <button type="submit" class="btn btn-primary" id="sellerEditButton">
                            <i class="bx bx-loader spinner me-2" style="display: none" id="sellerEditBtnSpinner"></i>Update Seller
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
                    url: "{{ route('owner.sellers.update', $seller->id) }}",
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
                        $('#sellerEditButton').attr('disabled', false);
                        $("#sellerEditBtnSpinner").hide();
                    },
                });
            }
        });
    });
</script>
@endsection
