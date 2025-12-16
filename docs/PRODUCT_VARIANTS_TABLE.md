# Product Variants Table - Documentation

**Date**: December 16, 2025  
**Status**: ✅ Complete

---

## 📋 Overview

Created the `product_variants` table to handle product variations (e.g., different sizes, colors, or combinations) with individual pricing and SKU tracking.

---

## 🗂️ Table Structure

### **product_variants** Table

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | BIGINT | PRIMARY KEY | Unique variant ID |
| `product_id` | BIGINT | FOREIGN KEY | Reference to products table |
| `sku` | VARCHAR(100) | UNIQUE | Stock Keeping Unit for variant |
| `purchase_price` | DECIMAL(10,2) | NULLABLE | Cost price for this variant |
| `original_price` | DECIMAL(10,2) | NULLABLE | Original MRP for this variant |
| `sell_price` | DECIMAL(10,2) | REQUIRED | Selling price for this variant |
| `is_active` | BOOLEAN | DEFAULT true | Variant active status |
| `created_at` | TIMESTAMP | AUTO | Creation timestamp |
| `updated_at` | TIMESTAMP | AUTO | Last update timestamp |

---

## 🔗 Relationships

### **Belongs To**:
```php
ProductVariant → Product (Many to One)
```

### **Product Has Many Variants**:
```php
Product → ProductVariants (One to Many)
```

---

## 💡 Use Cases

### **1. Size Variations**
```php
Product: "Cotton T-Shirt"
├── Variant 1: Small (SKU: TS-S-001, Price: ₹499)
├── Variant 2: Medium (SKU: TS-M-001, Price: ₹499)
├── Variant 3: Large (SKU: TS-L-001, Price: ₹549)
└── Variant 4: XL (SKU: TS-XL-001, Price: ₹599)
```

### **2. Color Variations**
```php
Product: "Nike Shoes"
├── Variant 1: Red (SKU: NS-RED-001, Price: ₹5999)
├── Variant 2: Blue (SKU: NS-BLUE-001, Price: ₹5999)
└── Variant 3: Black (SKU: NS-BLACK-001, Price: ₹6499)
```

### **3. Size + Color Combinations**
```php
Product: "Formal Shirt"
├── Variant 1: Small-White (SKU: FS-S-W-001, Price: ₹1299)
├── Variant 2: Small-Blue (SKU: FS-S-B-001, Price: ₹1299)
├── Variant 3: Medium-White (SKU: FS-M-W-001, Price: ₹1299)
├── Variant 4: Medium-Blue (SKU: FS-M-B-001, Price: ₹1299)
├── Variant 5: Large-White (SKU: FS-L-W-001, Price: ₹1399)
└── Variant 6: Large-Blue (SKU: FS-L-B-001, Price: ₹1399)
```

---

## 🎯 Model Features

### **ProductVariant Model**

#### **Fillable Fields**:
```php
[
    'product_id',
    'sku',
    'purchase_price',
    'original_price',
    'sell_price',
    'is_active',
]
```

#### **Casts**:
```php
[
    'is_active' => 'boolean',
    'purchase_price' => 'decimal:2',
    'original_price' => 'decimal:2',
    'sell_price' => 'decimal:2',
]
```

#### **Helper Methods**:

##### **1. Pricing Calculations**:
```php
// Get final price after discount
$variant->getFinalPrice()

// Get discount amount
$variant->getDiscountAmount()

// Get profit margin percentage
$variant->getProfitMargin()

// Get profit amount
$variant->getProfitAmount()
```

##### **2. Status Checks**:
```php
// Check if variant is active (variant + product both active)
$variant->isActive()
```

##### **3. Query Scopes**:
```php
// Get only active variants
ProductVariant::active()->get()
```

---

## 📊 Example Usage

### **Creating Variants**:
```php
$product = Product::find(1);

// Create size variants
$product->productVariants()->create([
    'sku' => 'TS-S-001',
    'purchase_price' => 200,
    'original_price' => 699,
    'sell_price' => 499,
    'is_active' => true,
]);

$product->productVariants()->create([
    'sku' => 'TS-M-001',
    'purchase_price' => 200,
    'original_price' => 699,
    'sell_price' => 499,
    'is_active' => true,
]);
```

### **Querying Variants**:
```php
// Get all variants of a product
$variants = $product->productVariants;

// Get only active variants
$activeVariants = $product->productVariants()->active()->get();

// Get variant by SKU
$variant = ProductVariant::where('sku', 'TS-S-001')->first();

// Get product from variant
$product = $variant->product;
```

### **Pricing Calculations**:
```php
$variant = ProductVariant::find(1);

// Get final price (after product discount)
$finalPrice = $variant->getFinalPrice();

// Get profit margin
$profitMargin = $variant->getProfitMargin(); // Returns percentage

// Get profit amount
$profitAmount = $variant->getProfitAmount(); // Returns amount
```

---

## 🔄 Workflow

### **Product with Variations**:
```
1. Create Product (set has_variation = true)
2. Create Multiple Variants with different:
   - SKUs
   - Prices
   - Attributes (handled separately)
3. Each variant can have:
   - Individual pricing
   - Individual stock (if needed)
   - Individual status
```

### **Discount Handling**:
```
Product Level Discount applies to ALL variants:
- If product has 10% discount
- All variants get 10% off their sell_price
- Calculated via getFinalPrice() method
```

---

## 💰 Pricing Structure

### **Example Calculation**:
```php
Variant Details:
- Purchase Price: ₹200
- Original Price: ₹699
- Sell Price: ₹499

Product Discount: 10%

Calculations:
- Discount Amount: ₹499 × 10% = ₹49.90
- Final Price: ₹499 - ₹49.90 = ₹449.10
- Profit Amount: ₹499 - ₹200 = ₹299
- Profit Margin: (₹299 / ₹499) × 100 = 59.92%
```

---

## 🔐 Constraints

### **Foreign Key**:
- `product_id` → `products.id` (CASCADE on delete)
- When product is deleted, all variants are automatically deleted

### **Unique Constraint**:
- `sku` must be unique across all variants
- Prevents duplicate SKUs

---

## 📈 Benefits

1. ✅ **Individual Pricing**: Each variant can have different prices
2. ✅ **Unique SKU**: Track each variant separately
3. ✅ **Profit Tracking**: Calculate profit per variant
4. ✅ **Flexible Discounts**: Product-level discounts apply to all variants
5. ✅ **Active Status**: Enable/disable variants independently
6. ✅ **Cascade Delete**: Clean up when product is removed

---

## 🎨 Integration with Product

### **Product Model Updated**:
```php
// In Product model
public function productVariants()
{
    return $this->hasMany(ProductVariant::class);
}

// Usage
$product->productVariants; // Get all variants
$product->productVariants()->active()->get(); // Get active variants
```

### **Check if Product has Variations**:
```php
if ($product->has_variation) {
    // Show variant selector
    $variants = $product->productVariants;
} else {
    // Show simple product
}
```

---

## ✅ Migration Status

- ✅ Migration created
- ✅ Migration executed successfully
- ✅ ProductVariant model created
- ✅ Product model updated with relationship
- ✅ Helper methods implemented
- ✅ **Ready to use!**

---

## 📁 Files Created

1. `database/migrations/2025_12_16_172958_create_product_variants_table.php` ✨ NEW
2. `app/Models/ProductVariant.php` ✨ NEW
3. `app/Models/Product.php` 🔄 UPDATED (added productVariants relationship)

---

## 🎉 Summary

Successfully created the **product_variants** table with:

- ✅ **9 columns** for variant management
- ✅ **Foreign key** to products table
- ✅ **Unique SKU** constraint
- ✅ **Individual pricing** (purchase, original, sell)
- ✅ **Active status** flag
- ✅ **ProductVariant model** with helper methods
- ✅ **Pricing calculations** (final price, discount, profit)
- ✅ **Relationship** with Product model

**Use Case**: Perfect for products with multiple variations like:
- Clothing (sizes, colors)
- Shoes (sizes, colors)
- Electronics (storage, color)
- Any product with multiple options

**Status**: ✅ Complete & Production-Ready
