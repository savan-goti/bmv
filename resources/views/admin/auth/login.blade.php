<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>
    <meta charset="utf-8" />
    <title>Sign In | Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('admin.layouts.header-links')

</head>

<body>
<div class="auth-page-wrapper pt-5">
    <div class="auth-page-content">
        <div class="container">
            @if(isset($setting->dark_logo))
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center mt-sm-5 mb-4 text-white-50">
                            <div>
                                <a href="#" class="d-inline-block auth-logo">
                                    <img src="{{ $setting->dark_logo ?? asset('assets/img/no_img.jpg') }}" alt="" height="50">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card mt-4">
                        <div class="card-body p-4">
                            <div class="text-center mt-2">
                                <h5 class="text-primary">Welcome Admin!</h5>
                                <p class="text-muted">Sign in to continue to {{env('APP_NAME')}}.</p>
                            </div>
                            <div class="p-2 mt-4">
                                <form id="loginForm" method="post">
                                    @csrf
                                    
                                    <x-input-field 
                                        name="email" 
                                        id="email" 
                                        label="Email ID" 
                                        placeholder="Enter Email ID" 
                                    />

                                    <x-input-field 
                                        type="password" 
                                        name="password" 
                                        id="password" 
                                        label="Password" 
                                        placeholder="Enter Password" 
                                        inputClass="pe-5 password-input"
                                    >
                                        <x-slot:suffix>
                                            <button
                                                class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon material-shadow-none"
                                                type="button" id="password-addon">
                                                <i class="ri-eye-fill align-middle"></i>
                                            </button>
                                        </x-slot:suffix>
                                    </x-input-field>
                                    
                                    <!-- Authentication Method Tabs (Hidden by default, shown when needed) -->
                                    <div class="mb-3" id="authMethodTabs" style="display: none;">
                                        <div class="btn-group w-100" role="group" aria-label="Authentication Method">
                                            <button type="button" class="btn btn-outline-primary auth-method-btn active" data-method="email_verification" id="emailVerificationTab">
                                                Email Verification
                                            </button>
                                            <button type="button" class="btn btn-outline-primary auth-method-btn" data-method="two_factor" id="twoFactorTab">
                                                2FA
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- Two-Factor Authentication Code (Hidden by default) -->
                                    <div class="mb-3" id="twoFactorCodeContainer" style="display: none;">
                                        <div class="alert alert-info mb-3">
                                            <i class="ri-shield-check-line me-2"></i>
                                            <strong>Two-Factor Authentication Required</strong>
                                            <p class="mb-0 small mt-1">Please enter the 6-digit code from your authenticator app or use one of your recovery codes.</p>
                                        </div>
                                        
                                        <x-input-field 
                                            name="two_factor_code" 
                                            id="two_factor_code" 
                                            label="Authentication Code" 
                                            placeholder="Enter 6-digit code or recovery code" 
                                            maxlength="10" 
                                            autocomplete="off"
                                            help-text="Enter the 6-digit code from your authenticator app or use a recovery code (up to 10 characters)."
                                        />
                                    </div>
                                    
                                    <!-- Login Email Verification Code (Hidden by default) -->
                                    <div class="mb-3" id="loginVerificationCodeContainer" style="display: none;">
                                        <div class="alert alert-success mb-3">
                                            <i class="ri-mail-check-line me-2"></i>
                                            <strong>Email Verification Required</strong>
                                            <p class="mb-0 small mt-1">A verification code has been sent to your email. Please check your inbox and enter the code below.</p>
                                        </div>
                                        
                                        <x-input-field 
                                            name="login_verification_code" 
                                            id="login_verification_code" 
                                            label="Verification Code" 
                                            placeholder="Enter 6-digit code" 
                                            maxlength="6" 
                                            autocomplete="off"
                                            help-text="The code will expire in 10 minutes."
                                        />
                                    </div>
                                    
                                    <x-checkbox 
                                        name="remember_me" 
                                        id="auth-remember-check" 
                                        label="Remember Me" 
                                    />

                                    <div class="float-end mt-2">
                                        <a href="{{route('admin.forgot-password')}}" class="text-muted">Forgot password?</a>
                                    </div>
                                    <div class="mt-4">
                                        <button type="submit" class="btn btn-primary w-100" id="loginButton">
                                            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true" style="display: none" id="loginBtnSpinner"></span>
                                            Login
                                        </button>
                                    </div>

                                    <div class="mt-4 text-center">
                                        <div class="signin-other-title">
                                            <h5 class="fs-13 mb-4 title">Or Sign In with</h5>
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.auth.google') }}" class="btn btn-light w-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-google me-2" viewBox="0 0 16 16">
                                                    <path d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z"/>
                                                </svg>
                                                Continue with Google
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- end card body -->
                    </div>

                </div>
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end auth page content -->

    <!-- footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center">
                        <p class="mb-0 text-muted">
                            © <script>document.write(new Date().getFullYear())</script> Admin Panel. Crafted with <i class="mdi mdi-heart text-danger"></i> by KeMedia SRL.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- end Footer -->
</div>
<!-- end auth-page-wrapper -->

<style>
    .signin-other-title {
        position: relative;
    }
    
    .signin-other-title .title {
        position: relative;
        display: inline-block;
        padding: 0 15px;
        background: white;
        z-index: 1;
    }
    
    .signin-other-title::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background: #e9ecef;
        z-index: 0;
    }
</style>

@include('admin.layouts.footer-links')
@include('admin.layouts.common-js')

<script>

    $(document).ready(function(){
        $("#loginForm").validate({
            rules: {
                email: {
                    required: true,
                    email: true,
                },
                password: {
                    required: true,
                }
            },
            messages: {
                email: {
                    required: "Email is required",
                    email: "The email must be a valid email address",
                },
                password: {
                    required: "The password field is required",
                }
            },
            errorPlacement: function (error, element) {
                element.after(error);
            },
            errorClass: "text-danger",
            submitHandler: function (form, e) {
                e.preventDefault();
                $.ajax({
                    url: "{{route('admin.authenticate')}}",
                    method: "post",
                    dataType: "json",
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: function () {
                        $('#loginButton').attr('disabled', true);
                        $("#loginBtnSpinner").show();
                    },
                    success: function (result) {
                        // Check if login email verification is required
                        if (result.data && result.data.requires_login_verification) {
                            // Show tabs only if both methods are available
                            if (result.data.has_both_methods) {
                                $('#authMethodTabs').show();
                            } else {
                                $('#authMethodTabs').hide();
                            }
                            
                            $('#loginVerificationCodeContainer').show();
                            $('#twoFactorCodeContainer').hide();
                            $('#login_verification_code').val('').focus();
                            
                            // Set active tab
                            $('.auth-method-btn').removeClass('active');
                            $('#emailVerificationTab').addClass('active');
                            
                            // Scroll to the verification field
                            var scrollTarget = result.data.has_both_methods ? $('#authMethodTabs') : $('#loginVerificationCodeContainer');
                            $('html, body').animate({
                                scrollTop: scrollTarget.offset().top - 100
                            }, 300);
                            
                            sendSuccess('Verification code sent to your email. Please check your inbox.');
                        }
                        // Check if 2FA is required
                        else if (result.data && result.data.requires_2fa) {
                            // Show tabs only if both methods are available
                            if (result.data.has_both_methods) {
                                $('#authMethodTabs').show();
                            } else {
                                $('#authMethodTabs').hide();
                            }
                            
                            $('#twoFactorCodeContainer').show();
                            $('#loginVerificationCodeContainer').hide();
                            $('#two_factor_code').val('').focus();
                            
                            // Set active tab
                            $('.auth-method-btn').removeClass('active');
                            $('#twoFactorTab').addClass('active');
                            
                            // Scroll to the 2FA field
                            var scrollTarget = result.data.has_both_methods ? $('#authMethodTabs') : $('#twoFactorCodeContainer');
                            $('html, body').animate({
                                scrollTop: scrollTarget.offset().top - 100
                            }, 300);
                            
                            sendSuccess('Please enter your two-factor authentication code');
                        } else {
                            // Login successful
                            sendSuccess(result.message);
                            setTimeout(function () {
                                window.location.href = "{{route('admin.dashboard')}}";
                            }, 1000);
                        }
                    },
                    error: function (xhr) {
                        let data = xhr.responseJSON;
                        if (data.hasOwnProperty('error')) {
                            if (data.error.hasOwnProperty('email')) {
                                $("#email-error").html(data.error.email).show();
                            }
                            if (data.error.hasOwnProperty('password')) {
                                $("#password-error").html(data.error.password).show();
                            }
                            if (data.error.hasOwnProperty('login_verification_code')) {
                                $("#login_verification_code-error").html(data.error.login_verification_code).show();
                            }
                            if (data.error.hasOwnProperty('two_factor_code')) {
                                $("#two_factor_code-error").html(data.error.two_factor_code).show();
                            }
                        } else if (data.hasOwnProperty('message')) {
                            actionError(xhr, data.message)
                        } else {
                            actionError(xhr);
                        }
                    },
                    complete: function () {
                        $('#loginButton').attr('disabled', false);
                        $("#loginBtnSpinner").hide();
                    },
                });
            }
        });

        // Handle authentication method tab switching
        $('.auth-method-btn').on('click', function() {
            const method = $(this).data('method');
            
            // Update active tab
            $('.auth-method-btn').removeClass('active');
            $(this).addClass('active');
            
            // Show/hide appropriate containers
            if (method === 'email_verification') {
                $('#loginVerificationCodeContainer').show();
                $('#twoFactorCodeContainer').hide();
                $('#login_verification_code').focus();
            } else if (method === 'two_factor') {
                $('#twoFactorCodeContainer').show();
                $('#loginVerificationCodeContainer').hide();
                $('#two_factor_code').focus();
            }
        });
    });
</script>
</body>
</html>
