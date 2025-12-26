# Migration Files - Before & After Compression

## Current Migration Structure (39 files)

### Core Laravel Tables (3)
1. `0001_01_01_000000_create_users_table.php`
2. `0001_01_01_000001_create_cache_table.php`
3. `0001_01_01_000002_create_jobs_table.php`

### Application Tables (36)

#### Settings & Configuration (1)
4. `2025_11_13_174229_create_settings_table.php`

#### User Management Tables (5) ✅ COMPRESSED
5. `2025_11_22_134825_create_owners_table.php` ✅
6. `2025_11_24_150959_create_admins_table.php` ✅
7. `2025_11_24_151010_create_sellers_table.php` ✅
8. `2025_11_24_151012_create_staffs_table.php` ✅
9. `2025_11_24_151013_create_customers_table.php` ✅

#### Product Catalog Tables (4) ✅ COMPRESSED
10. `2025_11_26_164430_create_categories_table.php` ✅
11. `2025_11_26_164431_create_sub_categories_table.php`
12. `2025_11_26_164432_create_products_table.php` ✅
13. `2025_11_26_164433_create_product_information_table.php`

#### Product Related Tables (9)
14. `2025_11_26_164434_create_product_images_table.php`
15. `2025_12_16_163924_create_child_categories_table.php`
16. `2025_12_16_171051_create_brands_table.php`
17. `2025_12_16_171053_create_collections_table.php`
18. `2025_12_16_171137_create_collection_product_table.php`
19. `2025_12_16_172958_create_product_variants_table.php`
20. `2025_12_16_173220_create_product_variant_attributes_table.php`
21. `2025_12_16_173223_create_product_variant_stock_table.php`
22. `2025_12_19_154000_add_additional_fields_to_product_information_table.php`

#### Product Analytics & Reviews (3)
23. `2025_12_16_173226_create_product_analytics_table.php`
24. `2025_12_16_173229_create_product_reviews_table.php`
25. `2025_12_16_173231_create_product_views_table.php`

#### Organization Structure (4)
26. `2025_11_27_033139_create_job_positions_table.php`
27. `2025_11_27_033403_create_branches_table.php`
28. `2025_11_27_033914_create_branch_positions_table.php`
29. `2025_11_27_035705_add_unique_active_position_constraint_to_branch_positions.php`

#### Password Reset Tokens (4)
30. `2025_11_30_065740_create_admin_password_reset_tokens_table.php`
31. `2025_12_01_135133_create_owner_password_reset_tokens_table.php`
32. `2025_12_02_144500_create_staff_password_reset_tokens_table.php`
33. `2025_12_03_144637_create_seller_password_reset_tokens_table.php`

#### Support System (4)
34. `2025_12_07_193000_create_support_team_members_table.php`
35. `2025_12_07_193100_create_support_departments_table.php`
36. `2025_12_07_193200_create_support_queues_table.php`
37. `2025_12_07_193300_create_support_audit_logs_table.php`

#### Miscellaneous (2)
38. `2025_12_01_143036_create_seller_management_table.php`
39. `2025_12_05_143621_add_guard_to_sessions_table.php`

---

## Removed Files (23) - Moved to migrations_backup/

### Admins Table (5 files removed)
- ❌ `2025_11_26_171752_add_missing_columns_to_admins_and_staffs_tables.php`
- ❌ `2025_11_27_084859_update_position_to_foreign_key_in_admins_and_staffs_tables.php`
- ❌ `2025_12_05_170036_add_two_factor_columns_to_admins_table.php`
- ❌ `2025_12_08_141752_add_login_email_verification_to_admins_table.php`
- ❌ `2025_12_24_191600_add_google_oauth_to_admins_table.php`

### Owners Table (4 files removed)
- ❌ `2025_12_05_162355_add_two_factor_columns_to_owners_table.php`
- ❌ `2025_12_12_154811_add_login_email_verification_to_owners_table.php`
- ❌ `2025_12_20_161346_add_google_oauth_to_owners_table.php`
- ❌ `2025_12_24_172904_add_remember_token_to_owners_table.php`

### Staffs Table (4 files removed)
- ❌ `2025_11_26_171752_add_missing_columns_to_admins_and_staffs_tables.php` (shared)
- ❌ `2025_11_27_084859_update_position_to_foreign_key_in_admins_and_staffs_tables.php` (shared)
- ❌ `2025_12_05_170039_add_two_factor_columns_to_staff_table.php`
- ❌ `2025_12_12_164129_add_login_email_verification_to_staffs_table.php`
- ❌ `2025_12_24_191602_add_google_oauth_to_staffs_table.php`

### Sellers Table (5 files removed)
- ❌ `2025_11_27_060925_add_missing_columns_to_sellers_table.php`
- ❌ `2025_12_01_143033_add_is_approved_by_to_sellers_table.php`
- ❌ `2025_12_06_100146_add_two_factor_columns_to_sellers_table.php`
- ❌ `2025_12_12_164135_add_login_email_verification_to_sellers_table.php`
- ❌ `2025_12_24_191601_add_google_oauth_to_sellers_table.php`

### Customers Table (1 file removed)
- ❌ `2025_11_27_050553_add_missing_columns_to_customers_table.php`

### Categories Table (1 file removed)
- ❌ `2025_12_25_164000_add_category_type_to_categories_table.php`

### Products Table (4 files removed)
- ❌ `2025_12_16_164254_add_child_category_id_to_products_table.php`
- ❌ `2025_12_16_172218_add_brand_id_to_products_table.php`
- ❌ `2025_12_16_172601_add_comprehensive_product_columns_to_products_table.php`
- ❌ `2025_12_18_195900_update_products_table_complete_schema.php`

---

## Statistics

| Metric | Before | After | Reduction |
|--------|--------|-------|-----------|
| Total Files | 62 | 39 | -23 (-37%) |
| User Tables | 11 migrations | 5 migrations | -6 (-55%) |
| Product Tables | 9 migrations | 5 migrations | -4 (-44%) |

## Key Improvements

✅ **Cleaner Structure** - Each main table now has a single comprehensive migration
✅ **Easier Onboarding** - New developers can understand the schema faster
✅ **Faster Fresh Installs** - Fewer migration files to process
✅ **Better Maintainability** - All table columns defined in one place
✅ **Safe Backup** - All old migrations preserved in `migrations_backup/`
