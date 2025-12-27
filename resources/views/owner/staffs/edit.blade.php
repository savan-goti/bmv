@extends('owner.master')
@section('title','Edit Staff')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Edit Staff</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-body">
                    <form id="staffEditForm" method="POST">
                        @csrf
                        @method('PUT')
                        <x-input-field 
                            type="select" 
                            name="admin_id" 
                            label="Admin" 
                            placeholder="Select Admin"
                            required
                        >
                            @foreach($admins as $admin)
                                <option value="{{ $admin->id }}" {{ $staff->admin_id == $admin->id ? 'selected' : '' }}>{{ $admin->name }}</option>
                            @endforeach
                        </x-input-field>
                        
                        <div class="mb-3">
                            <x-input-field 
                                type="file" 
                                name="profile_image" 
                                label="Profile Image" 
                            />
                            @if($staff->profile_image)
                                <img src="{{ asset($staff->profile_image) }}" alt="Profile Image" width="100" class="mt-2">
                            @endif
                        </div>

                        <x-input-field name="name" label="Name" placeholder="Enter staff name" value="{{ $staff->name }}" required />
                        <x-input-field name="father_name" label="Father Name" placeholder="Enter father name" value="{{ $staff->father_name }}" />
                        <x-input-field type="email" name="email" label="Email" placeholder="Enter email" value="{{ $staff->email }}" required />
                        <x-input-field type="date" name="date_of_birth" label="Date of Birth" value="{{ $staff->date_of_birth }}" />

                        <x-input-field type="select" name="gender" label="Gender" placeholder="Select Gender">
                            <option value="male" {{ $staff->gender == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ $staff->gender == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ $staff->gender == 'other' ? 'selected' : '' }}>Other</option>
                        </x-input-field>

                        <x-input-field name="phone" label="Phone" placeholder="Enter phone" value="{{ $staff->phone }}" />

                        <x-input-field type="select" name="assigned_role" label="Assigned Role" required>
                            <option value="editor" {{ $staff->assigned_role == 'editor' ? 'selected' : '' }}>Editor</option>
                            <option value="viewer" {{ $staff->assigned_role == 'viewer' ? 'selected' : '' }}>Viewer</option>
                            <option value="support" {{ $staff->assigned_role == 'support' ? 'selected' : '' }}>Support</option>
                        </x-input-field>

                        <x-input-field name="education" label="Education" placeholder="Enter education" value="{{ $staff->education }}" />
                        <x-input-field type="textarea" name="address" label="Address" placeholder="Enter address" value="{{ $staff->address }}" rows="3" />

                        <x-input-field type="select" name="status" label="Status" required>
                            <option value="active" {{ $staff->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $staff->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </x-input-field>

                        <x-input-field type="date" name="resignation_date" label="Resignation Date" value="{{ $staff->resignation_date }}" />
                        <x-input-field type="textarea" name="purpose" label="Purpose" placeholder="Enter purpose" value="{{ $staff->purpose }}" rows="3" />
                        <x-input-field type="password" name="password" label="Password (Leave blank to keep current)" placeholder="Enter new password" />
                        <x-input-field type="password" name="password_confirmation" label="Confirm Password" placeholder="Confirm new password" />

                        <button type="submit" class="btn btn-primary" id="staffEditButton">
                            <i class="bx bx-loader spinner me-2" style="display: none" id="staffEditBtnSpinner"></i>Update Staff
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
        $("#staffEditForm").validate({
            rules: {
                name: { required: true },
                admin_id: { required: true },
                email: { required: true, email: true },
                assigned_role: { required: true },
                status: { required: true },
                password: { minlength: 8 },
                password_confirmation: { equalTo: "#password" }
            },
            messages: {
                name: { required: "The name field is required" },
                admin_id: { required: "The admin field is required" },
                email: { required: "The email field is required", email: "Please enter a valid email address" },
                assigned_role: { required: "The assigned role field is required" },
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
                    url: "{{ route('owner.staffs.update', $staff->id) }}",
                    method: "post",
                    dataType: "json",
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: function () {
                        $('#staffEditButton').attr('disabled', true);
                        $("#staffEditBtnSpinner").show();
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
                        $('#staffEditButton').attr('disabled', false);
                        $("#staffEditBtnSpinner").hide();
                    },
                });
            }
        });
    });
</script>
@endsection
