<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>
    <meta charset="utf-8" />
    <title>Sign In | Staff</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('staff.layouts.header-links')

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
                                <h5 class="text-primary">Welcome Staff!</h5>
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
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" name="remember_me"
                                               id="auth-remember-check">
                                        <label class="form-check-label" for="auth-remember-check">Remember Me</label>
                                    </div>

                                    <div class="float-end mt-2">
                                        <a href="{{route('staff.forgot-password')}}" class="text-muted">Forgot password?</a>
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
                            © <script>document.write(new Date().getFullYear())</script> Staff Panel. Crafted with <i class="mdi mdi-heart text-danger"></i> by KeMedia SRL.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- end Footer -->
</div>
<!-- end auth-page-wrapper -->

@include('staff.layouts.footer-links')
@include('staff.layouts.common-js')

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
                    url: "{{route('staff.authenticate')}}",
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
                        sendSuccess(result.message);
                        setTimeout(function () {
                            window.location.href = "{{route('staff.dashboard')}}";
                        }, 1000);
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
