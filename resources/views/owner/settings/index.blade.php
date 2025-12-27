@extends('owner.master')

@section('title', 'Application Settings')

@section('main')
<div class="container-fluid">

    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Application Settings</h4>
            </div>
        </div>
    </div>
    <!-- end page title end breadcrumb -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Application Settings</h4>
                    <p class="text-muted mb-3">Update your application settings.</p>

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form id="form" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-lg-6">
                                <x-input-field 
                                    name="site_name" 
                                    label="Site Name" 
                                    value="{{ old('site_name', $settings->site_name ?? '') }}" 
                                    required 
                                />
                                
                                <x-input-field 
                                    name="site_phone" 
                                    label="Phone" 
                                    value="{{ old('site_phone', $settings->site_phone ?? '') }}" 
                                />
                                
                                <x-input-field 
                                    type="url" 
                                    name="facebook_url" 
                                    label="Facebook URL" 
                                    value="{{ old('facebook_url', $settings->facebook_url ?? '') }}" 
                                    icon="bx bxl-facebook" 
                                />

                                <x-input-field 
                                    type="url" 
                                    name="instagram_url" 
                                    label="Instagram URL" 
                                    value="{{ old('instagram_url', $settings->instagram_url ?? '') }}" 
                                    icon="bx bxl-instagram" 
                                />

                                <x-input-field 
                                    type="url" 
                                    name="twitter_url" 
                                    label="Twitter URL" 
                                    value="{{ old('twitter_url', $settings->twitter_url ?? '') }}" 
                                    icon="bx bxl-twitter" 
                                />

                                <div class="mb-3">
                                    <label for="light_logo" class="form-label">Site Light Logo</label>
                                    <x-input-field 
                                        type="file" 
                                        name="light_logo" 
                                        inputClass="form-control" 
                                    />
                                    @if ($settings && $settings->light_logo)
                                        <div class="mt-2">
                                            <img src="{{ $settings->light_logo }}" alt="Site Light Logo" class="img-thumbnail" width="200">
                                        </div>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label for="dark_logo" class="form-label">Site Dark Logo</label>
                                    <x-input-field 
                                        type="file" 
                                        name="dark_logo" 
                                        inputClass="form-control" 
                                    />
                                    @if ($settings && $settings->dark_logo)
                                        <div class="mt-2">
                                            <img src="{{ $settings->dark_logo }}" alt="Site Dark Logo" class="img-thumbnail" width="200">
                                        </div>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label for="favicon" class="form-label">Favicon</label>
                                    <x-input-field 
                                        type="file" 
                                        name="favicon" 
                                        inputClass="form-control" 
                                    />
                                    @if ($settings && $settings->favicon)
                                        <div class="mt-2">
                                            <img src="{{ $settings->favicon }}" alt="Favicon" class="img-thumbnail" width="50">
                                        </div>
                                    @endif
                                </div>

                            </div>
                            <div class="col-lg-6">
                                <x-input-field 
                                    type="email" 
                                    name="site_email" 
                                    label="Site Email" 
                                    value="{{ old('site_email', $settings->site_email ?? '') }}" 
                                />

                                <x-input-field 
                                    name="site_address" 
                                    label="Address" 
                                    value="{{ old('site_address', $settings->site_address ?? '') }}" 
                                />

                                <x-input-field 
                                    type="url" 
                                    name="linkedin_url" 
                                    label="LinkedIn URL" 
                                    value="{{ old('linkedin_url', $settings->linkedin_url ?? '') }}" 
                                    icon="bx bxl-linkedin" 
                                />

                                <x-input-field 
                                    type="url" 
                                    name="youtube_url" 
                                    label="YouTube URL" 
                                    value="{{ old('youtube_url', $settings->youtube_url ?? '') }}" 
                                    icon="bx bxl-youtube" 
                                />

                                <x-input-field 
                                    type="url" 
                                    name="pinterest_url" 
                                    label="Pinterest URL" 
                                    value="{{ old('pinterest_url', $settings->pinterest_url ?? '') }}" 
                                    icon="bx bxl-pinterest" 
                                />

                                <x-input-field 
                                    type="url" 
                                    name="tiktok_url" 
                                    label="TikTok URL" 
                                    value="{{ old('tiktok_url', $settings->tiktok_url ?? '') }}" 
                                    icon="bx bxl-tiktok" 
                                />

                                <x-input-field 
                                    type="url" 
                                    name="whatsapp_url" 
                                    label="WhatsApp URL" 
                                    value="{{ old('whatsapp_url', $settings->whatsapp_url ?? '') }}" 
                                    icon="bx bxl-whatsapp" 
                                />

                                <x-input-field 
                                    type="url" 
                                    name="telegram_url" 
                                    label="Telegram URL" 
                                    value="{{ old('telegram_url', $settings->telegram_url ?? '') }}" 
                                    icon="bx bxl-telegram" 
                                />

                            </div>

                        </div>

                        <div class="form-group mb-0">
                            <div>
                                <button type="submit" class="btn btn-primary waves-effect waves-light" id="submitButton">
                                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true" style="display: none" id="submitBtnSpinner"></span>
                                    Update Settings
                                </button>
                            </div>
                        </div>
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
        $('#form').validate({
            rules: {
                site_name: {
                    required: true,
                    maxlength: 100
                }
            },
            messages: {
                site_name: {
                    required: "Please enter the site name",
                    maxlength: "Site name cannot exceed 100 characters"
                }
            },
            errorPlacement: function (error, element) {
                element.after(error);
            },
            errorClass: "text-danger",
            submitHandler: function(form, e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('owner.settings.update') }}",
                    type: "POST",
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: function () {
                        $('#submitButton').attr('disabled', true);
                        $("#submitBtnSpinner").show();
                    },
                    complete: function () {
                        $('#submitButton').attr('disabled', false);
                        $("#submitBtnSpinner").hide();
                    },
                    success: function(response) {
                        sendSuccess(response.message);
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    },
                    error: function(xhr) {
                        let data = xhr.responseJSON;
                        if (data.hasOwnProperty('error')) {
                            if (data.error.hasOwnProperty('site_name')) {
                                sendToast('error', data.error.site_name);
                            }
                        } else if (data.hasOwnProperty('message')) {
                            actionError(xhr, data.message)
                        } else {
                            actionError(xhr);
                        }
                    }
                });
            }
        });
    });
</script>
@endsection