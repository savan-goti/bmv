<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>
    <meta charset="utf-8" />
    <title>Sign In | Owner</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ \App\Models\Setting::first()->favicon ?? asset('favicon.ico') }}" type="image/x-icon" />

    <!-- jquery JS -->
    <script src="{{asset('assets/js/jquery-3.6.0.js')}}"></script>
    <script src="{{asset('assets/js/jquery.validate.js')}}"></script>

    <!-- Layout config Js -->
    <script src="{{asset('assets/admin/js/layout.js')}}"></script>
    <!-- Bootstrap Css -->
    <link href="{{asset('assets/admin/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>
    <!-- Icons Css -->
    <link href="{{asset('assets/admin/css/icons.min.css')}}" rel="stylesheet" type="text/css"/>
    <!-- App Css-->
    <link href="{{asset('assets/admin/css/app.min.css')}}" rel="stylesheet" type="text/css"/>
    <!-- custom Css-->
    <link href="{{asset('assets/admin/css/custom.min.css')}}" rel="stylesheet" type="text/css"/>

    <link href="{{asset('assets/admin/libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />

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
                                <h5 class="text-primary">Welcome Back!</h5>
                                <p class="text-muted">Sign in to continue to {{env('APP_NAME')}}.</p>
                            </div>
                            <div class="p-2 mt-4">
                                <form id="loginForm" method="post">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email ID</label>
                                        <input type="text" class="form-control" id="email" placeholder="Enter Email ID"
                                               name="email">
                                        <label id="email-error" class="text-danger error" for="email" style="display: none"></label>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="password">Password</label>
                                        <div class="position-relative auth-pass-inputgroup">
                                            <input type="password" class="form-control pe-5 password-input"
                                                   name="password" placeholder="Enter Password" id="password">
                                            <label id="password-error" class="text-danger error" for="password" style="display: none"></label>
                                            <button
                                                class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon material-shadow-none"
                                                type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                                        </div>
                                    </div>
                                    
                                    <!-- Two-Factor Authentication Code (Hidden by default) -->
                                    <div class="mb-3" id="twoFactorCodeContainer" style="display: none;">
                                        <div class="alert alert-info mb-3">
                                            <i class="ri-shield-check-line me-2"></i>
                                            <strong>Two-Factor Authentication Required</strong>
                                            <p class="mb-0 small mt-1">Please enter the 6-digit code from your authenticator app or use one of your recovery codes.</p>
                                        </div>
                                        <label for="two_factor_code" class="form-label">Authentication Code</label>
                                        <input type="text" class="form-control" id="two_factor_code" 
                                               placeholder="Enter 6-digit code or recovery code" name="two_factor_code" maxlength="10" autocomplete="off">
                                        <small class="text-muted d-block mt-1">Enter the code from your authenticator app or use a recovery code.</small>
                                        <label id="two_factor_code-error" class="text-danger error" for="two_factor_code" style="display: none"></label>
                                    </div>
                                    
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" name="remember_me"
                                               id="auth-remember-check">
                                        <label class="form-check-label" for="auth-remember-check">Remember Me</label>
                                    </div>

                                    <div class="float-end mt-2">
                                        <a href="{{route('owner.forgot-password')}}" class="text-muted">Forgot password?</a>
                                    </div>
                                    <div class="mt-4">
                                        <button type="submit" class="btn btn-primary w-100" id="loginButton">
                                            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true" style="display: none" id="loginBtnSpinner"></span>
                                            Login
                                        </button>
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

<!-- JAVASCRIPT -->
<script src="{{asset('assets/admin/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/admin/libs/simplebar/simplebar.min.js')}}"></script>
<script src="{{asset('assets/admin/libs/node-waves/waves.min.js')}}"></script>
<script src="{{asset('assets/admin/libs/feather-icons/feather.min.js')}}"></script>
<script src="{{asset('assets/admin/js/pages/plugins/lord-icon-2.1.0.js')}}"></script>
<script src="{{asset('assets/admin/js/plugins.js')}}"></script>

<script src="{{asset('assets/admin/libs/sweetalert2/sweetalert2.min.js')}}"></script>

<!-- password-addon init -->
<script src="{{asset('assets/admin/js/pages/password-addon.init.js')}}"></script>

<script>
    // Success Swal
    function sendSuccess(message) {
        Swal.fire({
            title: "Success",
            text: message,
            icon: "success",
            confirmButtonClass: "btn btn-primary w-md mt-3",
            showCancelButton: false,
            showConfirmButton: true,
            timer: 3000,
        });
    }

    // Error Swal
    function sendError(message) {
        Swal.fire({
            title: "Error",
            text: message,
            icon: "error",
            showCancelButton: false,
            showConfirmButton: true,
            timer: 4000,
        });
    }

    // Handle Ajax error
    function actionError(xhr, message = "Bad Request") {
        if (xhr.status == 400) {
            sendError(message);
        } else if (xhr.status === 401) {
            sendError("Unauthorized");
            setTimeout(function () {
                window.location.href = "{{route('owner.login')}}"
            }, 1500);
        } else if (xhr.status === 403) {
            sendError("Forbidden");
        } else if (xhr.status === 500) {
            sendError("Internal Server Error");
        }
    }

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
                    url: "{{route('owner.authenticate')}}",
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
                        // Check if 2FA is required
                        if (result.data && result.data.requires_2fa) {
                            // Show 2FA input field
                            $('#twoFactorCodeContainer').show();
                            $('#two_factor_code').val('').focus();
                            
                            // Scroll to the 2FA field
                            $('html, body').animate({
                                scrollTop: $('#twoFactorCodeContainer').offset().top - 100
                            }, 300);
                            
                            sendSuccess('Please enter your two-factor authentication code');
                        } else {
                            // Login successful
                            sendSuccess(result.message);
                            setTimeout(function () {
                                window.location.href = "{{route('owner.dashboard')}}";
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
    });
</script>
</body>
</html>
