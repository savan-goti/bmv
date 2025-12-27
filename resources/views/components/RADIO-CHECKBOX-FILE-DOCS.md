# Radio, Checkbox, and File Input Components

## Overview
Specialized Blade components for handling radio buttons, checkboxes, and file inputs with built-in validation support.

---

## 📻 Radio Button Component

### Single Radio Button: `<x-radio>`

**Basic Usage:**
```blade
<x-radio 
    name="stock_type" 
    value="limited" 
    label="Limited" 
    checked 
/>

<x-radio 
    name="stock_type" 
    value="unlimited" 
    label="Unlimited" 
/>
```

**With Inline Display:**
```blade
<div class="mb-3">
    <label class="form-label">Stock Type <span class="text-danger">*</span></label>
    <div>
        <x-radio name="stock_type" value="limited" label="Limited" checked inline />
        <x-radio name="stock_type" value="unlimited" label="Unlimited" inline />
    </div>
    <label id="stock_type-error" class="text-danger error" for="stock_type" style="display: none;"></label>
</div>
```

### Radio Group: `<x-radio-group>`

**Recommended for Multiple Options:**
```blade
<x-radio-group 
    name="discount_type" 
    label="Discount Type" 
    :options="[
        'flat' => 'Flat',
        'percentage' => 'Percentage'
    ]"
    selected="flat"
    required
    inline
/>
```

**With Help Text:**
```blade
<x-radio-group 
    name="commission_type" 
    label="Commission Type" 
    :options="[
        'flat' => 'Flat Amount',
        'percentage' => 'Percentage'
    ]"
    selected="percentage"
    help-text="Choose how commission is calculated"
    required
/>
```

**Available Props:**
| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `name` | string | '' | Input name (required) |
| `label` | string | '' | Group label |
| `options` | array | [] | Options as key=>value pairs |
| `selected` | string | '' | Selected value |
| `required` | boolean | false | Mark as required |
| `disabled` | boolean | false | Disable all options |
| `inline` | boolean | true | Display inline |
| `help-text` | string | '' | Help text |

---

## ☑️ Checkbox Component: `<x-checkbox>`

**Basic Usage:**
```blade
<x-checkbox 
    name="is_featured" 
    value="1" 
    label="Featured Product" 
/>
```

**Checked by Default:**
```blade
<x-checkbox 
    name="is_returnable" 
    value="1" 
    label="Returnable" 
    checked 
/>
```

**With Help Text:**
```blade
<x-checkbox 
    name="tax_included" 
    value="1" 
    label="Tax Included in Price" 
    help-text="Check if the price already includes tax"
/>
```

**Inline Checkboxes:**
```blade
<div class="mb-3">
    <label class="form-label">Shipping Options</label>
    <div>
        <x-checkbox name="free_shipping" value="1" label="Free Shipping" inline />
        <x-checkbox name="cod_available" value="1" label="COD Available" checked inline />
    </div>
</div>
```

**Required Checkbox:**
```blade
<x-checkbox 
    name="agree_terms" 
    value="1" 
    label="I agree to the terms and conditions" 
    required 
/>
```

**Available Props:**
| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `name` | string | '' | Input name (required) |
| `value` | string | '' | Checkbox value |
| `label` | string | '' | Label text |
| `checked` | boolean | false | Checked state |
| `required` | boolean | false | Mark as required |
| `disabled` | boolean | false | Disable checkbox |
| `inline` | boolean | false | Display inline |
| `help-text` | string | '' | Help text |

---

## 📁 File Input Component: `<x-file-input>`

**Basic File Upload:**
```blade
<x-file-input 
    name="document" 
    label="Upload Document" 
/>
```

**Image Upload with Accept:**
```blade
<x-file-input 
    name="thumbnail" 
    label="Thumbnail Image" 
    accept="image/*"
    help-text="Recommended size: 800x800px. Max file size: 5MB"
/>
```

**Multiple File Upload:**
```blade
<x-file-input 
    name="gallery_images[]" 
    label="Gallery Images" 
    accept="image/*"
    multiple
    help-text="You can upload multiple images. Max 10 images, 5MB each"
/>
```

**With Preview:**
```blade
<x-file-input 
    name="profile_picture" 
    label="Profile Picture" 
    accept="image/*"
    preview
    help-text="Upload your profile picture"
/>
```

**Required File:**
```blade
<x-file-input 
    name="resume" 
    label="Resume" 
    accept=".pdf,.doc,.docx"
    required
    help-text="Upload your resume in PDF or DOC format"
/>
```

**Available Props:**
| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `name` | string | '' | Input name (required) |
| `label` | string | '' | Label text |
| `accept` | string | '' | Accepted file types |
| `multiple` | boolean | false | Allow multiple files |
| `required` | boolean | false | Mark as required |
| `disabled` | boolean | false | Disable input |
| `preview` | boolean | false | Show image preview |
| `help-text` | string | '' | Help text |

---

## 🎯 Complete Form Example

```blade
<form id="productForm" method="POST" enctype="multipart/form-data">
    @csrf

    {{-- Radio Group --}}
    <x-radio-group 
        name="stock_type" 
        label="Stock Type" 
        :options="[
            'limited' => 'Limited',
            'unlimited' => 'Unlimited'
        ]"
        selected="limited"
        required
    />

    {{-- Individual Radio Buttons --}}
    <div class="mb-3">
        <label class="form-label">Discount Type <span class="text-danger">*</span></label>
        <div>
            <x-radio name="discount_type" value="flat" label="Flat" checked inline />
            <x-radio name="discount_type" value="percentage" label="Percentage" inline />
        </div>
        <label id="discount_type-error" class="text-danger error" for="discount_type" style="display: none;"></label>
    </div>

    {{-- Checkboxes --}}
    <x-checkbox 
        name="is_featured" 
        value="1" 
        label="Featured Product" 
    />

    <x-checkbox 
        name="is_returnable" 
        value="1" 
        label="Returnable" 
        checked 
    />

    {{-- File Upload --}}
    <x-file-input 
        name="thumbnail" 
        label="Thumbnail Image" 
        accept="image/*"
        preview
        help-text="Recommended size: 800x800px"
    />

    {{-- Multiple Files --}}
    <x-file-input 
        name="gallery[]" 
        label="Gallery Images" 
        accept="image/*"
        multiple
        help-text="Upload multiple images"
    />

    <button type="submit" class="btn btn-primary">Submit</button>
</form>
```

---

## 🔄 Refactoring Existing Code

### Before (Traditional Radio Buttons)
```blade
<div class="mb-3">
    <label class="form-label">Stock Type <span class="text-danger">*</span></label>
    <div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="stock_type" id="stock_limited" value="limited" checked>
            <label class="form-check-label" for="stock_limited">Limited</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="stock_type" id="stock_unlimited" value="unlimited">
            <label class="form-check-label" for="stock_unlimited">Unlimited</label>
        </div>
    </div>
    <label id="stock_type-error" class="text-danger error" for="stock_type" style="display: none;"></label>
</div>
```

### After (Radio Group Component)
```blade
<x-radio-group 
    name="stock_type" 
    label="Stock Type" 
    :options="['limited' => 'Limited', 'unlimited' => 'Unlimited']"
    selected="limited"
    required
/>
```

**Result:** 15 lines → 6 lines (60% reduction!)

---

## ✅ Validation Support

All components include:
- ✅ Laravel validation error display
- ✅ jQuery Validation error labels
- ✅ Old value retention
- ✅ Required field indicators

### jQuery Validation Example
```javascript
$("#productForm").validate({
    rules: {
        stock_type: { required: true },
        is_featured: { required: true },
        thumbnail: { required: true, extension: "jpg|jpeg|png" }
    },
    messages: {
        stock_type: { required: 'Please select stock type' },
        is_featured: { required: 'Please check if featured' },
        thumbnail: { 
            required: 'Thumbnail is required',
            extension: 'Please upload a valid image'
        }
    }
});
```

---

## 🎨 Styling

All components use Bootstrap 5 classes by default:
- Radio: `form-check`, `form-check-input`, `form-check-label`
- Checkbox: `form-check`, `form-check-input`, `form-check-label`
- File: `form-control`

You can customize classes via props:
```blade
<x-checkbox 
    name="custom" 
    label="Custom" 
    input-class="form-check-input custom-checkbox"
    label-class="form-check-label fw-bold"
/>
```

---

## 📦 Summary

**New Components Created:**
1. ✅ `<x-radio>` - Single radio button
2. ✅ `<x-radio-group>` - Radio button group
3. ✅ `<x-checkbox>` - Checkbox
4. ✅ `<x-file-input>` - File upload

**Benefits:**
- Cleaner, more maintainable code
- Automatic validation support
- Old value retention
- Consistent styling
- Easy to use and customize

**Use These Components For:**
- ✅ Product forms (stock type, discount type, etc.)
- ✅ Settings forms (enable/disable features)
- ✅ File uploads (images, documents)
- ✅ Any form with radio buttons or checkboxes
