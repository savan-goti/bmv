# Input Field Component Documentation

## Overview
A reusable Blade component for creating form input fields with consistent styling, validation, and features across your Laravel application.

## Location
`resources/views/components/input-field.blade.php`

## Features
- ✅ Multiple input types (text, number, email, password, url, date, file, textarea, select)
- ✅ Icon support (left or right positioned)
- ✅ Required field indicator
- ✅ Validation error display
- ✅ Help text support
- ✅ Old value retention
- ✅ Bootstrap 5 styling
- ✅ Fully customizable classes
- ✅ Accessibility compliant

---

## Basic Usage

### Simple Text Input
```blade
<x-input-field 
    name="product_name" 
    label="Product Name" 
    placeholder="Enter product name"
    required 
/>
```

### Number Input
```blade
<x-input-field 
    type="number" 
    name="price" 
    label="Price" 
    placeholder="0.00"
    step="0.01"
    min="0"
    required 
/>
```

### Email Input
```blade
<x-input-field 
    type="email" 
    name="email" 
    label="Email Address" 
    placeholder="example@domain.com"
    icon="bx bx-envelope"
    required 
/>
```

### Password Input
```blade
<x-input-field 
    type="password" 
    name="password" 
    label="Password" 
    placeholder="Enter password"
    icon="bx bx-lock-alt"
    required 
/>
```

### URL Input
```blade
<x-input-field 
    type="url" 
    name="video_url" 
    label="Video URL" 
    placeholder="https://youtube.com/watch?v=..."
    help-text="Add a YouTube or Vimeo video URL"
/>
```

### Date Input
```blade
<x-input-field 
    type="date" 
    name="launch_date" 
    label="Launch Date" 
    required 
/>
```

### Textarea
```blade
<x-input-field 
    type="textarea" 
    name="description" 
    label="Description" 
    placeholder="Enter product description"
    rows="5"
    maxlength="500"
    help-text="Maximum 500 characters"
/>
```

### Select Dropdown
```blade
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
```

### File Upload
```blade
<x-input-field 
    type="file" 
    name="thumbnail" 
    label="Thumbnail Image" 
    accept="image/*"
    help-text="Recommended size: 800x800px. Max file size: 5MB"
/>
```

### Multiple File Upload
```blade
<x-input-field 
    type="file" 
    name="gallery_images[]" 
    label="Gallery Images" 
    accept="image/*"
    multiple
    help-text="You can upload multiple images. Max 10 images, 5MB each"
/>
```

---

## Advanced Usage

### Input with Left Icon
```blade
<x-input-field 
    name="search" 
    label="Search Products" 
    placeholder="Search..."
    icon="bx bx-search"
    icon-position="left"
/>
```

### Input with Right Icon
```blade
<x-input-field 
    name="website" 
    label="Website" 
    placeholder="https://example.com"
    icon="bx bx-link-external"
    icon-position="right"
/>
```

### Readonly Input
```blade
<x-input-field 
    name="sku" 
    label="SKU" 
    value="PRD-12345"
    readonly
/>
```

### Disabled Input
```blade
<x-input-field 
    name="total_stock" 
    label="Total Stock" 
    value="999999"
    disabled
/>
```

### Custom Classes
```blade
<x-input-field 
    name="discount" 
    label="Discount" 
    container-class="mb-4 col-md-6"
    label-class="form-label fw-bold"
    input-class="form-control form-control-lg"
/>
```

### With Pattern Validation
```blade
<x-input-field 
    name="phone" 
    label="Phone Number" 
    placeholder="+1 (555) 000-0000"
    pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"
    help-text="Format: 123-456-7890"
/>
```

---

## Complete Example: Product Form

```blade
<form action="{{ route('owner.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="row">
        <div class="col-md-8">
            {{-- Product Name --}}
            <x-input-field 
                name="product_name" 
                label="Product Name" 
                placeholder="Enter product name"
                maxlength="255"
                required 
            />

            {{-- Description --}}
            <x-input-field 
                type="textarea" 
                name="description" 
                label="Description" 
                placeholder="Enter detailed product description"
                rows="6"
                help-text="Provide a comprehensive description of your product"
            />

            {{-- Price --}}
            <div class="row">
                <div class="col-md-6">
                    <x-input-field 
                        type="number" 
                        name="sell_price" 
                        label="Sell Price" 
                        placeholder="0.00"
                        step="0.01"
                        min="0"
                        icon="bx bx-dollar"
                        required 
                    />
                </div>
                <div class="col-md-6">
                    <x-input-field 
                        type="number" 
                        name="cost_price" 
                        label="Cost Price" 
                        placeholder="0.00"
                        step="0.01"
                        min="0"
                        icon="bx bx-dollar"
                    />
                </div>
            </div>

            {{-- Stock --}}
            <x-input-field 
                type="number" 
                name="total_stock" 
                label="Total Stock" 
                placeholder="0"
                min="0"
                required 
            />
        </div>

        <div class="col-md-4">
            {{-- Category --}}
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

            {{-- Thumbnail --}}
            <x-input-field 
                type="file" 
                name="thumbnail_image" 
                label="Thumbnail Image" 
                accept="image/*"
                help-text="Recommended size: 800x800px. Max file size: 5MB"
            />

            {{-- Video URL --}}
            <x-input-field 
                type="url" 
                name="video_url" 
                label="Video URL" 
                placeholder="https://youtube.com/watch?v=..."
                help-text="Add a YouTube or Vimeo video URL"
            />
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Create Product</button>
</form>
```

---

## Available Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `type` | string | 'text' | Input type (text, number, email, password, url, date, file, textarea, select) |
| `name` | string | '' | Input name attribute (required) |
| `id` | string | '' | Input ID (auto-generated from name if not provided) |
| `label` | string | '' | Label text |
| `placeholder` | string | '' | Placeholder text |
| `value` | string | '' | Default value |
| `required` | boolean | false | Mark field as required |
| `readonly` | boolean | false | Make field readonly |
| `disabled` | boolean | false | Disable the field |
| `min` | number | null | Minimum value (for number/date inputs) |
| `max` | number | null | Maximum value (for number/date inputs) |
| `step` | number | null | Step value (for number inputs) |
| `maxlength` | number | null | Maximum character length |
| `help-text` | string | '' | Help text displayed below input |
| `icon` | string | '' | Icon class (e.g., 'bx bx-search') |
| `icon-position` | string | 'left' | Icon position ('left' or 'right') |
| `container-class` | string | 'mb-3' | Container div class |
| `label-class` | string | 'form-label' | Label class |
| `input-class` | string | 'form-control' | Input class |
| `rows` | number | 3 | Number of rows (for textarea) |
| `accept` | string | '' | Accepted file types (for file input) |
| `multiple` | boolean | false | Allow multiple file selection |
| `pattern` | string | '' | Validation pattern (regex) |
| `autocomplete` | string | '' | Autocomplete attribute |

---

## Validation

The component automatically displays Laravel validation errors:

```php
// In your controller
$request->validate([
    'product_name' => 'required|max:255',
    'email' => 'required|email',
    'price' => 'required|numeric|min:0',
]);
```

Errors will automatically appear below the corresponding input field in red text.

### jQuery Validation Plugin Support

The component also includes built-in support for **jQuery Validation Plugin**. Each input field automatically includes a hidden error label with the format:

```html
<label id="{name}-error" class="text-danger error" for="{inputId}" style="display: none;"></label>
```

**Example with jQuery Validation:**

```javascript
$("#productCreateForm").validate({
    rules: {
        product_name: { required: true },
        email: { required: true, email: true },
        price: { required: true, number: true, min: 0 }
    },
    messages: {
        product_name: { required: 'Product name is required' },
        email: { 
            required: 'Email is required',
            email: 'Please enter a valid email'
        },
        price: { 
            required: 'Price is required',
            number: 'Price must be a number',
            min: 'Price must be greater than 0'
        }
    }
});
```

The validation errors will automatically appear in the pre-generated error labels.

---

## Styling

The component uses Bootstrap 5 classes by default. You can customize by:

1. **Passing custom classes:**
```blade
<x-input-field 
    name="custom" 
    input-class="form-control custom-input-class"
    container-class="mb-4 custom-container"
/>
```

2. **Global styling in CSS:**
```css
.form-control {
    border-radius: 8px;
    padding: 12px;
}
```

---

## Tips & Best Practices

1. **Always provide a name:** The `name` attribute is essential for form submission
2. **Use meaningful labels:** Clear labels improve accessibility and UX
3. **Add help text for complex fields:** Guide users with helpful hints
4. **Mark required fields:** Use `required` prop for mandatory fields
5. **Use appropriate input types:** This enables browser validation and better mobile keyboards
6. **Leverage icons:** Icons can make forms more visually appealing and intuitive
7. **Keep consistent spacing:** Use default `container-class="mb-3"` for uniform spacing

---

## Browser Compatibility

- ✅ Chrome/Edge (latest)
- ✅ Firefox (latest)
- ✅ Safari (latest)
- ✅ Mobile browsers (iOS Safari, Chrome Mobile)

---

## Support

For issues or questions, refer to:
- Laravel Blade Components: https://laravel.com/docs/blade#components
- Bootstrap Forms: https://getbootstrap.com/docs/5.0/forms/overview/
