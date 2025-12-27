{{-- 
    EXAMPLE: Refactoring Product Create Form to Use Input Field Component
    
    This file shows before/after examples of how to replace traditional form inputs
    with the new <x-input-field> component in your product forms.
--}}

{{-- ============================================ --}}
{{-- BASIC INFO TAB EXAMPLES --}}
{{-- ============================================ --}}

{{-- BEFORE: Product Name --}}
<div class="mb-3">
    <label for="product_name" class="form-label">Product Name <span class="text-danger">*</span></label>
    <input type="text" class="form-control" id="product_name" name="product_name" 
           placeholder="Enter product name" maxlength="255" required>
</div>

{{-- AFTER: Product Name --}}
<x-input-field 
    name="product_name" 
    label="Product Name" 
    placeholder="Enter product name"
    maxlength="255"
    required 
/>

{{-- ============================================ --}}

{{-- BEFORE: Product Description (Textarea) --}}
<div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea class="form-control" id="description" name="description" 
              rows="4" placeholder="Enter product description"></textarea>
</div>

{{-- AFTER: Product Description --}}
<x-input-field 
    type="textarea" 
    name="description" 
    label="Description" 
    placeholder="Enter product description"
    rows="4"
/>

{{-- ============================================ --}}
{{-- PRICING TAB EXAMPLES --}}
{{-- ============================================ --}}

{{-- BEFORE: Sell Price --}}
<div class="mb-3">
    <label for="sell_price" class="form-label">Sell Price <span class="text-danger">*</span></label>
    <input type="number" class="form-control" id="sell_price" name="sell_price" 
           step="0.01" min="0" placeholder="0.00" required>
</div>

{{-- AFTER: Sell Price --}}
<x-input-field 
    type="number" 
    name="sell_price" 
    label="Sell Price" 
    placeholder="0.00"
    step="0.01"
    min="0"
    required 
/>

{{-- ============================================ --}}

{{-- BEFORE: Cost Price --}}
<div class="mb-3">
    <label for="cost_price" class="form-label">Cost Price</label>
    <input type="number" class="form-control" id="cost_price" name="cost_price" 
           step="0.01" min="0" placeholder="0.00">
</div>

{{-- AFTER: Cost Price --}}
<x-input-field 
    type="number" 
    name="cost_price" 
    label="Cost Price" 
    placeholder="0.00"
    step="0.01"
    min="0"
/>

{{-- ============================================ --}}
{{-- INVENTORY TAB EXAMPLES --}}
{{-- ============================================ --}}

{{-- BEFORE: Total Stock --}}
<div class="mb-3">
    <label for="total_stock" class="form-label">Total Stock <span class="text-danger">*</span></label>
    <input type="number" class="form-control" id="total_stock" name="total_stock" 
           min="0" value="0" required>
</div>

{{-- AFTER: Total Stock --}}
<x-input-field 
    type="number" 
    name="total_stock" 
    label="Total Stock" 
    value="0"
    min="0"
    required 
/>

{{-- ============================================ --}}

{{-- BEFORE: SKU --}}
<div class="mb-3">
    <label for="sku" class="form-label">SKU</label>
    <input type="text" class="form-control" id="sku" name="sku" 
           placeholder="Enter SKU">
</div>

{{-- AFTER: SKU --}}
<x-input-field 
    name="sku" 
    label="SKU" 
    placeholder="Enter SKU"
/>

{{-- ============================================ --}}
{{-- MEDIA TAB EXAMPLES --}}
{{-- ============================================ --}}

{{-- BEFORE: Image Alt Text --}}
<div class="mb-3">
    <label for="image_alt_text" class="form-label">Image Alt Text</label>
    <input type="text" class="form-control" id="image_alt_text" name="image_alt_text" 
           placeholder="Enter descriptive alt text for SEO">
</div>

{{-- AFTER: Image Alt Text --}}
<x-input-field 
    name="image_alt_text" 
    label="Image Alt Text" 
    placeholder="Enter descriptive alt text for SEO"
/>

{{-- ============================================ --}}

{{-- BEFORE: Video URL --}}
<div class="mb-3">
    <label for="video_url" class="form-label">Video URL</label>
    <input type="url" class="form-control" id="video_url" name="video_url" 
           placeholder="https://youtube.com/watch?v=...">
    <small class="text-muted">Add a YouTube or Vimeo video URL</small>
</div>

{{-- AFTER: Video URL --}}
<x-input-field 
    type="url" 
    name="video_url" 
    label="Video URL" 
    placeholder="https://youtube.com/watch?v=..."
    help-text="Add a YouTube or Vimeo video URL"
/>

{{-- ============================================ --}}
{{-- SHIPPING TAB EXAMPLES --}}
{{-- ============================================ --}}

{{-- BEFORE: Weight --}}
<div class="mb-3">
    <label for="weight" class="form-label">Weight (kg)</label>
    <input type="number" class="form-control" id="weight" name="weight" 
           step="0.01" min="0">
</div>

{{-- AFTER: Weight --}}
<x-input-field 
    type="number" 
    name="weight" 
    label="Weight (kg)" 
    step="0.01"
    min="0"
/>

{{-- ============================================ --}}

{{-- BEFORE: Dimensions (Row with 3 columns) --}}
<div class="row">
    <div class="col-md-4">
        <div class="mb-3">
            <label for="length" class="form-label">Length (cm)</label>
            <input type="number" class="form-control" id="length" name="length" 
                   step="0.01" min="0">
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label for="width" class="form-label">Width (cm)</label>
            <input type="number" class="form-control" id="width" name="width" 
                   step="0.01" min="0">
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label for="height" class="form-label">Height (cm)</label>
            <input type="number" class="form-control" id="height" name="height" 
                   step="0.01" min="0">
        </div>
    </div>
</div>

{{-- AFTER: Dimensions (Row with 3 columns) --}}
<div class="row">
    <div class="col-md-4">
        <x-input-field 
            type="number" 
            name="length" 
            label="Length (cm)" 
            step="0.01"
            min="0"
        />
    </div>
    <div class="col-md-4">
        <x-input-field 
            type="number" 
            name="width" 
            label="Width (cm)" 
            step="0.01"
            min="0"
        />
    </div>
    <div class="col-md-4">
        <x-input-field 
            type="number" 
            name="height" 
            label="Height (cm)" 
            step="0.01"
            min="0"
        />
    </div>
</div>

{{-- ============================================ --}}

{{-- BEFORE: Shipping Class (Select) --}}
<div class="mb-3">
    <label for="shipping_class" class="form-label">Shipping Class <span class="text-danger">*</span></label>
    <select class="form-select" name="shipping_class" id="shipping_class" required>
        <option value="normal" selected>Normal</option>
        <option value="heavy">Heavy</option>
    </select>
</div>

{{-- AFTER: Shipping Class --}}
<x-input-field 
    type="select" 
    name="shipping_class" 
    label="Shipping Class" 
    required
>
    <option value="normal" selected>Normal</option>
    <option value="heavy">Heavy</option>
</x-input-field>

{{-- ============================================ --}}
{{-- SEO TAB EXAMPLES --}}
{{-- ============================================ --}}

{{-- BEFORE: Meta Title --}}
<div class="mb-3">
    <label for="meta_title" class="form-label">Meta Title</label>
    <input type="text" class="form-control" id="meta_title" name="meta_title" maxlength="255">
    <small class="text-muted">Recommended: 50-60 characters</small>
</div>

{{-- AFTER: Meta Title --}}
<x-input-field 
    name="meta_title" 
    label="Meta Title" 
    maxlength="255"
    help-text="Recommended: 50-60 characters"
/>

{{-- ============================================ --}}

{{-- BEFORE: Meta Description --}}
<div class="mb-3">
    <label for="meta_description" class="form-label">Meta Description</label>
    <textarea class="form-control" id="meta_description" name="meta_description" 
              rows="3" maxlength="160"></textarea>
    <small class="text-muted">Recommended: 150-160 characters</small>
</div>

{{-- AFTER: Meta Description --}}
<x-input-field 
    type="textarea" 
    name="meta_description" 
    label="Meta Description" 
    rows="3"
    maxlength="160"
    help-text="Recommended: 150-160 characters"
/>

{{-- ============================================ --}}

{{-- BEFORE: Meta Keywords --}}
<div class="mb-3">
    <label for="meta_keywords" class="form-label">Meta Keywords</label>
    <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" 
           placeholder="keyword1, keyword2, keyword3">
</div>

{{-- AFTER: Meta Keywords --}}
<x-input-field 
    name="meta_keywords" 
    label="Meta Keywords" 
    placeholder="keyword1, keyword2, keyword3"
/>

{{-- ============================================ --}}
{{-- SIDEBAR EXAMPLES --}}
{{-- ============================================ --}}

{{-- BEFORE: Category (Select with dynamic options) --}}
<div class="mb-3">
    <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
    <select class="form-select" name="category_id" id="category_id" required>
        <option value="">Select Category</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
    </select>
</div>

{{-- AFTER: Category --}}
<x-input-field 
    type="select" 
    name="category_id" 
    label="Category" 
    placeholder="Select Category"
    required
>
    @foreach($categories as $category)
        <option value="{{ $category->id }}">{{ $category->name }}</option>
    @endforeach
</x-input-field>

{{-- ============================================ --}}

{{-- BEFORE: Return Days --}}
<div class="mb-3">
    <label for="return_days" class="form-label">Return Days</label>
    <input type="number" class="form-control" id="return_days" name="return_days" 
           min="0" value="7">
</div>

{{-- AFTER: Return Days --}}
<x-input-field 
    type="number" 
    name="return_days" 
    label="Return Days" 
    value="7"
    min="0"
/>

{{-- ============================================ --}}
{{-- ADVANCED EXAMPLES WITH ICONS --}}
{{-- ============================================ --}}

{{-- Email with Icon --}}
<x-input-field 
    type="email" 
    name="contact_email" 
    label="Contact Email" 
    placeholder="example@domain.com"
    icon="bx bx-envelope"
    icon-position="left"
/>

{{-- Price with Dollar Icon --}}
<x-input-field 
    type="number" 
    name="price" 
    label="Price" 
    placeholder="0.00"
    step="0.01"
    min="0"
    icon="bx bx-dollar"
    icon-position="left"
/>

{{-- Search with Icon --}}
<x-input-field 
    name="search" 
    label="Search Products" 
    placeholder="Search..."
    icon="bx bx-search"
    icon-position="left"
/>

{{-- Website URL with Icon --}}
<x-input-field 
    type="url" 
    name="website" 
    label="Website" 
    placeholder="https://example.com"
    icon="bx bx-link-external"
    icon-position="right"
/>

{{-- ============================================ --}}
{{-- NOTES --}}
{{-- ============================================ --}}

{{--
    Benefits of Using the Component:
    
    1. ✅ Cleaner, more readable code
    2. ✅ Consistent styling across all forms
    3. ✅ Automatic validation error display
    4. ✅ Less repetitive code
    5. ✅ Easier to maintain
    6. ✅ Built-in accessibility features
    7. ✅ Old value retention out of the box
    8. ✅ Required field indicators automatically added
    
    To refactor your existing forms:
    1. Replace traditional input markup with <x-input-field>
    2. Map attributes to component props
    3. Test validation and form submission
    4. Enjoy cleaner, more maintainable code!
--}}
