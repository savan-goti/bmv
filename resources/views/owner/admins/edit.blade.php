@extends('owner.master')
@section('title','Edit Admin')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Edit Admin</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-body">
                    <form id="adminEditForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <x-input-field 
                                type="file" 
                                name="profile_image" 
                                label="Profile Image" 
                            />
                            @if($admin->profile_image)
                                <img src="{{ asset($admin->profile_image) }}" alt="Profile Image" width="100" class="mt-2">
                            @endif
                        </div>

                        <x-input-field 
                            name="name" 
                            label="Name" 
                            placeholder="Enter admin name"
                            value="{{ $admin->name }}"
                            required 
                        />

                        <x-input-field 
                            name="father_name" 
                            label="Father Name" 
                            placeholder="Enter father name"
                            value="{{ $admin->father_name }}"
                        />

                        <x-input-field 
                            type="email"
                            name="email" 
                            label="Email" 
                            placeholder="Enter email address"
                            value="{{ $admin->email }}"
                            required 
                        />

                        <x-input-field 
                            type="date"
                            name="date_of_birth" 
                            label="Date of Birth" 
                            value="{{ $admin->date_of_birth }}"
                        />

                        <x-input-field 
                            type="select" 
                            name="gender" 
                            label="Gender" 
                            placeholder="Select Gender"
                        >
                            <option value="male" {{ $admin->gender == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ $admin->gender == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ $admin->gender == 'other' ? 'selected' : '' }}>Other</option>
                        </x-input-field>

                        <x-input-field 
                            name="phone" 
                            label="Phone" 
                            placeholder="Enter phone number"
                            value="{{ $admin->phone }}"
                        />

                        <x-input-field 
                            type="select" 
                            name="role" 
                            label="Role" 
                            required
                        >
                            <option value="admin" {{ $admin->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="manager" {{ $admin->role == 'manager' ? 'selected' : '' }}>Manager</option>
                            <option value="super_admin" {{ $admin->role == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                        </x-input-field>

                        <x-input-field 
                            name="education" 
                            label="Education" 
                            placeholder="Enter education details"
                            value="{{ $admin->education }}"
                        />

                        <x-input-field 
                            type="textarea"
                            name="address" 
                            label="Address" 
                            placeholder="Enter address"
                            value="{{ $admin->address }}"
                            rows="3"
                        />

                        <x-input-field 
                            type="select" 
                            name="status" 
                            label="Status" 
                            required
                        >
                            <option value="active" {{ $admin->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $admin->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </x-input-field>

                        <x-input-field 
                            type="date"
                            name="resignation_date" 
                            label="Resignation Date" 
                            value="{{ $admin->resignation_date }}"
                        />

                        <x-input-field 
                            type="textarea"
                            name="purpose" 
                            label="Purpose" 
                            placeholder="Enter purpose"
                            value="{{ $admin->purpose }}"
                            rows="3"
                        />

                        <x-input-field 
                            type="password"
                            name="password" 
                            label="Password (Leave blank to keep current)" 
                            placeholder="Enter new password"
                        />

                        <x-input-field 
                            type="password"
                            name="password_confirmation" 
                            label="Confirm Password" 
                            placeholder="Confirm new password"
                        />

                        <button type="submit" class="btn btn-primary" id="adminEditButton">
                            <i class="bx bx-loader spinner me-2" style="display: none" id="adminEditBtnSpinner"></i>Update Admin
                        </button>
                        <a href="{{ route('owner.admins.index') }}" class="btn btn-secondary">Cancel</a>
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
        $("#adminEditForm").validate({
            rules: {
                name: { required: true },
                email: { required: true, email: true },
                role: { required: true },
                status: { required: true },
                password: { minlength: 8 },
                password_confirmation: { equalTo: "#password" }
            },
            messages: {
                name: { required: "The name field is required" },
                email: { required: "The email field is required", email: "Please enter a valid email address" },
                role: { required: "The role field is required" },
                status: { required: "The status field is required" },
                password: { minlength: "Password must be at least 8 characters long" },
                password_confirmation: { equalTo: "Passwords do not match" }
            },
            errorPlacement: function (error, element) {
                element.after(error);
            },
            errorClass: "text-danger",
            submitHandler: function (form, e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('owner.admins.update', $admin->id) }}",
                    method: "post",
                    dataType: "json",
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: function () {
                        $('#adminEditButton').attr('disabled', true);
                        $("#adminEditBtnSpinner").show();
                    },
                    success: function (result) {
                        if(result.status){
                            sendSuccess(result.message);
                            setTimeout(function() {
                            window.location.href = "{{ route('owner.admins.index') }}";
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
                             if (data.error.hasOwnProperty('role')) $("#role-error").html(data.error.role).show();
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
                        $('#adminEditButton').attr('disabled', false);
                        $("#adminEditBtnSpinner").hide();
                    },
                });
            }
        });
    });
</script>
@endsection
