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
                        <!-- Two-Factor Authentication -->
                        <div class="mb-4">
                            <h6 class="mb-3">Two-Factor Authentication</h6>
                            
                            @if($owner->two_factor_enabled && $owner->two_factor_confirmed_at)
                                <!-- 2FA is enabled -->
                                <div class="alert alert-success">
                                    <i class="ri-shield-check-line"></i> 
                                    Two-factor authentication is <strong>enabled</strong> and protecting your account.
                                    <br>
                                    <small class="text-muted">Enabled on {{ $owner->two_factor_confirmed_at->format('d M Y, h:i A') }}</small>
                                </div>

                                <div class="d-flex gap-2 mb-3">
                                    <button type="button" class="btn btn-sm btn-primary" id="showRecoveryCodesBtn">
                                        <i class="ri-key-line"></i> View Recovery Codes
                                    </button>
                                    <button type="button" class="btn btn-sm btn-warning" id="regenerateRecoveryCodesBtn">
                                        <i class="ri-refresh-line"></i> Regenerate Recovery Codes
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" id="disableTwoFactorBtn">
                                        <i class="ri-shield-off-line"></i> Disable 2FA
                                    </button>
                                </div>

                                <!-- Recovery Codes Display (Hidden by default) -->
                                <div id="recoveryCodesContainer" style="display: none;">
                                    <div class="alert alert-warning">
                                        <h6 class="alert-heading">Recovery Codes</h6>
                                        <p class="small mb-2">Store these recovery codes in a secure location. They can be used to access your account if you lose your 2FA device.</p>
                                        <div id="recoveryCodesList" class="font-monospace"></div>
                                        <button type="button" class="btn btn-sm btn-outline-dark mt-2" id="copyRecoveryCodesBtn">
                                            <i class="ri-file-copy-line"></i> Copy Codes
                                        </button>
                                    </div>
                                </div>
                            @else
                                <!-- 2FA is not enabled -->
                                <div class="alert alert-warning">
                                    <i class="ri-error-warning-line"></i> 
                                    Two-factor authentication is <strong>not enabled</strong>. Enable it to add an extra layer of security to your account.
                                </div>

                                <button type="button" class="btn btn-primary" id="enableTwoFactorBtn">
                                    <i class="ri-shield-check-line"></i> Enable Two-Factor Authentication
                                </button>

                                <!-- 2FA Setup Container (Hidden by default) -->
                                <div id="twoFactorSetupContainer" style="display: none;" class="mt-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title">Set Up Two-Factor Authentication</h6>
                                            
                                            <ol class="mb-3">
                                                <li class="mb-2">Download an authenticator app like <strong>Google Authenticator</strong>, <strong>Authy</strong>, or <strong>Microsoft Authenticator</strong>.</li>
                                                <li class="mb-2">Scan the QR code below with your authenticator app:</li>
                                            </ol>

                                            <div class="text-center mb-3">
                                                <div id="qrCodeContainer" class="d-inline-block p-3 bg-white border rounded">
                                                    <div class="spinner-border text-primary" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="alert alert-info">
                                                <strong>Manual Entry:</strong> If you can't scan the QR code, enter this secret key manually:
                                                <div class="font-monospace mt-2" id="secretKeyDisplay"></div>
                                            </div>

                                            <form id="verifyTwoFactorForm">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="verification_code" class="form-label">Enter the 6-digit code from your authenticator app:</label>
                                                    <input type="text" class="form-control" id="verification_code" name="code" 
                                                           placeholder="000000" maxlength="6" pattern="[0-9]{6}" required>
                                                </div>
                                                <div class="d-flex gap-2">
                                                    <button type="submit" class="btn btn-success" id="verifyTwoFactorBtn">
                                                        <i class="bx bx-loader spinner me-2" style="display: none" id="verifyTwoFactorSpinner"></i>
                                                        <i class="ri-checkbox-circle-line"></i> Verify and Enable
                                                    </button>
                                                    <button type="button" class="btn btn-secondary" id="cancelTwoFactorSetupBtn">
                                                        Cancel
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <hr>

                        <!-- Email Verification -->
                        <form method="POST" id="settingsForm">
                            @csrf

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

                <!-- Danger Zone Card -->
                <div class="card mt-4 border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="ri-alert-line"></i> Danger Zone</h5>
                    </div>
                    <div class="card-body">
                        <h6 class="text-danger mb-3">Delete Account</h6>
                        <p class="text-muted mb-3">
                            Once you delete your account, there is no going back. Please be certain. This action will:
                        </p>
                        <ul class="text-muted mb-3">
                            <li>Permanently delete your owner account</li>
                            <li>Remove all your personal information</li>
                            <li>Terminate all active sessions</li>
                            <li>This action cannot be undone</li>
                        </ul>
                        
                        <button type="button" class="btn btn-danger" id="deleteAccountBtn">
                            <i class="ri-delete-bin-line"></i> Delete My Account
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
<script>
    $(document).ready(function() {
        // Enable Two-Factor Authentication
        $('#enableTwoFactorBtn').on('click', function() {
            const btn = $(this);
            
            $.ajax({
                url: "{{ route('owner.owner-settings.two-factor.enable') }}",
                method: "POST",
                dataType: "json",
                data: {
                    _token: '{{ csrf_token() }}'
                },
                beforeSend: function () {
                    btn.attr('disabled', true);
                },
                success: function (result) {
                    // Show setup container
                    $('#twoFactorSetupContainer').slideDown();
                    btn.hide();
                    
                    // Display QR code
                    $('#qrCodeContainer').html('<img src="data:image/svg+xml;base64,' + result.data.qr_code + '" alt="QR Code" style="width: 200px; height: 200px;">');
                    
                    // Display secret key
                    $('#secretKeyDisplay').text(result.data.secret);
                    
                    sendSuccess(result.message);
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

        // Cancel Two-Factor Setup
        $('#cancelTwoFactorSetupBtn').on('click', function() {
            $('#twoFactorSetupContainer').slideUp();
            $('#enableTwoFactorBtn').show().attr('disabled', false);
            $('#verification_code').val('');
        });

        // Verify and Confirm Two-Factor Authentication
        $('#verifyTwoFactorForm').on('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            $.ajax({
                url: "{{ route('owner.owner-settings.two-factor.confirm') }}",
                method: "POST",
                dataType: "json",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $('#verifyTwoFactorBtn').attr('disabled', true);
                    $('#verifyTwoFactorSpinner').show();
                },
                success: function (result) {
                    sendSuccess(result.message);
                    
                    // Show recovery codes
                    displayRecoveryCodes(result.data.recovery_codes);
                    
                    // Reload page after showing codes
                    setTimeout(function() {
                        location.reload();
                    }, 5000);
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
                    $('#verifyTwoFactorBtn').attr('disabled', false);
                    $('#verifyTwoFactorSpinner').hide();
                },
            });
        });

        // Disable Two-Factor Authentication
        $('#disableTwoFactorBtn').on('click', function() {
            const password = prompt('Please enter your password to disable two-factor authentication:');
            
            if (!password) {
                return;
            }
            
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('password', password);
            
            $.ajax({
                url: "{{ route('owner.owner-settings.two-factor.disable') }}",
                method: "POST",
                dataType: "json",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $('#disableTwoFactorBtn').attr('disabled', true);
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
                    $('#disableTwoFactorBtn').attr('disabled', false);
                },
            });
        });

        // Show Recovery Codes
        $('#showRecoveryCodesBtn').on('click', function() {
            const password = prompt('Please enter your password to view recovery codes:');
            
            if (!password) {
                return;
            }
            
            // For security, we'll just toggle the display
            // In production, you might want to verify password first
            $('#recoveryCodesContainer').slideToggle();
            
            // Load current recovery codes from server
            // This would require a new endpoint to fetch existing codes
            // For now, we'll show a message
            if ($('#recoveryCodesList').is(':empty')) {
                $('#recoveryCodesList').html('<p class="text-muted">Recovery codes are encrypted and cannot be displayed. Please regenerate new codes if needed.</p>');
            }
        });

        // Regenerate Recovery Codes
        $('#regenerateRecoveryCodesBtn').on('click', function() {
            if (!confirm('Are you sure you want to regenerate recovery codes? Your old codes will no longer work.')) {
                return;
            }
            
            $.ajax({
                url: "{{ route('owner.owner-settings.two-factor.recovery-codes') }}",
                method: "POST",
                dataType: "json",
                data: {
                    _token: '{{ csrf_token() }}'
                },
                beforeSend: function () {
                    $('#regenerateRecoveryCodesBtn').attr('disabled', true);
                },
                success: function (result) {
                    sendSuccess(result.message);
                    displayRecoveryCodes(result.data.recovery_codes);
                    $('#recoveryCodesContainer').slideDown();
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
                    $('#regenerateRecoveryCodesBtn').attr('disabled', false);
                },
            });
        });

        // Copy Recovery Codes
        $('#copyRecoveryCodesBtn').on('click', function() {
            const codes = $('#recoveryCodesList').text();
            navigator.clipboard.writeText(codes).then(function() {
                sendSuccess('Recovery codes copied to clipboard');
            }, function() {
                alert('Failed to copy recovery codes');
            });
        });

        // Display Recovery Codes
        function displayRecoveryCodes(codes) {
            let html = '<div class="row">';
            codes.forEach(function(code, index) {
                html += '<div class="col-md-6 mb-2">' + (index + 1) + '. ' + code + '</div>';
            });
            html += '</div>';
            $('#recoveryCodesList').html(html);
        }

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

        // ========== Account Deletion Handler ==========
        
        $('#deleteAccountBtn').on('click', function() {
            const modalHtml = `
                <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title"><i class="ri-alert-line"></i> Confirm Account Deletion</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-danger">
                                    <strong>Warning!</strong> This action is permanent and cannot be undone.
                                </div>
                                
                                <p class="mb-3">To confirm deletion, please:</p>
                                <ol class="mb-3">
                                    <li>Enter your password</li>
                                    <li>Type <strong>DELETE</strong> in the confirmation field</li>
                                </ol>
                                
                                <form id="deleteAccountForm">
                                    <div class="mb-3">
                                        <label for="delete_password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="delete_password" name="password" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="delete_confirmation" class="form-label">Type DELETE to confirm</label>
                                        <input type="text" class="form-control" id="delete_confirmation" name="confirmation" 
                                               placeholder="DELETE" required>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-danger" id="confirmDeleteBtn" disabled>
                                    <i class="bx bx-loader spinner me-2" style="display: none" id="deleteAccountSpinner"></i>
                                    Delete My Account
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            $('#deleteAccountModal').remove();
            $('body').append(modalHtml);
            
            const modal = new bootstrap.Modal(document.getElementById('deleteAccountModal'));
            modal.show();
            
            $('#delete_confirmation').on('input', function() {
                const confirmText = $(this).val();
                if (confirmText === 'DELETE') {
                    $('#confirmDeleteBtn').prop('disabled', false);
                } else {
                    $('#confirmDeleteBtn').prop('disabled', true);
                }
            });
            
            $('#confirmDeleteBtn').on('click', function() {
                const password = $('#delete_password').val();
                const confirmation = $('#delete_confirmation').val();
                
                if (!password) {
                    alert('Please enter your password');
                    return;
                }
                
                if (confirmation !== 'DELETE') {
                    alert('Please type DELETE to confirm');
                    return;
                }
                
                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('password', password);
                formData.append('confirmation', confirmation);
                
                $.ajax({
                    url: "{{ route('owner.owner-settings.delete-account') }}",
                    method: "POST",
                    dataType: "json",
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function () {
                        $('#confirmDeleteBtn').attr('disabled', true);
                        $('#deleteAccountSpinner').show();
                    },
                    success: function (result) {
                        sendSuccess(result.message);
                        modal.hide();
                        
                        setTimeout(function() {
                            window.location.href = "{{ route('owner.login') }}";
                        }, 2000);
                    },
                    error: function (xhr) {
                        let data = xhr.responseJSON;
                        if (data.hasOwnProperty('message')) {
                            actionError(xhr, data.message);
                        } else {
                            actionError(xhr);
                        }
                        $('#confirmDeleteBtn').attr('disabled', false);
                        $('#deleteAccountSpinner').hide();
                    },
                });
            });
        });
    });
</script>
@endsection
