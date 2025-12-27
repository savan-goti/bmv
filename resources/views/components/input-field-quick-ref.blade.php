{{-- ============================================ --}}
{{-- INPUT FIELD COMPONENT - QUICK REFERENCE --}}
{{-- ============================================ --}}

{{-- TEXT INPUT --}}
<x-input-field name="field_name" label="Label" placeholder="Placeholder" required />

{{-- NUMBER INPUT --}}
<x-input-field type="number" name="price" label="Price" step="0.01" min="0" required />

{{-- EMAIL INPUT --}}
<x-input-field type="email" name="email" label="Email" icon="bx bx-envelope" required />

{{-- PASSWORD INPUT --}}
<x-input-field type="password" name="password" label="Password" icon="bx bx-lock-alt" required />

{{-- URL INPUT --}}
<x-input-field type="url" name="website" label="Website" placeholder="https://example.com" />

{{-- DATE INPUT --}}
<x-input-field type="date" name="date" label="Date" required />

{{-- TEXTAREA --}}
<x-input-field type="textarea" name="description" label="Description" rows="5" />

{{-- SELECT DROPDOWN --}}
<x-input-field type="select" name="category" label="Category" placeholder="Select..." required>
    <option value="1">Option 1</option>
    <option value="2">Option 2</option>
</x-input-field>

{{-- FILE UPLOAD --}}
<x-input-field type="file" name="image" label="Image" accept="image/*" />

{{-- MULTIPLE FILE UPLOAD --}}
<x-input-field type="file" name="images[]" label="Images" accept="image/*" multiple />

{{-- WITH HELP TEXT --}}
<x-input-field name="sku" label="SKU" help-text="Unique product identifier" />

{{-- WITH ICON (LEFT) --}}
<x-input-field name="search" label="Search" icon="bx bx-search" icon-position="left" />

{{-- WITH ICON (RIGHT) --}}
<x-input-field name="link" label="Link" icon="bx bx-link" icon-position="right" />

{{-- READONLY --}}
<x-input-field name="code" label="Code" value="ABC123" readonly />

{{-- DISABLED --}}
<x-input-field name="status" label="Status" value="Active" disabled />

{{-- WITH CUSTOM CLASSES --}}
<x-input-field 
    name="custom" 
    label="Custom" 
    container-class="mb-4 col-md-6"
    input-class="form-control form-control-lg"
/>

{{-- COMMON PROPS --}}
{{--
    name          - Input name (required)
    label         - Label text
    type          - text|number|email|password|url|date|file|textarea|select
    placeholder   - Placeholder text
    value         - Default value
    required      - Mark as required
    readonly      - Make readonly
    disabled      - Disable field
    min/max       - Min/max values (number/date)
    step          - Step value (number)
    maxlength     - Max character length
    rows          - Rows (textarea)
    accept        - File types (file input)
    multiple      - Multiple files (file input)
    help-text     - Help text below input
    icon          - Icon class
    icon-position - left|right
--}}
