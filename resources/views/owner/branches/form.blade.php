@extends('owner.master')
@section('title', isset($branch) ? 'Edit Branch' : 'Create Branch')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">{{ isset($branch) ? 'Edit Branch' : 'Create Branch' }}</h4>
                <div class="page-title-right">
                    <a href="{{ route('owner.branches.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="branch-form">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <x-input-field 
                                    name="name" 
                                    label="Branch Name" 
                                    placeholder="Enter branch name" 
                                    value="{{ old('name', $branch->name ?? '') }}"
                                    required 
                                />
                            </div>
                            <div class="col-md-6">
                                <x-input-field 
                                    name="code" 
                                    label="Branch Code" 
                                    placeholder="e.g., BR001" 
                                    value="{{ old('code', $branch->code ?? '') }}"
                                    required 
                                />
                            </div>
                        </div>

                        <x-input-field type="select" name="type" label="Branch Type" required>
                            <option value="product" {{ old('type', $branch->type ?? 'product') == 'product' ? 'selected' : '' }}>Product</option>
                            <option value="service" {{ old('type', $branch->type ?? 'product') == 'service' ? 'selected' : '' }}>Service</option>
                        </x-input-field>

                        <div class="row">
                            <div class="col-md-6">
                                <x-input-field 
                                    type="email" 
                                    name="email" 
                                    label="Email" 
                                    placeholder="Enter email" 
                                    value="{{ old('email', $branch->email ?? '') }}"
                                />
                            </div>
                            <div class="col-md-6">
                                <x-input-field 
                                    name="phone" 
                                    label="Phone" 
                                    placeholder="Enter phone" 
                                    value="{{ old('phone', $branch->phone ?? '') }}"
                                />
                            </div>
                        </div>

                        <x-input-field 
                            type="textarea" 
                            name="address" 
                            label="Address" 
                            placeholder="Enter address" 
                            value="{{ old('address', $branch->address ?? '') }}"
                            rows="2" 
                        />

                        <div class="row">
                            <div class="col-md-4">
                                <x-input-field 
                                    name="city" 
                                    label="City" 
                                    placeholder="Enter city" 
                                    value="{{ old('city', $branch->city ?? '') }}"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-input-field 
                                    name="state" 
                                    label="State" 
                                    placeholder="Enter state" 
                                    value="{{ old('state', $branch->state ?? '') }}"
                                />
                            </div>
                            <div class="col-md-4">
                                <x-input-field 
                                    name="country" 
                                    label="Country" 
                                    placeholder="Enter country" 
                                    value="{{ old('country', $branch->country ?? '') }}"
                                />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <x-input-field 
                                    name="postal_code" 
                                    label="Postal Code" 
                                    placeholder="Enter postal code" 
                                    value="{{ old('postal_code', $branch->postal_code ?? '') }}"
                                />
                            </div>
                            <div class="col-md-6">
                                <x-input-field 
                                    type="date" 
                                    name="opening_date" 
                                    label="Opening Date" 
                                    value="{{ old('opening_date', $branch->opening_date ?? '') }}"
                                />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <x-input-field 
                                    name="manager_name" 
                                    label="Manager Name" 
                                    placeholder="Enter manager name" 
                                    value="{{ old('manager_name', $branch->manager_name ?? '') }}"
                                />
                            </div>
                            <div class="col-md-6">
                                <x-input-field 
                                    name="manager_phone" 
                                    label="Manager Phone" 
                                    placeholder="Enter manager phone" 
                                    value="{{ old('manager_phone', $branch->manager_phone ?? '') }}"
                                />
                            </div>
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3">Social Media Links</h5>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <x-input-field 
                                    type="url" 
                                    name="social_media[facebook_url]" 
                                    label="Facebook URL" 
                                    placeholder="https://facebook.com/..." 
                                    value="{{ old('social_media.facebook_url', $branch->social_media['facebook_url'] ?? '') }}"
                                    icon="bx bxl-facebook" 
                                />
                            </div>
                            <div class="col-md-6">
                                <x-input-field 
                                    type="url" 
                                    name="social_media[instagram_url]" 
                                    label="Instagram URL" 
                                    placeholder="https://instagram.com/..." 
                                    value="{{ old('social_media.instagram_url', $branch->social_media['instagram_url'] ?? '') }}"
                                    icon="bx bxl-instagram" 
                                />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <x-input-field 
                                    type="url" 
                                    name="social_media[twitter_url]" 
                                    label="Twitter URL" 
                                    placeholder="https://twitter.com/..." 
                                    value="{{ old('social_media.twitter_url', $branch->social_media['twitter_url'] ?? '') }}"
                                    icon="bx bxl-twitter" 
                                />
                            </div>
                            <div class="col-md-6">
                                <x-input-field 
                                    type="url" 
                                    name="social_media[linkedin_url]" 
                                    label="LinkedIn URL" 
                                    placeholder="https://linkedin.com/..." 
                                    value="{{ old('social_media.linkedin_url', $branch->social_media['linkedin_url'] ?? '') }}"
                                    icon="bx bxl-linkedin" 
                                />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <x-input-field 
                                    type="url" 
                                    name="social_media[youtube_url]" 
                                    label="YouTube URL" 
                                    placeholder="https://youtube.com/..." 
                                    value="{{ old('social_media.youtube_url', $branch->social_media['youtube_url'] ?? '') }}"
                                    icon="bx bxl-youtube" 
                                />
                            </div>
                            <div class="col-md-6">
                                <x-input-field 
                                    type="url" 
                                    name="social_media[pinterest_url]" 
                                    label="Pinterest URL" 
                                    placeholder="https://pinterest.com/..." 
                                    value="{{ old('social_media.pinterest_url', $branch->social_media['pinterest_url'] ?? '') }}"
                                    icon="bx bxl-pinterest" 
                                />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <x-input-field 
                                    type="url" 
                                    name="social_media[tiktok_url]" 
                                    label="TikTok URL" 
                                    placeholder="https://tiktok.com/..." 
                                    value="{{ old('social_media.tiktok_url', $branch->social_media['tiktok_url'] ?? '') }}"
                                    icon="bx bxl-tiktok" 
                                />
                            </div>
                            <div class="col-md-6">
                                <x-input-field 
                                    type="url" 
                                    name="social_media[whatsapp_url]" 
                                    label="WhatsApp URL" 
                                    placeholder="https://wa.me/..." 
                                    value="{{ old('social_media.whatsapp_url', $branch->social_media['whatsapp_url'] ?? '') }}"
                                    icon="bx bxl-whatsapp" 
                                />
                            </div>
                        </div>

                        <x-input-field 
                            type="url" 
                            name="social_media[telegram_url]" 
                            label="Telegram URL" 
                            placeholder="https://t.me/..." 
                            value="{{ old('social_media.telegram_url', $branch->social_media['telegram_url'] ?? '') }}"
                            icon="bx bxl-telegram" 
                        />

                        <hr class="my-4">

                        <x-input-field type="select" name="status" label="Status" required>
                            <option value="active" {{ old('status', $branch->status ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $branch->status ?? 'active') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </x-input-field>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary" id="submit-btn">
                                    {{ isset($branch) ? 'Update Branch' : 'Create Branch' }}
                                    <span class="spinner-border spinner-border-sm d-none" id="submit-btn-spinner" role="status" aria-hidden="true"></span>
                                </button>
                                <a href="{{ route('owner.branches.index') }}" class="btn btn-secondary">Cancel</a>
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
        $("#branch-form").validate({
            rules: {
                name: { required: true },
                code: { required: true },
                type: { required: true },
                status: { required: true },
                email: { email: true }
            },
            messages: {
                name: { required: "The branch name field is required" },
                code: { required: "The branch code field is required" },
                type: { required: "The branch type field is required" },
                status: { required: "The status field is required" },
                email: { email: "Please enter a valid email address" }
            },
            errorPlacement: function (error, element) {
                element.after(error);
            },
            errorClass: "text-danger",
            submitHandler: function (form, e) {
                e.preventDefault();
                
                @if(isset($branch))
                    var url = "{{ route('owner.branches.save', $branch->id) }}";
                @else
                    var url = "{{ route('owner.branches.save') }}";
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
                                window.location.href = "{{ route('owner.branches.index') }}";
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
