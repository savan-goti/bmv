@extends('seller.master')
@section('title', 'Seller Settings')
@section('main')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Account Settings</div>

                    <div class="card-body">
                        <form method="POST" id="settingsForm">
                            @csrf

                            <div class="mb-4">
                                <h6 class="mb-3">Security Settings</h6>
                                
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" role="switch" id="two_factor_enabled" 
                                           name="two_factor_enabled" value="1" 
                                           {{ $seller->two_factor_enabled ? 'checked' : '' }}>
                                    <label class="form-check-label" for="two_factor_enabled">
                                        <strong>Two-Factor Authentication</strong>
                                        <p class="text-muted small mb-0">Add an extra layer of security to your account</p>
                                    </label>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h6 class="mb-3">Email Settings</h6>
                                
                                <div class="mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" class="form-control" value="{{ $seller->email }}" disabled>
                                </div>

                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="email_verified" 
                                           name="email_verified" value="1" 
                                           {{ $seller->email_verified_at ? 'checked' : '' }}>
                                    <label class="form-check-label" for="email_verified">
                                        <strong>Email Verified</strong>
                                        <p class="text-muted small mb-0">
                                            @if($seller->email_verified_at)
                                                Verified on {{ $seller->email_verified_at->format('d M Y, h:i A') }}
                                            @else
                                                Email not verified yet
                                            @endif
                                        </p>
                                    </label>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h6 class="mb-3">Account Information</h6>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted small">Account Status</label>
                                        <p class="mb-0">
                                            @if($seller->status == 1)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </p>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted small">Approval Status</label>
                                        <p class="mb-0">
                                            @if($seller->is_approved)
                                                <span class="badge bg-success">Approved</span>
                                            @else
                                                <span class="badge bg-warning">Pending Approval</span>
                                            @endif
                                        </p>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted small">Last Login</label>
                                        <p class="mb-0">
                                            @if($seller->last_login_at)
                                                {{ $seller->last_login_at->format('d M Y, h:i A') }}
                                                <br><small class="text-muted">IP: {{ $seller->last_login_ip }}</small>
                                            @else
                                                Never
                                            @endif
                                        </p>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted small">Account Created</label>
                                        <p class="mb-0">{{ $seller->created_at->format('d M Y') }}</p>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" name="submit" value="submit" class="btn btn-primary" id="settingsUpdateButton">
                                <i class="bx bx-loader spinner me-2" style="display: none" id="settingsUpdateBtnSpinner"></i>
                                Save Settings
                            </button>
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
        $("#settingsForm").submit(function(e) {
            e.preventDefault();
            
            $.ajax({
                url: "{{ route('seller.settings.update') }}",
                method: "post",
                dataType: "json",
                data: new FormData(this),
                processData: false,
                contentType: false,
                cache: false,
                beforeSend: function () {
                    $('#settingsUpdateButton').attr('disabled', true);
                    $("#settingsUpdateBtnSpinner").show();
                },
                success: function (result) {
                    sendSuccess(result.message);
                },
                error: function (xhr) {
                    let data = xhr.responseJSON;
                    if (data.hasOwnProperty('message')) {
                        actionError(xhr, data.message)
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
    });
</script>
@endsection
