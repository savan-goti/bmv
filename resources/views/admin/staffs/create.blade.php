@extends('admin.master')
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
                        
                        <x-input-field 
                            type="file" 
                            name="profile_image" 
                            id="profile_image" 
                            label="Profile Image" 
                        />

                        <x-input-field 
                            name="name" 
                            id="name" 
                            label="Name" 
                            required 
                        />

                        <x-input-field 
                            name="father_name" 
                            id="father_name" 
                            label="Father Name" 
                        />

                        <x-input-field 
                            type="email" 
                            name="email" 
                            id="email" 
                            label="Email" 
                            required 
                        />

                        <x-input-field 
                            type="date" 
                            name="date_of_birth" 
                            id="date_of_birth" 
                            label="Date of Birth" 
                        />

                        <x-input-field 
                            type="select" 
                            name="gender" 
                            id="gender" 
                            label="Gender"
                        >
                            <option value="" selected disabled>Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </x-input-field>

                        <x-input-field 
                            name="phone" 
                            id="phone" 
                            label="Phone" 
                        />

                        <x-input-field 
                            type="select" 
                            name="assigned_role" 
                            id="assigned_role" 
                            label="Assigned Role" 
                            required
                        >
                            <option value="editor">Editor</option>
                            <option value="viewer">Viewer</option>
                            <option value="support">Support</option>
                        </x-input-field>

                        <x-input-field 
                            name="education" 
                            id="education" 
                            label="Education" 
                        />

                        <x-input-field 
                            type="textarea" 
                            name="address" 
                            id="address" 
                            label="Address" 
                            rows="3" 
                        />

                        <x-input-field 
                            type="select" 
                            name="status" 
                            id="status" 
                            label="Status" 
                            required
                        >
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </x-input-field>

                        <x-input-field 
                            type="date" 
                            name="resignation_date" 
                            id="resignation_date" 
                            label="Resignation Date" 
                        />

                        <x-input-field 
                            type="textarea" 
                            name="purpose" 
                            id="purpose" 
                            label="Purpose" 
                            rows="3" 
                        />

                        <x-input-field 
                            type="password" 
                            name="password" 
                            id="password" 
                            label="Password" 
                            required 
                        />

                        <x-input-field 
                            type="password" 
                            name="password_confirmation" 
                            id="password_confirmation" 
                            label="Confirm Password" 
                            required 
                        />

                        <button type="submit" class="btn btn-primary" id="staffCreateButton">
                            <i class="bx bx-loader spinner me-2" style="display: none" id="staffCreateBtnSpinner"></i>Create Staff
                        </button>
                        <a href="{{ route('admin.staffs.index') }}" class="btn btn-secondary">Cancel</a>
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
                email: { required: true, email: true },
                assigned_role: { required: true },
                status: { required: true },
                password: { required: true, minlength: 8 },
                password_confirmation: { required: true, equalTo: "#password" }
            },
            messages: {
                name: { required: "The name field is required" },
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
                    url: "{{ route('admin.staffs.store') }}",
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
                                window.location.href = "{{ route('admin.staffs.index') }}";
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
