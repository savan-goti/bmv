@extends('owner.master')

@section('title', 'Settings')

@section('main')
<div class="container-fluid">

    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Settings</h4>
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
                                <div class="mb-3">
                                    <label for="site_name" class="form-label">Site Name</label>
                                    <input type="text" class="form-control" id="site_name" name="site_name" value="{{  old('site_name', $settings->site_name ?? '') }}">
                                </div>
                                <div class="mb-3">
                                    <label for="site_phone" class="form-label">Phone</label>
                                    <input type="text" class="form-control" id="site_phone" name="site_phone" value="{{ old('site_phone', $settings->site_phone ?? '') }}">
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Facebook URL</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class='bx bxl-facebook'></i></span>
                                        <input type="url" class="form-control" name="facebook_url"
                                            value="{{ old('facebook_url', $settings->facebook_url ?? '') }}">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Instagram URL</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class='bx bxl-instagram'></i></span>
                                        <input type="url" class="form-control" name="instagram_url"
                                            value="{{ old('instagram_url', $settings->instagram_url ?? '') }}">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Twitter URL</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class='bx bxl-twitter'></i></span>
                                        <input type="url" class="form-control" name="twitter_url"
                                            value="{{ old('twitter_url', $settings->twitter_url ?? '') }}">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="light_logo" class="form-label">Site Light Logo</label>
                                    <input type="file" class="form-control" id="light_logo" name="light_logo">
                                    @if ($settings && $settings->light_logo)
                                        <img src="{{ $settings->light_logo }}" alt="Site Light Logo" class="img-thumbnail mt-2" width="200">
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label for="dark_logo" class="form-label">Site Dark Logo</label>
                                    <input type="file" class="form-control" id="dark_logo" name="dark_logo">
                                    @if ($settings && $settings->dark_logo)
                                        <img src="{{ $settings->dark_logo }}" alt="Site Dark Logo" class="img-thumbnail mt-2" width="200">
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label for="favicon" class="form-label">Favicon</label>
                                    <input type="file" class="form-control" id="favicon" name="favicon">
                                    @if ($settings && $settings->favicon)
                                        <img src="{{ $settings->favicon }}" alt="Favicon" class="img-thumbnail mt-2" width="50">
                                    @endif
                                </div>

                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="site_email" class="form-label">Site Email</label>
                                    <input type="email" class="form-control" id="site_email" name="site_email" value="{{ old('site_email', $settings->site_email ?? '') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="site_address" class="form-label">Address</label>
                                    <input type="text" class="form-control" id="site_address" name="site_address" value="{{ old('site_address', $settings->site_address ?? '') }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">LinkedIn URL</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class='bx bxl-linkedin'></i></span>
                                        <input type="url" class="form-control" name="linkedin_url"
                                            value="{{ old('linkedin_url', $settings->linkedin_url ?? '') }}">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">YouTube URL</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class='bx bxl-youtube'></i></span>
                                        <input type="url" class="form-control" name="youtube_url"
                                            value="{{ old('youtube_url', $settings->youtube_url ?? '') }}">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Pinterest URL</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class='bx bxl-pinterest'></i></span>
                                        <input type="url" class="form-control" name="pinterest_url"
                                            value="{{ old('pinterest_url', $settings->pinterest_url ?? '') }}">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">TikTok URL</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class='bx bxl-tiktok'></i></span>
                                        <input type="url" class="form-control" name="tiktok_url"
                                            value="{{ old('tiktok_url', $settings->tiktok_url ?? '') }}">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">WhatsApp URL</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class='bx bxl-whatsapp'></i></span>
                                        <input type="url" class="form-control" name="whatsapp_url"
                                            value="{{ old('whatsapp_url', $settings->whatsapp_url ?? '') }}">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Telegram URL</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class='bx bxl-telegram'></i></span>
                                        <input type="url" class="form-control" name="telegram_url"
                                            value="{{ old('telegram_url', $settings->telegram_url ?? '') }}">
                                    </div>
                                </div>


                            </div>

                        </div>

                        <div class="form-group mb-0">
                            <div>
                                <button type="submit" class="btn btn-primary waves-effect waves-light">
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