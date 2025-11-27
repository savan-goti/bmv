@extends('owner.master')
@section('title','Create Staff')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Create Staff</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-body">
                    <form id="staffCreateForm" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="admin_id" class="form-label">Admin</label>
                            <select class="form-select" id="admin_id" name="admin_id" required>
                                <option value="" selected disabled>Select Admin</option>
                                @foreach($admins as $admin)
                                    <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                                @endforeach
                            </select>
                            <label id="admin_id-error" class="text-danger error" for="admin_id" style="display: none"></label>
                        </div>
                        <div class="mb-3">
                            <label for="profile_image" class="form-label">Profile Image</label>
                            <input type="file" class="form-control" id="profile_image" name="profile_image">
                            <label id="profile_image-error" class="text-danger error" for="profile_image" style="display: none"></label>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <label id="name-error" class="text-danger error" for="name" style="display: none"></label>
                        </div>

                        <div class="mb-3">
                            <label for="father_name" class="form-label">Father Name</label>
                            <input type="text" class="form-control" id="father_name" name="father_name">
                            <label id="father_name-error" class="text-danger error" for="father_name" style="display: none"></label>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <label id="email-error" class="text-danger error" for="email" style="display: none"></label>
                        </div>

                        <div class="mb-3">
                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth">
                            <label id="date_of_birth-error" class="text-danger error" for="date_of_birth" style="display: none"></label>
                        </div>

                        <div class="mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-select" id="gender" name="gender">
                                <option value="" selected disabled>Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                            <label id="gender-error" class="text-danger error" for="gender" style="display: none"></label>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone">
                            <label id="phone-error" class="text-danger error" for="phone" style="display: none"></label>
                        </div>

                        <div class="mb-3">
                            <label for="assigned_role" class="form-label">Assigned Role</label>
                            <select class="form-select" id="assigned_role" name="assigned_role" required>
                                <option value="editor">Editor</option>
                                <option value="viewer">Viewer</option>
                                <option value="support">Support</option>
                            </select>
                            <label id="assigned_role-error" class="text-danger error" for="assigned_role" style="display: none"></label>
                        </div>

                        <div class="mb-3">
                            <label for="education" class="form-label">Education</label>
                            <input type="text" class="form-control" id="education" name="education">
                            <label id="education-error" class="text-danger error" for="education" style="display: none"></label>
                        </div>


                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                            <label id="address-error" class="text-danger error" for="address" style="display: none"></label>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            <label id="status-error" class="text-danger error" for="status" style="display: none"></label>
                        </div>

                        <div class="mb-3">
                            <label for="resignation_date" class="form-label">Resignation Date</label>
                            <input type="date" class="form-control" id="resignation_date" name="resignation_date">
                            <label id="resignation_date-error" class="text-danger error" for="resignation_date" style="display: none"></label>
                        </div>

                        <div class="mb-3">
                            <label for="purpose" class="form-label">Purpose</label>
                            <textarea class="form-control" id="purpose" name="purpose" rows="3"></textarea>
                            <label id="purpose-error" class="text-danger error" for="purpose" style="display: none"></label>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <label id="password-error" class="text-danger error" for="password" style="display: none"></label>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            <label id="password_confirmation-error" class="text-danger error" for="password_confirmation" style="display: none"></label>
                        </div>

                        <button type="submit" class="btn btn-primary" id="staffCreateButton">
                            <i class="bx bx-loader spinner me-2" style="display: none" id="staffCreateBtnSpinner"></i>Create Staff
                        </button>
                        <a href="{{ route('owner.staffs.index') }}" class="btn btn-secondary">Cancel</a>
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
        $("#staffCreateForm").validate({
            rules: {
                name: { required: true },
                admin_id: { required: true },
                email: { required: true, email: true },
                assigned_role: { required: true },
                status: { required: true },
                password: { required: true, minlength: 8 },
                password_confirmation: { required: true, equalTo: "#password" }
            },
            messages: {
                name: { required: "The name field is required" },
                admin_id: { required: "The admin field is required" },
                email: { required: "The email field is required", email: "Please enter a valid email address" },
                assigned_role: { required: "The assigned role field is required" },
                status: { required: "The status field is required" },
                password: { required: "The password field is required", minlength: "Password must be at least 8 characters long" },
                password_confirmation: { required: "The confirm password field is required", equalTo: "Passwords do not match" }
            },
            errorPlacement: function (error, element) {
                element.after(error);
            },
            errorClass: "text-danger",
            submitHandler: function (form, e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('owner.staffs.store') }}",
                    method: "post",
                    dataType: "json",
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: function () {
                        $('#staffCreateButton').attr('disabled', true);
                        $("#staffCreateBtnSpinner").show();
                    },
                    success: function (result) {
                        if(result.status){
                             sendSuccess(result.message);
                             setTimeout(function() {
                                window.location.href = "{{ route('owner.staffs.index') }}";
                             }, 1000);
                        }else{
                             sendError(result.message);
                        }
                    },
                    error: function (xhr) {
                        let data = xhr.responseJSON;
                        if (data.hasOwnProperty('error')) {
                             if (data.error.hasOwnProperty('profile_image')) $("#profile_image-error").html(data.error.profile_image).show();
                             if (data.error.hasOwnProperty('name')) $("#name-error").html(data.error.name).show();
                             if (data.error.hasOwnProperty('father_name')) $("#father_name-error").html(data.error.father_name).show();
                             if (data.error.hasOwnProperty('admin_id')) $("#admin_id-error").html(data.error.admin_id).show();
                             if (data.error.hasOwnProperty('email')) $("#email-error").html(data.error.email).show();
                             if (data.error.hasOwnProperty('date_of_birth')) $("#date_of_birth-error").html(data.error.date_of_birth).show();
                             if (data.error.hasOwnProperty('gender')) $("#gender-error").html(data.error.gender).show();
                             if (data.error.hasOwnProperty('phone')) $("#phone-error").html(data.error.phone).show();
                             if (data.error.hasOwnProperty('assigned_role')) $("#assigned_role-error").html(data.error.assigned_role).show();
                             if (data.error.hasOwnProperty('education')) $("#education-error").html(data.error.education).show();
                             if (data.error.hasOwnProperty('position_id')) $("#position_id-error").html(data.error.position_id).show();
                             if (data.error.hasOwnProperty('address')) $("#address-error").html(data.error.address).show();
                             if (data.error.hasOwnProperty('status')) $("#status-error").html(data.error.status).show();
                             if (data.error.hasOwnProperty('resignation_date')) $("#resignation_date-error").html(data.error.resignation_date).show();
                             if (data.error.hasOwnProperty('purpose')) $("#purpose-error").html(data.error.purpose).show();
                             if (data.error.hasOwnProperty('password')) $("#password-error").html(data.error.password).show();
                        } else if (data.hasOwnProperty('message')) {
                            actionError(xhr, data.message)
                        } else {
                            actionError(xhr);
                        }
                    },
                    complete: function () {
                        $('#staffCreateButton').attr('disabled', false);
                        $("#staffCreateBtnSpinner").hide();
                    },
                });
            }
        });
    });
</script>
@endsection
