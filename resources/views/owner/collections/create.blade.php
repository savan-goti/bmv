@extends('owner.master')
@section('title','Create Collection')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Create Collection</h4>
                <div class="page-title-right">
                    <a href="{{ route('owner.collections.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('owner.collections.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <x-input-field 
                                    name="name" 
                                    label="Name" 
                                    placeholder="Enter collection name"
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

                            <div class="col-md-6">
                                <x-input-field 
                                    type="date"
                                    name="start_date" 
                                    label="Start Date" 
                                    value="{{ old('start_date') }}"
                                />
                            </div>

                            <div class="col-md-6">
                                <x-input-field 
                                    type="date"
                                    name="end_date" 
                                    label="End Date" 
                                    value="{{ old('end_date') }}"
                                />
                            </div>

                            <div class="col-md-6 mb-3">
                                <x-input-field 
                                    type="file" 
                                    name="image" 
                                    label="Image" 
                                    accept="image/*"
                                />
                                <div id="image-preview" class="mt-2"></div>
                            </div>

                            <div class="col-md-6">
                                <x-checkbox 
                                    name="is_featured" 
                                    value="1" 
                                    label="Featured Collection"
                                    :checked="old('is_featured') ?? false"
                                    container-class="mt-4"
                                />
                            </div>

                            <div class="col-md-12">
                                <x-input-field 
                                    type="textarea"
                                    name="description" 
                                    label="Description" 
                                    placeholder="Enter collection description"
                                    value="{{ old('description') }}"
                                    rows="4"
                                />
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Select Products</label>
                                <select name="products[]" id="products" class="form-select" multiple size="10">
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" {{ in_array($product->id, old('products', [])) ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Hold Ctrl (Cmd on Mac) to select multiple products</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Create Collection</button>
                                <a href="{{ route('owner.collections.index') }}" class="btn btn-secondary">Cancel</a>
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
