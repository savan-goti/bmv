# Migration Testing Guide

## Testing the Compressed Migrations

### Prerequisites
- ⚠️ **NEVER run these commands on production database**
- Use a separate test database
- Backup your current database before testing

### Step 1: Setup Test Environment

```bash
# Create a backup of your current .env
cp .env .env.backup

# Update .env to use a test database
# Change DB_DATABASE to a test database name
DB_DATABASE=bmv_test
```

### Step 2: Create Test Database

```sql
CREATE DATABASE bmv_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Or using Laravel:

```bash
php artisan db:create bmv_test
```

### Step 3: Run Fresh Migrations

```bash
# Clear any cached migration data
php artisan config:clear
php artisan cache:clear

# Run fresh migrations
php artisan migrate:fresh --seed
```

### Step 4: Verify Tables

```bash
# Check if all tables were created
php artisan db:show

# Or check specific tables
php artisan db:table admins
php artisan db:table owners
php artisan db:table staffs
php artisan db:table sellers
php artisan db:table customers
php artisan db:table categories
php artisan db:table products
```

### Step 5: Verify Columns

Run this SQL to check all columns in each table:

```sql
-- Check Admins Table
DESCRIBE admins;

-- Check Owners Table
DESCRIBE owners;

-- Check Staffs Table
DESCRIBE staffs;

-- Check Sellers Table
DESCRIBE sellers;

-- Check Customers Table
DESCRIBE customers;

-- Check Categories Table
DESCRIBE categories;

-- Check Products Table
DESCRIBE products;
```

### Step 6: Test Foreign Keys

```sql
-- Check foreign key constraints
SELECT 
    TABLE_NAME,
    COLUMN_NAME,
    CONSTRAINT_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME
FROM
    INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE
    REFERENCED_TABLE_SCHEMA = 'bmv_test'
    AND TABLE_NAME IN ('admins', 'owners', 'staffs', 'sellers', 'customers', 'products')
ORDER BY TABLE_NAME, COLUMN_NAME;
```

### Step 7: Rollback Test

```bash
# Test rollback functionality
php artisan migrate:rollback --step=5

# Re-run migrations
php artisan migrate
```

### Step 8: Restore Production Environment

```bash
# Restore original .env
cp .env.backup .env

# Clear cache
php artisan config:clear
php artisan cache:clear
```

## Expected Results

### Admins Table Should Have:
- ✅ 31 columns including: id, owner_id, profile_image, name, email, google_id, position, two_factor_*, login_verification_*, etc.

### Owners Table Should Have:
- ✅ 27 columns including: id, name, email, google_id, business_*, two_factor_*, login_verification_*, etc.

### Staffs Table Should Have:
- ✅ 32 columns including: id, admin_id, profile_image, name, email, google_id, position, permissions, two_factor_*, etc.

### Sellers Table Should Have:
- ✅ 51 columns including: id, business_*, owner_name, email, google_id, kyc_*, bank_*, approval_*, two_factor_*, etc.

### Customers Table Should Have:
- ✅ 23 columns including: id, profile_image, username, name, email, phone, location data, social_links, etc.

### Categories Table Should Have:
- ✅ 7 columns including: id, category_type, name, slug, image, status, timestamps

### Products Table Should Have:
- ✅ 57 columns including: id, product_type, categories, brand_id, collection_id, pricing, inventory, shipping, SEO, etc.

## Troubleshooting

### Issue: Foreign Key Constraint Fails

**Solution:** Check the migration order. Ensure parent tables are created before child tables.

```bash
# Check migration order
php artisan migrate:status
```

### Issue: Column Already Exists

**Solution:** This means you're running on an existing database. Use `migrate:fresh` on a clean database.

```bash
# Drop all tables and re-migrate
php artisan migrate:fresh
```

### Issue: Unknown Column in Field List

**Solution:** Check if the Enum class exists and is properly imported.

```bash
# Check if App\Enums\Status exists
php artisan tinker
>>> App\Enums\Status::Active->value
```

## Success Criteria

✅ All 39 migrations run successfully
✅ All tables created with correct columns
✅ All foreign keys established correctly
✅ No errors during migration
✅ Rollback works correctly
✅ Models can interact with tables properly

## Next Steps After Successful Testing

1. ✅ Commit the compressed migrations to version control
2. ✅ Update team documentation
3. ✅ Inform team members about the new structure
4. ✅ Keep `migrations_backup/` folder for reference
5. ✅ Update any deployment scripts if needed

## Important Notes

- 🔴 **Production databases are NOT affected** - They already have all columns from previous migrations
- 🟢 **Fresh installations** will use the new compressed migrations
- 🟡 **Existing development databases** can continue using current schema or refresh with new migrations
- 🔵 **Backup folder** contains all original migrations for reference
