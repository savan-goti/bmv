@extends('owner.master')
@section('title', isset($admin) ? 'Edit Admin' : 'Create Admin')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">{{ isset($admin) ? 'Edit Admin' : 'Create Admin' }}</h4>
                <div class="page-title-right">
                    <a href="{{ route('owner.admins.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="admin-form">
                        @csrf
                        @if(!isset($admin))
                            <input type="hidden" name="owner_id" value="{{ Auth::user()->id }}">
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <x-input-field 
                                    type="file" 
                                    name="profile_image" 
                                    label="Profile Image" 
                                />
                                @if(isset($admin) && $admin->profile_image)
                                    <img src="{{ asset($admin->profile_image) }}" alt="Profile Image" width="100" class="mt-2">
                                @endif
                            </div>

                            <div class="col-md-6">
                                <x-input-field 
                                    name="name" 
                                    label="Name" 
                                    placeholder="Enter admin name"
                                    value="{{ old('name', $admin->name ?? '') }}"
                                    required 
                                />
                            </div>

                            <div class="col-md-6">
                               <x-input-field 
                                    type="select" 
                                    name="position_id" 
                                    label="Branch & Job Position" 
                                    required
                                >
                                    <option value="">-- Select Branch - Position --</option>
                                    @foreach($branch_positions as $bp)
                                        <option value="{{ $bp->id }}" 
                                            {{ (isset($admin) && $admin->position_id == $bp->id) ? 'selected' : '' }}>
                                            {{ $bp->branch->name ?? 'N/A' }} - {{ $bp->jobPosition->name ?? 'N/A' }}
                                        </option>
                                    @endforeach
                                </x-input-field>
                            </div>

                            <div class="col-md-6">
                                <x-input-field 
                                    name="father_name" 
                                    label="Father Name" 
                                    placeholder="Enter father name"
                                    value="{{ old('father_name', $admin->father_name ?? '') }}"
                                />
                            </div>

                            <div class="col-md-6">
                                <x-input-field 
                                    type="email"
                                    name="email" 
                                    label="Email" 
                                    placeholder="Enter email address"
                                    value="{{ old('email', $admin->email ?? '') }}"
                                    required 
                                />
                            </div>

                            <div class="col-md-6">
                                <x-input-field 
                                    type="date"
                                    name="date_of_birth" 
                                    label="Date of Birth" 
                                    value="{{ old('date_of_birth', $admin->date_of_birth ?? '') }}"
                                />
                            </div>

                            <div class="col-md-6">
                                <x-input-field 
                                    type="select" 
                                    name="gender" 
                                    label="Gender" 
                                    placeholder="Select Gender"
                                >
                                    <option value="male" {{ old('gender', $admin->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $admin->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender', $admin->gender ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                                </x-input-field>
                            </div>

                            <div class="col-md-6">
                                <x-input-field 
                                    name="phone" 
                                    label="Phone" 
                                    placeholder="Enter phone number"
                                    value="{{ old('phone', $admin->phone ?? '') }}"
                                />
                            </div>

                            <div class="col-md-6">
                                <x-input-field 
                                    name="education" 
                                    label="Education" 
                                    placeholder="Enter education details"
                                    value="{{ old('education', $admin->education ?? '') }}"
                                />
                            </div>

                            <div class="col-md-6">
                                <x-input-field 
                                    type="select" 
                                    name="status" 
                                    label="Status" 
                                    required
                                >
                                    <option value="active" {{ old('status', $admin->status ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $admin->status ?? 'active') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </x-input-field>
                            </div>

                            <div class="col-md-6">
                                <x-input-field 
                                    type="date"
                                    name="resignation_date" 
                                    label="Resignation Date" 
                                    value="{{ old('resignation_date', $admin->resignation_date ?? '') }}"
                                />
                            </div>

                            <div class="col-md-12">
                                <x-input-field 
                                    type="textarea"
                                    name="address" 
                                    label="Address" 
                                    placeholder="Enter address"
                                    value="{{ old('address', $admin->address ?? '') }}"
                                    rows="3"
                                />
                            </div>

                            <div class="col-md-12">
                                <x-input-field 
                                    type="textarea"
                                    name="purpose" 
                                    label="Purpose" 
                                    placeholder="Enter purpose"
                                    value="{{ old('purpose', $admin->purpose ?? '') }}"
                                    rows="3"
                                />
                            </div>

                            <div class="col-md-6">
                                <x-input-field 
                                    type="password"
                                    name="password" 
                                    label="{{ isset($admin) ? 'Password (Leave blank to keep current)' : 'Password' }}" 
                                    placeholder="Enter password"
                                    :required="!isset($admin)"
                                />
                            </div>

                            <div class="col-md-6">
                                <x-input-field 
                                    type="password"
                                    name="password_confirmation" 
                                    label="Confirm Password" 
                                    placeholder="Confirm password"
                                    :required="!isset($admin)"
                                />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary" id="submit-btn">
                                    {{ isset($admin) ? 'Update Admin' : 'Create Admin' }}
                                    <span class="spinner-border spinner-border-sm d-none" id="submit-btn-spinner" role="status" aria-hidden="true"></span>
                                </button>
                                <a href="{{ route('owner.admins.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
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
        $("#admin-form").validate({
            rules: {
                name: { required: true },
                email: { required: true, email: true },
                status: { required: true },
                @if(!isset($admin))
                password: { required: true, minlength: 8 },
                password_confirmation: { required: true, equalTo: "[name='password']" }
                @else
                password: { minlength: 8 },
                password_confirmation: { equalTo: "[name='password']" }
                @endif
            },
            messages: {
                name: { required: "The name field is required" },
                email: { required: "The email field is required", email: "Please enter a valid email address" },
                status: { required: "The status field is required" },
                password: { 
                    @if(!isset($admin))
                    required: "The password field is required", 
                    @endif
                    minlength: "Password must be at least 8 characters long" 
                },
                password_confirmation: { 
                    @if(!isset($admin))
                    required: "The confirm password field is required", 
                    @endif
                    equalTo: "Passwords do not match" 
                }
            },
            errorPlacement: function (error, element) {
                element.after(error);
            },
            errorClass: "text-danger",
            submitHandler: function (form, e) {
                e.preventDefault();
                
                @if(isset($admin))
                    var url = "{{ route('owner.admins.save', $admin->id) }}";
                @else
                    var url = "{{ route('owner.admins.save') }}";
                @endif
                
                $.ajax({
                    url: url,
                    method: "POST",
                    dataType: "json",
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: function () {
                        $('#submit-btn').attr('disabled', true);
                        $("#submit-btn-spinner").removeClass('d-none');
                    },
                    success: function (result) {
                        if(result.status){
                            sendSuccess(result.message);
                            setTimeout(function() {
                                window.location.href = "{{ route('owner.admins.index') }}";
                            }, 1000);
                        } else {
                            sendError(result.message);
                        }
                    },
                    error: function (xhr) {
                        let data = xhr.responseJSON;
                        if (data && data.hasOwnProperty('error')) {
                            // Display validation errors
                            $.each(data.error, function(field, messages) {
                                let errorMsg = Array.isArray(messages) ? messages[0] : messages;
                                sendError(errorMsg);
                            });
                        } else if (data && data.hasOwnProperty('message')) {
                            sendError(data.message);
                        } else {
                            sendError('An error occurred. Please try again.');
                        }
                    },
                    complete: function () {
                        $('#submit-btn').attr('disabled', false);
                        $("#submit-btn-spinner").addClass('d-none');
                    },
                });
            }
        });
    });
</script>
@endsection
