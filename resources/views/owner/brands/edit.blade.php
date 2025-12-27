@extends('owner.master')
@section('title','Edit Brand')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Edit Brand</h4>
                <div class="page-title-right">
                    <a href="{{ route('owner.brands.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('owner.brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <x-input-field 
                                    name="name" 
                                    label="Name" 
                                    placeholder="Enter brand name"
                                    value="{{ old('name', $brand->name) }}"
                                    required 
                                />
                            </div>

                            <div class="col-md-6">
                                <x-input-field 
                                    type="url"
                                    name="website" 
                                    label="Website" 
                                    placeholder="https://example.com"
                                    value="{{ old('website', $brand->website) }}"
                                />
                            </div>

                            <div class="col-md-6">
                                <x-input-field 
                                    type="select" 
                                    name="status" 
                                    label="Status" 
                                    required
                                >
                                    <option value="active" {{ old('status', $brand->status->value) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $brand->status->value) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </x-input-field>
                            </div>

                            <div class="col-md-6 mb-3">
                                <x-input-field 
                                    type="file" 
                                    name="logo" 
                                    label="Logo" 
                                    accept="image/*"
                                />
                                
                                @if($brand->getRawOriginal('logo'))
                                    <div class="mt-2">
                                        <p class="mb-1">Current Logo:</p>
                                        <img src="{{ asset('uploads/brands/' . $brand->getRawOriginal('logo')) }}" 
                                            alt="{{ $brand->name }}" class="img-thumbnail" style="max-width: 200px;">
                                    </div>
                                @endif
                                
                                <div id="logo-preview" class="mt-2"></div>
                            </div>

                            <div class="col-md-12">
                                <x-input-field 
                                    type="textarea"
                                    name="description" 
                                    label="Description" 
                                    placeholder="Enter brand description"
                                    value="{{ old('description', $brand->description) }}"
                                    rows="4"
                                />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Update Brand</button>
                                <a href="{{ route('owner.brands.index') }}" class="btn btn-secondary">Cancel</a>
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
        // Logo preview
        $('#logo').change(function() {
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#logo-preview').html('<p class="mb-1">New Logo Preview:</p><img src="' + e.target.result + '" class="img-thumbnail" style="max-width: 200px;">');
                }
                reader.readAsDataURL(file);
            } else {
                $('#logo-preview').html('');
            }
        });
    });
</script>
@endsection
