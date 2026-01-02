# Product Weight Fields Implementation

## Summary
Added `product_weight` and `shipping_weight` fields to the product forms in the Variations section.

## Changes Made

### 1. Database Migration
**File:** `database/migrations/2026_01_02_153444_add_weight_fields_to_products_table.php`

- Added two new decimal columns to the `products` table:
  - `product_weight` (decimal 8,2, nullable) - Actual weight of the product
  - `shipping_weight` (decimal 8,2, nullable) - Weight including packaging
- Both fields are placed in the Variations section after `has_variation`
- Migration has been executed successfully

### 2. Product Model
**File:** `app/Models/Product.php`

- Added `product_weight` and `shipping_weight` to the `$fillable` array in the Variations section
- These fields are now mass-assignable

### 3. Product Create Form
**File:** `resources/views/owner/products/create.blade.php`

- Added two new input fields in the Variations tab within the `.variation-fields` div:
  - **Product Weight (kg)**: Number input with step 0.01, min 0
  - **Shipping Weight (kg)**: Number input with step 0.01, min 0
- Both fields include helpful text to guide users
- Fields are displayed when "This product has variations" checkbox is checked

### 4. Product Edit Form
**File:** `resources/views/owner/products/edit.blade.php`

- Added the same two weight input fields in the Variations tab
- Fields are pre-filled with existing product values using `{{ $product->product_weight }}` and `{{ $product->shipping_weight }}`
- Fields are shown/hidden based on the `has_variation` value

### 5. Product Controller
**File:** `app/Http/Controllers/Owner/ProductController.php`

**Changes in `store()` method:**
- Added validation rules for both fields:
  - `'product_weight' => 'nullable|numeric|min:0'`
  - `'shipping_weight' => 'nullable|numeric|min:0'`
- Added fields to `$productData` array:
  - `'product_weight' => $request->product_weight`
  - `'shipping_weight' => $request->shipping_weight`

**Changes in `update()` method:**
- Added the same validation rules
- Added fields to `$productData` array for updates

This ensures the data is properly validated and saved to the database.

## Field Details

### Product Weight
- **Label:** Product Weight (kg)
- **Type:** Number (decimal)
- **Validation:** Min: 0, Step: 0.01
- **Help Text:** "Actual weight of the product"
- **Database:** Nullable decimal(8,2)

### Shipping Weight
- **Label:** Shipping Weight (kg)
- **Type:** Number (decimal)
- **Validation:** Min: 0, Step: 0.01
- **Help Text:** "Weight including packaging"
- **Database:** Nullable decimal(8,2)

## Usage
These fields appear in the **Variations** tab of the product form, alongside the Color and Size selection dropdowns. They are only visible when the "This product has variations" checkbox is checked.

The fields allow users to specify:
1. The actual weight of the product itself
2. The total shipping weight including packaging materials

This information is useful for:
- Calculating accurate shipping costs
- Managing inventory logistics
- Providing detailed product specifications
- E-commerce platform integrations

## Notes
- Both fields are optional (nullable in database)
- Values are stored in kilograms (kg)
- Supports decimal values up to 2 decimal places
- Maximum value: 999,999.99 kg (based on decimal(8,2) definition)
