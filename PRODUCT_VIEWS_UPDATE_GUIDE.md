# Product Views Update Guide

## Overview
This guide provides the field mapping changes needed to update the product views to work with the new schema.

## Field Name Changes

### Main Field Mappings

| Old Field Name | New Field Name | Notes |
|---------------|----------------|-------|
| `name` | `product_name` | Product name field |
| `image` | `thumbnail_image` | Main product image |
| `price` | `sell_price` | Selling price |
| `discount` | `discount_value` | Discount amount |
| `quantity` | `total_stock` | Total stock quantity |
| `status` | `is_active` | Active/Inactive status |
| `published_at` | ❌ REMOVED | No longer used |
| `added_by_type` | `added_by_role` | Changed to ENUM |

### New Required Fields

#### Basic Info
- `product_type` (required) - Dropdown: simple, variable, digital, service
- `sku` (optional) - Text input
- `barcode` (optional) - Text input
- `short_description` (optional) - Textarea (now in main form, not separate)
- `full_description` (optional) - Rich text editor (now in main form, not separate)

#### Category & Brand
- `child_category_id` (optional) - Dropdown (cascading from subcategory)
- `brand_id` (optional) - Dropdown
- `collection_id` (optional) - Dropdown

#### Pricing
- `purchase_price` (optional) - Number input
- `original_price` (optional) - Number input
- `discount_type` (required) - Radio/Select: flat, percentage
- `gst_rate` (optional) - Number input (0-100)
- `tax_included` (optional) - Checkbox
- `commission_type` (required) - Radio/Select: flat, percentage
- `commission_value` (optional) - Number input

#### Inventory
- `stock_type` (required) - Radio/Select: limited, unlimited
- `low_stock_alert` (optional) - Number input (default: 10)
- `warehouse_location` (optional) - Text input

#### Media
- `video_url` (optional) - URL input
- `image_alt_text` (optional) - Text input

#### Shipping
- `weight` (optional) - Number input (kg)
- `length` (optional) - Number input (cm)
- `width` (optional) - Number input (cm)
- `height` (optional) - Number input (cm)
- `shipping_class` (required) - Radio/Select: normal, heavy (default: normal)
- `free_shipping` (optional) - Checkbox
- `cod_available` (optional) - Checkbox (default: true)

#### Status & Workflow
- `product_status` (required) - Select: draft, pending, approved, rejected
- `is_featured` (optional) - Checkbox
- `is_returnable` (optional) - Checkbox (default: true)
- `return_days` (optional) - Number input (default: 7)

#### SEO (now in main form)
- `meta_title` (optional) - Text input
- `meta_description` (optional) - Textarea
- `meta_keywords` (optional) - Text input
- `search_tags` (optional) - Text input

## View-Specific Updates

### index.blade.php (Product List)

**DataTable Columns to Update:**
```javascript
// Old columns
{ data: 'name', name: 'name' }
{ data: 'image', name: 'image' }
{ data: 'price', name: 'price' }
{ data: 'status', name: 'status' }

// New columns
{ data: 'product_name', name: 'product_name' }
{ data: 'thumbnail_image', name: 'thumbnail_image' }
{ data: 'sell_price', name: 'sell_price' }
{ data: 'is_active', name: 'is_active' }
{ data: 'product_status', name: 'product_status' } // NEW
```

**Additional Columns to Consider:**
- Brand name
- Collection name
- Stock status (low stock indicator)
- Product type badge

### create.blade.php (Create Product)

**Form Structure Recommendation:**
```html
<!-- Use tabs or accordion for better organization -->
<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="tab" href="#basic-info">Basic Info</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#pricing">Pricing</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#inventory">Inventory</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#media">Media</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#shipping">Shipping</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#seo">SEO</a>
    </li>
</ul>
```

**Removed Sections:**
- Product Information section (fields now in main form)
- Separate meta fields section (now in SEO tab)

**JavaScript Needed:**
```javascript
// Cascading dropdowns
- Category → Subcategory → Child Category
- Stock type toggle (show/hide stock fields if unlimited)
- Discount type toggle (flat/percentage)
- Commission type toggle (flat/percentage)
- Product type specific fields (digital products don't need shipping)
```

### edit.blade.php (Edit Product)

**Same as create.blade.php plus:**
- Pre-fill all existing values
- Show current thumbnail image with option to change
- Display gallery images with delete option
- Show product history (created_at, updated_at, approved_at)
- Display approval status and approver info

### show.blade.php (View Product)

**Display Sections:**
```html
1. Product Overview
   - Product name, SKU, Barcode
   - Product type badge
   - Status badges (is_active, product_status, is_featured)
   - Thumbnail image

2. Categorization
   - Category → Subcategory → Child Category breadcrumb
   - Brand
   - Collection

3. Pricing Details
   - Purchase price
   - Original price
   - Sell price
   - Discount (type + value)
   - Final price (calculated)
   - GST rate
   - Commission (type + value)

4. Inventory
   - Stock type
   - Total stock
   - Reserved stock
   - Available stock
   - Low stock alert threshold
   - Warehouse location

5. Media
   - Thumbnail image
   - Gallery images
   - Video URL
   - Image alt text

6. Shipping Information
   - Dimensions (L × W × H)
   - Weight
   - Shipping class
   - Free shipping status
   - COD availability

7. Product Details
   - Short description
   - Full description

8. Return Policy
   - Returnable status
   - Return days

9. SEO Information
   - Meta title
   - Meta description
   - Meta keywords
   - Search tags

10. Audit Information
    - Added by (role + user)
    - Owner
    - Seller
    - Branch
    - Approved by
    - Approved at
    - Created at
    - Updated at
```

## JavaScript Utilities

### Cascading Dropdowns
```javascript
// Category change
$('#category_id').on('change', function() {
    loadSubCategories($(this).val());
});

// Subcategory change
$('#sub_category_id').on('change', function() {
    loadChildCategories($(this).val());
});
```

### Dynamic Field Visibility
```javascript
// Stock type toggle
$('input[name="stock_type"]').on('change', function() {
    if ($(this).val() === 'unlimited') {
        $('#stock-fields').hide();
    } else {
        $('#stock-fields').show();
    }
});

// Product type specific fields
$('select[name="product_type"]').on('change', function() {
    if ($(this).val() === 'digital') {
        $('#shipping-section').hide();
    } else {
        $('#shipping-section').show();
    }
});
```

### Price Calculation
```javascript
// Calculate final price
function calculateFinalPrice() {
    let sellPrice = parseFloat($('#sell_price').val()) || 0;
    let discountType = $('input[name="discount_type"]:checked').val();
    let discountValue = parseFloat($('#discount_value').val()) || 0;
    
    let finalPrice = sellPrice;
    if (discountType === 'percentage') {
        finalPrice = sellPrice - (sellPrice * discountValue / 100);
    } else {
        finalPrice = sellPrice - discountValue;
    }
    
    $('#final_price_display').text(finalPrice.toFixed(2));
}
```

## Validation Rules

### Client-Side Validation
```javascript
// Required fields
- product_type
- product_name
- category_id
- sell_price
- discount_type
- commission_type
- stock_type
- total_stock
- shipping_class
- product_status
- is_active

// Conditional required
- sub_category_id (if child_category_id is selected)
- total_stock (if stock_type is 'limited')

// Format validation
- sku: alphanumeric, unique
- barcode: alphanumeric
- video_url: valid URL
- email fields: valid email format
- numeric fields: min 0
- gst_rate: 0-100
```

## Default Values

Set these defaults in the form:
```javascript
{
    product_type: 'simple',
    discount_type: 'flat',
    discount_value: 0,
    gst_rate: 0,
    tax_included: false,
    commission_type: 'percentage',
    commission_value: 0,
    stock_type: 'limited',
    low_stock_alert: 10,
    shipping_class: 'normal',
    free_shipping: false,
    cod_available: true,
    product_status: 'draft',
    is_active: 'active',
    is_featured: false,
    is_returnable: true,
    return_days: 7
}
```

## Important Notes

1. **Removed productInformation relationship**: All fields previously in `product_information` table are now in the main `products` table
2. **Collection is now one-to-many**: Changed from pivot table to direct foreign key
3. **Image field renamed**: `image` → `thumbnail_image`
4. **Status field renamed**: `status` → `is_active`
5. **New workflow field**: `product_status` for draft/pending/approved/rejected workflow
6. **Automatic calculations**: `available_stock` = `total_stock` - `reserved_stock`

## Testing Checklist

- [ ] Create product form displays all new fields
- [ ] All dropdowns populate correctly
- [ ] Cascading dropdowns work (category → subcategory → child category)
- [ ] Image upload works for thumbnail
- [ ] Gallery images upload works
- [ ] Form validation works for all required fields
- [ ] Edit form pre-fills all existing data
- [ ] Update saves all fields correctly
- [ ] Show page displays all product information
- [ ] DataTable displays updated columns
- [ ] Status toggle works with new field name
- [ ] Delete functionality works
- [ ] Search and filter work with new fields
