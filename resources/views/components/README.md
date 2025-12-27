# Input Field Component - Getting Started

## 📦 What Was Created

A reusable Blade component for form input fields with the following files:

1. **`input-field.blade.php`** - The main component file
2. **`INPUT-FIELD-DOCS.md`** - Complete documentation
3. **`input-field-examples.blade.php`** - Before/after refactoring examples
4. **`input-field-quick-ref.blade.php`** - Quick reference guide

---

## 🚀 Quick Start

### Basic Usage

Replace this:
```blade
<div class="mb-3">
    <label for="product_name" class="form-label">Product Name <span class="text-danger">*</span></label>
    <input type="text" class="form-control" id="product_name" name="product_name" 
           placeholder="Enter product name" required>
</div>
```

With this:
```blade
<x-input-field 
    name="product_name" 
    label="Product Name" 
    placeholder="Enter product name"
    required 
/>
```

---

## ✨ Key Features

- ✅ **Multiple input types**: text, number, email, password, url, date, file, textarea, select
- ✅ **Icon support**: Add icons on left or right side
- ✅ **Validation**: Automatic Laravel validation error display
- ✅ **jQuery Validation**: Built-in support for jQuery Validation plugin
- ✅ **Help text**: Built-in support for helper text
- ✅ **Required indicators**: Automatic asterisk for required fields
- ✅ **Old values**: Automatically retains old input values
- ✅ **Bootstrap 5**: Fully styled with Bootstrap classes
- ✅ **Customizable**: Override any class or attribute

---

## 📝 Common Examples

### Text Input
```blade
<x-input-field name="name" label="Name" required />
```

### Number Input
```blade
<x-input-field type="number" name="price" label="Price" step="0.01" min="0" />
```

### Textarea
```blade
<x-input-field type="textarea" name="description" label="Description" rows="5" />
```

### Select Dropdown
```blade
<x-input-field type="select" name="category" label="Category" required>
    @foreach($categories as $category)
        <option value="{{ $category->id }}">{{ $category->name }}</option>
    @endforeach
</x-input-field>
```

### With Help Text
```blade
<x-input-field 
    name="sku" 
    label="SKU" 
    help-text="Unique product identifier"
/>
```

### With Icon
```blade
<x-input-field 
    type="email" 
    name="email" 
    label="Email" 
    icon="bx bx-envelope"
/>
```

---

## 🎯 How to Use in Your Forms

### Step 1: Replace Traditional Inputs

Find inputs in your forms like `create.blade.php` and `edit.blade.php`

### Step 2: Use the Component

Replace with `<x-input-field>` syntax

### Step 3: Test

- Submit the form
- Check validation errors display correctly
- Verify old values are retained on validation failure

---

## 📚 Documentation Files

- **Full Documentation**: `INPUT-FIELD-DOCS.md`
- **Examples**: `input-field-examples.blade.php`
- **Quick Reference**: `input-field-quick-ref.blade.php`

---

## 🔧 Customization

### Custom Classes
```blade
<x-input-field 
    name="custom" 
    container-class="mb-4 col-md-6"
    label-class="form-label fw-bold"
    input-class="form-control form-control-lg"
/>
```

### Custom Attributes
```blade
<x-input-field 
    name="phone" 
    pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"
    autocomplete="tel"
/>
```

---

## ✅ Benefits

1. **Cleaner Code**: Reduce 7 lines to 1-5 lines
2. **Consistency**: Same styling across all forms
3. **Maintainability**: Update component once, affects all forms
4. **Validation**: Built-in error display
5. **Accessibility**: Proper label/input associations
6. **Developer Experience**: Faster form development

---

## 🎨 Supported Input Types

- `text` (default)
- `number`
- `email`
- `password`
- `url`
- `date`
- `file`
- `textarea`
- `select`

---

## 💡 Pro Tips

1. Always provide a `name` attribute
2. Use `required` prop for mandatory fields
3. Add `help-text` for complex fields
4. Use appropriate `type` for better UX
5. Leverage icons for visual appeal
6. Keep consistent spacing with default `mb-3`

---

## 🐛 Troubleshooting

### Component not found?
Make sure the file is at: `resources/views/components/input-field.blade.php`

### Validation errors not showing?
Ensure your controller returns validation errors properly:
```php
$request->validate([
    'field_name' => 'required|...',
]);
```

### Old values not retained?
The component uses `old()` helper automatically. Make sure you're using Laravel's validation.

### Icons not showing?
Ensure Boxicons CSS is loaded in your layout:
```html
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
```

---

## 📞 Next Steps

1. ✅ Review the documentation (`INPUT-FIELD-DOCS.md`)
2. ✅ Check the examples (`input-field-examples.blade.php`)
3. ✅ Start refactoring your forms
4. ✅ Test thoroughly
5. ✅ Enjoy cleaner, more maintainable code!

---

## 🎉 You're Ready!

Start using `<x-input-field>` in your forms today and enjoy cleaner, more maintainable code!
