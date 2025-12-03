@extends('staff.master')
@section('title', 'Staff Settings')
@section('main')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Staff Settings</h5>
                    </div>

                    <div class="card-body">
                        <form method="POST" id="settingsForm">
                            @csrf

                            <!-- Two-Factor Authentication -->
                            <div class="mb-4">
                                <h6 class="mb-3">Two-Factor Authentication</h6>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <label class="form-label mb-1">Enable Two-Factor Authentication</label>
                                        <p class="text-muted small mb-0">Add an extra layer of security to your account</p>
                                    </div>
                                    <div class="form-check form-switch form-switch-lg">
                                        <input class="form-check-input" type="checkbox" role="switch" 
                                               id="two_factor_enabled" name="two_factor_enabled" value="1"
                                               @if($staff->two_factor_enabled) checked @endif>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <!-- Email Verification -->
                            <div class="mb-4">
                                <h6 class="mb-3">Email Verification</h6>
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <label class="form-label mb-1">Email Verification Status</label>
                                        @if($staff->email_verified_at)
                                            <p class="text-success mb-0">
                                                <i class="ri-checkbox-circle-fill"></i> 
                                                Email verified on {{ $staff->email_verified_at->format('d M Y, h:i A') }}
                                            </p>
                                        @else
                                            <p class="text-warning mb-0">
                                                <i class="ri-error-warning-fill"></i> 
                                                Email not verified
                                            </p>
                                        @endif
                                    </div>
                                    <div class="col-md-4 text-end">
                                        @if($staff->email_verified_at)
                                            <button type="button" class="btn btn-sm btn-outline-danger" id="unverifyEmailBtn">
                                                <i class="ri-close-circle-line"></i> Mark as Unverified
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-sm btn-success" id="verifyEmailBtn">
                                                <i class="ri-checkbox-circle-line"></i> Mark as Verified
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary" id="settingsUpdateButton">
                                    <i class="bx bx-loader spinner me-2" style="display: none" id="settingsUpdateBtnSpinner"></i>
                                    Save Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Account Information Card -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Account Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small">Email Address</label>
                                <p class="mb-0">{{ $staff->email }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small">Last Login</label>
                                <p class="mb-0">
                                    @if($staff->last_login_at)
                                        {{ $staff->last_login_at->format('d M Y, h:i A') }}
                                    @else
                                        Never
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small">Last Login IP</label>
                                <p class="mb-0">{{ $staff->last_login_ip ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small">Account Status</label>
                                <p class="mb-0">
                                    <span class="badge bg-{{ $staff->status === 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($staff->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
<script>
    $(document).ready(function() {
        // Handle form submission
        $("#settingsForm").on('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            // If checkbox is not checked, explicitly set it to 0
            if (!$('#two_factor_enabled').is(':checked')) {
                formData.set('two_factor_enabled', '0');
            }

            $.ajax({
                url: "{{ route('staff.settings.update') }}",
                method: "POST",
                dataType: "json",
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                beforeSend: function () {
                    $('#settingsUpdateButton').attr('disabled', true);
                    $("#settingsUpdateBtnSpinner").show();
                },
                success: function (result) {
                    sendSuccess(result.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                },
                error: function (xhr) {
                    let data = xhr.responseJSON;
                    if (data.hasOwnProperty('message')) {
                        actionError(xhr, data.message);
                    } else {
                        actionError(xhr);
                    }
                },
                complete: function () {
                    $('#settingsUpdateButton').attr('disabled', false);
                    $("#settingsUpdateBtnSpinner").hide();
                },
            });
        });

        // Handle verify email button
        $('#verifyEmailBtn').on('click', function() {
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('email_verified', '1');

            $.ajax({
                url: "{{ route('staff.settings.update') }}",
                method: "POST",
                dataType: "json",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $('#verifyEmailBtn').attr('disabled', true);
                },
                success: function (result) {
                    sendSuccess(result.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                },
                error: function (xhr) {
                    let data = xhr.responseJSON;
                    if (data.hasOwnProperty('message')) {
                        actionError(xhr, data.message);
                    } else {
                        actionError(xhr);
                    }
                },
                complete: function () {
                    $('#verifyEmailBtn').attr('disabled', false);
                },
            });
        });

        // Handle unverify email button
        $('#unverifyEmailBtn').on('click', function() {
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('email_verified', '0');

            $.ajax({
                url: "{{ route('staff.settings.update') }}",
                method: "POST",
                dataType: "json",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $('#unverifyEmailBtn').attr('disabled', true);
                },
                success: function (result) {
                    sendSuccess(result.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                },
                error: function (xhr) {
                    let data = xhr.responseJSON;
                    if (data.hasOwnProperty('message')) {
                        actionError(xhr, data.message);
                    } else {
                        actionError(xhr);
                    }
                },
                complete: function () {
                    $('#unverifyEmailBtn').attr('disabled', false);
                },
            });
        });
    });
</script>
@endsection
