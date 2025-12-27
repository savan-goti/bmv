<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>
    <meta charset="utf-8" />
    <title>Reset Password | Owner</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('owner.layouts.header-links')

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
                                <h5 class="text-primary">Create New Password</h5>
                                <p class="text-muted">Your new password must be different from previous passwords</p>
                            </div>

                            <div class="p-2 mt-4">
                                <form id="resetPasswordForm" method="post">
                                    @csrf
                                    <input type="hidden" name="token" value="{{ $token }}">
                                    
                                    <x-input-field 
                                        type="email" 
                                        name="email" 
                                        label="Email Address" 
                                        placeholder="Enter your email address" 
                                        value="{{ $email ?? '' }}" 
                                        readonly 
                                    />

                                    <x-input-field 
                                        type="password" 
                                        name="password" 
                                        label="New Password" 
                                        placeholder="Enter new password" 
                                        inputClass="form-control pe-5 password-input"
                                        helpText="Must be at least 8 characters."
                                        required
                                    >
                                        <x-slot name="suffix">
                                            <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon material-shadow-none" type="button" id="password-addon">
                                                <i class="ri-eye-fill align-middle"></i>
                                            </button>
                                        </x-slot>
                                    </x-input-field>

                                    <x-input-field 
                                        type="password" 
                                        name="password_confirmation" 
                                        label="Confirm Password" 
                                        placeholder="Confirm password" 
                                        inputClass="form-control pe-5 password-input"
                                        required
                                    >
                                        <x-slot name="suffix">
                                            <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon material-shadow-none" type="button" id="password-confirmation-addon">
                                                <i class="ri-eye-fill align-middle"></i>
                                            </button>
                                        </x-slot>
                                    </x-input-field>

                                    <div class="mt-4">
                                        <button class="btn btn-primary w-100" type="submit" id="submitButton">
                                            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true" style="display: none" id="submitSpinner"></span>
                                            Reset Password
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- end card body -->
                    </div>
                    <!-- end card -->

                    <div class="mt-4 text-center">
                        <p class="mb-0">Wait, I remember my password... <a href="{{route('owner.login')}}" class="fw-semibold text-primary text-decoration-underline"> Click here </a> </p>
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
                            © <script>document.write(new Date().getFullYear())</script> Owner Panel. Crafted with <i class="mdi mdi-heart text-danger"></i> by KeMedia SRL.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- end Footer -->
</div>
<!-- end auth-page-wrapper -->

@include('owner.layouts.footer-links')
@include('owner.layouts.common-js')

<script>
    $(document).ready(function(){
        $("#resetPasswordForm").validate({
            rules: {
                email: {
                    required: true,
                    email: true,
                },
                password: {
                    required: true,
                    minlength: 8
                },
                password_confirmation: {
                    required: true,
                    equalTo: "#password"
                }
            },
            messages: {
                email: {
                    required: "Email is required",
                    email: "Please enter a valid email address",
                },
                password: {
                    required: "Password is required",
                    minlength: "Password must be at least 8 characters"
                },
                password_confirmation: {
                    required: "Please confirm your password",
                    equalTo: "Passwords do not match"
                }
            },
            errorPlacement: function (error, element) {
                element.after(error);
            },
            errorClass: "text-danger",
            submitHandler: function (form, e) {
                e.preventDefault();
                $.ajax({
                    url: "{{route('owner.reset-password.update')}}",
                    method: "post",
                    dataType: "json",
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: function () {
                        $('#submitButton').attr('disabled', true);
                        $("#submitSpinner").show();
                    },
                    success: function (result) {
                        sendSuccess(result.message);
                        setTimeout(function () {
                            window.location.href = "{{route('owner.login')}}";
                        }, 2000);
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
                            if (data.error.hasOwnProperty('password_confirmation')) {
                                $("#password_confirmation-error").html(data.error.password_confirmation).show();
                            }
                        } else if (data.hasOwnProperty('message')) {
                            sendError(data.message);
                        } else {
                            sendError('An error occurred. Please try again.');
                        }
                    },
                    complete: function () {
                        $('#submitButton').attr('disabled', false);
                        $("#submitSpinner").hide();
                    },
                });
            }
        });

        // Additional password addon for confirmation field
        document.getElementById('password-confirmation-addon').addEventListener('click', function() {
            var passwordInput = document.getElementById('password_confirmation');
            var icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('ri-eye-fill');
                icon.classList.add('ri-eye-off-fill');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('ri-eye-off-fill');
                icon.classList.add('ri-eye-fill');
            }
        });
    });
</script>
</body>
</html>
