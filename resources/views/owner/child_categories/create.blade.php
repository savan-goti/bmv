@extends('owner.master')
@section('title','Create Child Category')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Create Child Category</h4>
                <div class="page-title-right">
                    <a href="{{ route('owner.child-categories.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('owner.child-categories.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <x-input-field 
                                    type="select" 
                                    name="category_id" 
                                    label="Category" 
                                    placeholder="Select Category"
                                    required
                                >
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </x-input-field>
                            </div>

                            <div class="col-md-6">
                                <x-input-field 
                                    type="select" 
                                    name="sub_category_id" 
                                    label="Sub Category" 
                                    placeholder="Select Sub Category"
                                    required
                                >
                                </x-input-field>
                            </div>

                            <div class="col-md-6">
                                <x-input-field 
                                    name="name" 
                                    label="Name" 
                                    placeholder="Enter child category name"
                                    value="{{ old('name') }}"
                                    required 
                                />
                            </div>

                            <div class="col-md-6">
                                <x-input-field 
                                    type="select" 
                                    name="status" 
                                    label="Status" 
                                    required
                                >
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </x-input-field>
                            </div>

                            <div class="col-md-12 mb-3">
                                <x-input-field 
                                    type="file" 
                                    name="image" 
                                    label="Image" 
                                    accept="image/*"
                                />
                                <div id="image-preview" class="mt-2"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Create Child Category</button>
                                <a href="{{ route('owner.child-categories.index') }}" class="btn btn-secondary">Cancel</a>
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
        // Load sub-categories when category changes
        $('#category_id').change(function() {
            var categoryId = $(this).val();
            $('#sub_category_id').html('<option value="">Loading...</option>');
            
            if (categoryId) {
                $.ajax({
                    url: "{{ route('owner.sub-categories.get-by-category') }}",
                    type: 'GET',
                    data: { category_id: categoryId },
                    success: function(data) {
                        var options = '<option value="">Select Sub Category</option>';
                        $.each(data, function(key, value) {
                            options += '<option value="' + value.id + '">' + value.name + '</option>';
                        });
                        $('#sub_category_id').html(options);
                    },
                    error: function() {
                        $('#sub_category_id').html('<option value="">Error loading sub-categories</option>');
                    }
                });
            } else {
                $('#sub_category_id').html('<option value="">Select Sub Category</option>');
            }
        });

        // Image preview
        $('#image').change(function() {
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#image-preview').html('<img src="' + e.target.result + '" class="img-thumbnail" style="max-width: 200px;">');
                }
                reader.readAsDataURL(file);
            } else {
                $('#image-preview').html('');
            }
        });
    });
</script>
@endsection
