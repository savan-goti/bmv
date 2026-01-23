<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>
    <meta charset="utf-8" />
    <title>Register | Seller</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('seller.layouts.header-links')

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
                <div class="col-md-10 col-lg-8 col-xl-7">
                    <div class="card mt-4">
                        <div class="card-body p-4">
                            <div class="text-center mt-2">
                                <h5 class="text-primary">Create Seller Account</h5>
                                <p class="text-muted">Get your free seller account now</p>
                            </div>
                            <div class="p-2 mt-4">
                                <form id="registerForm" method="post">
                                    @csrf
                                    
                                    <div class="row">
                                        <!-- Business Information -->
                                        <div class="col-12">
                                            <h6 class="text-muted mb-3">Business Information</h6>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <x-input-field 
                                                name="business_name" 
                                                id="business_name" 
                                                label="Business Name" 
                                                placeholder="Enter Business Name" 
                                                required="true"
                                            />
                                        </div>

                                        <div class="col-md-6">
                                            <x-input-field 
                                                name="business_type" 
                                                id="business_type" 
                                                label="Business Type" 
                                                placeholder="e.g., Retail, Wholesale, Service" 
                                            />
                                        </div>

                                        <!-- Owner Information -->
                                        <div class="col-12 mt-3">
                                            <h6 class="text-muted mb-3">Owner Information</h6>
                                        </div>

                                        <div class="col-md-6">
                                            <x-input-field 
                                                name="owner_name" 
                                                id="owner_name" 
                                                label="Owner Name" 
                                                placeholder="Enter Owner Name" 
                                                required="true"
                                            />
                                        </div>

                                        <div class="col-md-6">
                                            <x-input-field 
                                                name="email" 
                                                id="email" 
                                                label="Email Address" 
                                                placeholder="Enter Email Address" 
                                                required="true"
                                            />
                                        </div>

                                        <div class="col-md-6">
                                            <x-input-field 
                                                name="phone" 
                                                id="phone" 
                                                label="Phone Number" 
                                                placeholder="Enter Phone Number" 
                                                required="true"
                                            />
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="gender" class="form-label">Gender</label>
                                                <select class="form-select" name="gender" id="gender">
                                                    <option value="">Select Gender</option>
                                                    <option value="male">Male</option>
                                                    <option value="female">Female</option>
                                                    <option value="other">Other</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Address Information -->
                                        <div class="col-12 mt-3">
                                            <h6 class="text-muted mb-3">Address Information</h6>
                                        </div>

                                        <div class="col-12">
                                            <x-input-field 
                                                name="address" 
                                                id="address" 
                                                label="Address" 
                                                placeholder="Enter Full Address" 
                                            />
                                        </div>

                                        <div class="col-md-6">
                                            <x-input-field 
                                                name="city" 
                                                id="city" 
                                                label="City" 
                                                placeholder="Enter City" 
                                            />
                                        </div>

                                        <div class="col-md-6">
                                            <x-input-field 
                                                name="state" 
                                                id="state" 
                                                label="State" 
                                                placeholder="Enter State" 
                                            />
                                        </div>

                                        <div class="col-md-6">
                                            <x-input-field 
                                                name="country" 
                                                id="country" 
                                                label="Country" 
                                                placeholder="Enter Country" 
                                            />
                                        </div>

                                        <div class="col-md-6">
                                            <x-input-field 
                                                name="pincode" 
                                                id="pincode" 
                                                label="Pincode" 
                                                placeholder="Enter Pincode" 
                                            />
                                        </div>

                                        <!-- Password -->
                                        <div class="col-12 mt-3">
                                            <h6 class="text-muted mb-3">Security</h6>
                                        </div>

                                        <div class="col-md-6">
                                            <x-input-field 
                                                type="password" 
                                                name="password" 
                                                id="password" 
                                                label="Password" 
                                                placeholder="Enter Password" 
                                                inputClass="form-control pe-5 password-input"
                                                required="true"
                                            >
                                                <x-slot:suffix>
                                                    <button
                                                        class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon material-shadow-none"
                                                        type="button" id="password-addon">
                                                        <i class="ri-eye-fill align-middle"></i>
                                                    </button>
                                                </x-slot:suffix>
                                            </x-input-field>
                                        </div>

                                        <div class="col-md-6">
                                            <x-input-field 
                                                type="password" 
                                                name="password_confirmation" 
                                                id="password_confirmation" 
                                                label="Confirm Password" 
                                                placeholder="Confirm Password" 
                                                inputClass="form-control pe-5 password-input"
                                                required="true"
                                            >
                                                <x-slot:suffix>
                                                    <button
                                                        class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon material-shadow-none"
                                                        type="button" id="password-confirmation-addon">
                                                        <i class="ri-eye-fill align-middle"></i>
                                                    </button>
                                                </x-slot:suffix>
                                            </x-input-field>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                                                <label class="form-check-label" for="terms">
                                                    I agree to the <a href="#" class="text-primary">Terms and Conditions</a>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <button type="submit" class="btn btn-primary w-100" id="registerButton">
                                            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true" style="display: none" id="registerBtnSpinner"></span>
                                            Register
                                        </button>
                                    </div>

                                    <div class="mt-4 text-center">
                                        <div class="signin-other-title">
                                            <h5 class="fs-13 mb-4 title">Or Sign Up with</h5>
                                        </div>
                                        <div>
                                            <a href="{{ route('seller.auth.google') }}" class="btn btn-light w-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-google me-2" viewBox="0 0 16 16">
                                                    <path d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z"/>
                                                </svg>
                                                Continue with Google
                                            </a>
                                        </div>
                                    </div>

                                    <div class="mt-4 text-center">
                                        <p class="mb-0">Already have an account? <a href="{{route('seller.login')}}" class="fw-semibold text-primary text-decoration-underline">Sign in</a></p>
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
                            © <script>document.write(new Date().getFullYear())</script> Seller Panel. Crafted with <i class="mdi mdi-heart text-danger"></i> by KeMedia SRL.
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

@include('seller.layouts.footer-links')
@include('seller.layouts.common-js')

<script>

    $(document).ready(function(){
        // Password visibility toggle
        $('#password-addon').on('click', function() {
            var passwordInput = $('#password');
            var icon = $(this).find('i');
            
            if (passwordInput.attr('type') === 'password') {
                passwordInput.attr('type', 'text');
                icon.removeClass('ri-eye-fill').addClass('ri-eye-off-fill');
            } else {
                passwordInput.attr('type', 'password');
                icon.removeClass('ri-eye-off-fill').addClass('ri-eye-fill');
            }
        });

        // Password confirmation visibility toggle
        $('#password-confirmation-addon').on('click', function() {
            var passwordInput = $('#password_confirmation');
            var icon = $(this).find('i');
            
            if (passwordInput.attr('type') === 'password') {
                passwordInput.attr('type', 'text');
                icon.removeClass('ri-eye-fill').addClass('ri-eye-off-fill');
            } else {
                passwordInput.attr('type', 'password');
                icon.removeClass('ri-eye-off-fill').addClass('ri-eye-fill');
            }
        });

        $("#registerForm").validate({
            rules: {
                business_name: {
                    required: true,
                },
                owner_name: {
                    required: true,
                },
                email: {
                    required: true,
                    email: true,
                },
                phone: {
                    required: true,
                },
                password: {
                    required: true,
                    minlength: 8,
                },
                password_confirmation: {
                    required: true,
                    equalTo: "#password"
                },
                terms: {
                    required: true,
                }
            },
            messages: {
                business_name: {
                    required: "Business name is required",
                },
                owner_name: {
                    required: "Owner name is required",
                },
                email: {
                    required: "Email is required",
                    email: "The email must be a valid email address",
                },
                phone: {
                    required: "Phone number is required",
                },
                password: {
                    required: "Password is required",
                    minlength: "Password must be at least 8 characters",
                },
                password_confirmation: {
                    required: "Please confirm your password",
                    equalTo: "Passwords do not match"
                },
                terms: {
                    required: "You must agree to the terms and conditions",
                }
            },
            errorPlacement: function (error, element) {
                if (element.attr("name") === "terms") {
                    error.insertAfter(element.parent());
                } else {
                    element.after(error);
                }
            },
            errorClass: "text-danger",
            submitHandler: function (form, e) {
                e.preventDefault();
                $.ajax({
                    url: "{{route('seller.register.submit')}}",
                    method: "post",
                    dataType: "json",
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: function () {
                        $('#registerButton').attr('disabled', true);
                        $("#registerBtnSpinner").show();
                    },
                    success: function (result) {
                        sendSuccess(result.message);
                        setTimeout(function () {
                            window.location.href = "{{route('seller.login')}}";
                        }, 2000);
                    },
                    error: function (xhr) {
                        let data = xhr.responseJSON;
                        if (data.hasOwnProperty('error')) {
                            // Display validation errors
                            $.each(data.error, function(field, messages) {
                                $("#" + field + "-error").html(messages).show();
                            });
                        } else if (data.hasOwnProperty('message')) {
                            actionError(xhr, data.message)
                        } else {
                            actionError(xhr);
                        }
                    },
                    complete: function () {
                        $('#registerButton').attr('disabled', false);
                        $("#registerBtnSpinner").hide();
                    },
                });
            }
        });
    });

</script>
</body>
</html>
