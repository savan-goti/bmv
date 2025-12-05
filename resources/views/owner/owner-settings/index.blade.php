@extends('owner.master')
@section('title', 'Owner Settings')
@section('main')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Owner Settings</h5>
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
                                               @if($owner->two_factor_enabled) checked @endif>
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
                                        @if($owner->email_verified_at)
                                            <p class="text-success mb-0">
                                                <i class="ri-checkbox-circle-fill"></i> 
                                                Email verified on {{ $owner->email_verified_at->format('d M Y, h:i A') }}
                                            </p>
                                        @else
                                            <p class="text-warning mb-0">
                                                <i class="ri-error-warning-fill"></i> 
                                                Email not verified
                                            </p>
                                        @endif
                                    </div>
                                    <div class="col-md-4 text-end">
                                        @if($owner->email_verified_at)
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
                                <p class="mb-0">{{ $owner->email }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small">Last Login</label>
                                <p class="mb-0">
                                    @if($owner->last_login_at)
                                        {{ $owner->last_login_at->format('d M Y, h:i A') }}
                                    @else
                                        Never
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small">Last Login IP</label>
                                <p class="mb-0">{{ $owner->last_login_ip ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small">Account Status</label>
                                <p class="mb-0">
                                    <span class="badge bg-{{ $owner->status === 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($owner->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Sessions Card -->
                <div class="card mt-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Active Sessions</h5>
                        <button type="button" class="btn btn-sm btn-danger" id="logoutAllOthersBtn">
                            <i class="ri-logout-box-line"></i> Logout All Other Sessions
                        </button>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-3">Manage your active sessions on other browsers and devices.</p>
                        <div id="sessionsContainer">
                            <div class="text-center py-3">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
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
                url: "{{ route('owner.owner-settings.update') }}",
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
                url: "{{ route('owner.owner-settings.update') }}",
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
                url: "{{ route('owner.owner-settings.update') }}",
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

        // Load active sessions
        function loadSessions() {
            $.ajax({
                url: "{{ route('owner.owner-settings.sessions') }}",
                method: "GET",
                dataType: "json",
                success: function (result) {
                    displaySessions(result.data);
                },
                error: function (xhr) {
                    $('#sessionsContainer').html(
                        '<div class="alert alert-danger">Failed to load sessions. Please refresh the page.</div>'
                    );
                },
            });
        }

        // Display sessions
        function displaySessions(sessions) {
            if (sessions.length === 0) {
                $('#sessionsContainer').html(
                    '<p class="text-muted text-center">No active sessions found.</p>'
                );
                return;
            }

            let html = '';
            sessions.forEach(function(session) {
                const lastActivity = new Date(session.last_activity * 1000);
                const deviceInfo = parseUserAgent(session.user_agent);
                const isCurrent = session.is_current;

                html += `
                    <div class="border rounded p-3 mb-3 ${isCurrent ? 'border-primary bg-light' : ''}">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="${deviceInfo.icon} fs-4 me-2"></i>
                                    <div>
                                        <h6 class="mb-0">${deviceInfo.device}</h6>
                                        <small class="text-muted">${deviceInfo.browser} on ${deviceInfo.os}</small>
                                    </div>
                                    ${isCurrent ? '<span class="badge bg-primary ms-2">Current Session</span>' : ''}
                                </div>
                                <div class="small text-muted">
                                    <i class="ri-map-pin-line"></i> IP: ${session.ip_address}
                                    <span class="ms-3"><i class="ri-time-line"></i> Last active: ${lastActivity.toLocaleString()}</span>
                                </div>
                            </div>
                            <div class="col-md-4 text-end">
                                ${!isCurrent ? `
                                    <button type="button" class="btn btn-sm btn-outline-danger logout-session-btn" 
                                            data-session-id="${session.id}">
                                        <i class="ri-logout-box-line"></i> Logout
                                    </button>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                `;
            });

            $('#sessionsContainer').html(html);
        }

        // Parse user agent to get device info
        function parseUserAgent(userAgent) {
            let device = 'Unknown Device';
            let browser = 'Unknown Browser';
            let os = 'Unknown OS';
            let icon = 'ri-computer-line';

            // Detect OS
            if (userAgent.includes('Windows')) os = 'Windows';
            else if (userAgent.includes('Mac')) os = 'macOS';
            else if (userAgent.includes('Linux')) os = 'Linux';
            else if (userAgent.includes('Android')) os = 'Android';
            else if (userAgent.includes('iOS') || userAgent.includes('iPhone') || userAgent.includes('iPad')) os = 'iOS';

            // Detect Browser
            if (userAgent.includes('Chrome') && !userAgent.includes('Edg')) browser = 'Chrome';
            else if (userAgent.includes('Firefox')) browser = 'Firefox';
            else if (userAgent.includes('Safari') && !userAgent.includes('Chrome')) browser = 'Safari';
            else if (userAgent.includes('Edg')) browser = 'Edge';
            else if (userAgent.includes('Opera') || userAgent.includes('OPR')) browser = 'Opera';

            // Detect Device Type
            if (userAgent.includes('Mobile') || userAgent.includes('Android') || userAgent.includes('iPhone')) {
                device = 'Mobile Device';
                icon = 'ri-smartphone-line';
            } else if (userAgent.includes('Tablet') || userAgent.includes('iPad')) {
                device = 'Tablet';
                icon = 'ri-tablet-line';
            } else {
                device = 'Desktop Computer';
                icon = 'ri-computer-line';
            }

            return { device, browser, os, icon };
        }

        // Handle logout from specific session
        $(document).on('click', '.logout-session-btn', function() {
            const sessionId = $(this).data('session-id');
            const btn = $(this);

            if (!confirm('Are you sure you want to logout from this session?')) {
                return;
            }

            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('session_id', sessionId);

            $.ajax({
                url: "{{ route('owner.owner-settings.sessions.logout') }}",
                method: "POST",
                dataType: "json",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    btn.attr('disabled', true);
                },
                success: function (result) {
                    sendSuccess(result.message);
                    loadSessions();
                },
                error: function (xhr) {
                    let data = xhr.responseJSON;
                    if (data.hasOwnProperty('message')) {
                        actionError(xhr, data.message);
                    } else {
                        actionError(xhr);
                    }
                    btn.attr('disabled', false);
                },
            });
        });

        // Handle logout from all other sessions
        $('#logoutAllOthersBtn').on('click', function() {
            if (!confirm('Are you sure you want to logout from all other sessions?')) {
                return;
            }

            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');

            $.ajax({
                url: "{{ route('owner.owner-settings.sessions.logout-others') }}",
                method: "POST",
                dataType: "json",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $('#logoutAllOthersBtn').attr('disabled', true);
                },
                success: function (result) {
                    sendSuccess(result.message);
                    loadSessions();
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
                    $('#logoutAllOthersBtn').attr('disabled', false);
                },
            });
        });

        // Load sessions on page load
        loadSessions();
    });
</script>
@endsection
