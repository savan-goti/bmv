# 🚀 BMV Quick Reference Guide

**Date**: December 26, 2025  
**Version**: 1.0.0

---

## 📋 Table of Contents

1. [User Roles & Access](#user-roles--access)
2. [Authentication Methods](#authentication-methods)
3. [Product Management](#product-management)
4. [API Endpoints](#api-endpoints)
5. [Database Tables](#database-tables)
6. [Common Commands](#common-commands)
7. [File Locations](#file-locations)
8. [Helper Functions](#helper-functions)

---

## 👥 User Roles & Access

### Owner (Super Admin)
- **Access**: Full system control
- **Login**: `/owner/login`
- **Dashboard**: `/owner`
- **Guard**: `owner`
- **Features**: All features, manage all users, configure settings

### Admin
- **Access**: Administrative
- **Login**: `/admin/login`
- **Dashboard**: `/admin`
- **Guard**: `admin`
- **Features**: Manage sellers/staff, product approval, reports

### Staff
- **Access**: Branch-based
- **Login**: `/staff/login`
- **Dashboard**: `/staff`
- **Guard**: `staff`
- **Features**: Branch operations, limited product management

### Seller
- **Access**: Vendor portal
- **Login**: `/seller/login`
- **Dashboard**: `/seller`
- **Guard**: `seller`
- **Features**: Own products, KYC, analytics

### Customer
- **Access**: API only
- **Endpoint**: `/api/v1/auth/login`
- **Guard**: `api` (JWT)
- **Features**: Shopping, profile, reviews

---

## 🔐 Authentication Methods

### Session-Based (Owner, Admin, Staff, Seller)

**Login Flow**:
1. Email + Password
2. Email Verification (6-digit code)
3. 2FA (if enabled)
4. Dashboard access

**Features**:
- ✅ Email verification
- ✅ Two-Factor Authentication (Google Authenticator)
- ✅ Google OAuth login
- ✅ Remember Me
- ✅ Password reset
- ✅ Session management (7 days)

### JWT-Based (Customer)

**Login Flow**:
1. POST `/api/v1/auth/login`
2. Receive JWT token
3. Use token in Authorization header

**Token Info**:
- **Lifetime**: 60 minutes
- **Refresh**: 2 weeks
- **Algorithm**: HS256
- **Blacklist**: Enabled

---

## 🛒 Product Management

### Product Types
- **Simple**: Single SKU, no variations
- **Variable**: Multiple variants with attributes

### Product Fields (89 total)

**Basic** (7):
- product_name, slug, sku, barcode
- short_description, full_description, product_type

**Pricing** (8):
- purchase_price, original_price, sell_price
- discount_type, discount_value, gst_rate
- tax_included, commission_type, commission_value

**Inventory** (6):
- stock_type, total_stock, reserved_stock
- available_stock, low_stock_alert, warehouse_location

**Categories** (5):
- category_id, sub_category_id, child_category_id
- brand_id, collection_id

**Media** (4):
- thumbnail_image, video_url, image_alt_text
- has_variation

**Shipping** (6):
- weight, length, width, height
- shipping_class, free_shipping, cod_available

**Status** (5):
- product_status, is_active, is_featured
- is_returnable, return_days

**SEO** (5):
- meta_title, meta_description, meta_keywords
- search_tags, schema_markup

**Ownership** (7):
- owner_id, seller_id, branch_id
- added_by_role, added_by_user_id
- approved_by_admin_id, approved_at

### Product Status Workflow
```
Draft → Pending → Approved
                ↓
             Rejected
```

---

## 📱 API Endpoints

### Health Check
```
GET /api/health
```

### Authentication
```
POST   /api/v1/auth/register      # Register customer
POST   /api/v1/auth/login         # Login
POST   /api/v1/auth/logout        # Logout
POST   /api/v1/auth/refresh       # Refresh token
GET    /api/v1/auth/profile       # Get profile
```

### Headers
```
Content-Type: application/json
Accept: application/json
Authorization: Bearer {token}  # For protected routes
```

---

## 🗄️ Database Tables

### User Tables (13)
```
owners, admins, staffs, sellers, customers
admin_password_reset_tokens
owner_password_reset_tokens
staff_password_reset_tokens
seller_password_reset_tokens
sessions, users, seller_management, cache
```

### Product Tables (14)
```
categories, sub_categories, child_categories
products, product_information, product_images
product_variants, product_variant_attributes
product_variant_stock, product_analytics
product_reviews, product_views
brands, collections, collection_product
```

### Organization Tables (4)
```
branches, job_positions
branch_positions, seller_management
```

### Support Tables (4)
```
support_team_members, support_departments
support_queues, support_audit_logs
```

### System Tables (4)
```
settings, jobs, failed_jobs, cache
```

**Total**: 39 tables

---

## ⚡ Common Commands

### Setup
```bash
# Install dependencies
composer install
npm install

# Environment
cp .env.example .env
php artisan key:generate

# Database
php artisan migrate
php artisan db:seed

# Build assets
npm run build
```

### Development
```bash
# Start server
php artisan serve

# Watch assets
npm run dev

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Queue worker
php artisan queue:work
```

### Database
```bash
# Run migrations
php artisan migrate

# Rollback
php artisan migrate:rollback

# Fresh migration
php artisan migrate:fresh

# Seed database
php artisan db:seed

# Create migration
php artisan make:migration create_table_name
```

### Generate Code
```bash
# Controller
php artisan make:controller ControllerName

# Model
php artisan make:model ModelName

# Migration
php artisan make:migration migration_name

# Seeder
php artisan make:seeder SeederName

# Request
php artisan make:request RequestName
```

---

## 📁 File Locations

### Controllers
```
app/Http/Controllers/
├── Owner/          (20 controllers)
├── Admin/          (8 controllers)
├── Staff/          (7 controllers)
├── Seller/         (5 controllers)
├── Api/            (1 controller)
└── Auth/           (1 controller)
```

### Models
```
app/Models/         (34 models)
```

### Views
```
resources/views/
├── owner/          (66 files)
├── admin/          (26 files)
├── staff/          (18 files)
├── seller/         (14 files)
└── emails/         (12 files)
```

### Routes
```
routes/
├── web.php         (Public routes)
├── api.php         (API routes)
├── owner.php       (100+ routes)
├── admin.php       (50+ routes)
├── staff.php       (40+ routes)
├── seller.php      (30+ routes)
└── console.php     (Console routes)
```

### Migrations
```
database/migrations/    (39 files)
```

### Seeders
```
database/seeders/       (8 files)
```

### Documentation
```
docs/                   (32 files)
```

---

## 🔧 Helper Functions

### Image Functions

**Upload Single Image**:
```php
uploadImgFile($file, $path)
// Returns: file path
// Features: WebP conversion, optimization
```

**Upload Multiple Images**:
```php
uploadMultipleImages($files, $path)
// Returns: array of file paths
// Features: Bulk upload, WebP conversion
```

**Delete Image**:
```php
deleteImgFile($path)
// Returns: boolean
// Features: File deletion
```

### Utility Functions

**Currency Format**:
```php
currency($amount)
// Returns: €100.00
// Note: Symbol is hardcoded (€)
```

**Format Amount**:
```php
formatAmount($amount, $decimals = 2)
// Returns: 1,234.56
```

**Generate Slug**:
```php
slug($string)
// Returns: unique-slug-string
// Features: Unique slug generation
```

---

## 🔑 Configuration Files

### Authentication
```
config/auth.php         # Multi-guard configuration
```

### JWT
```
config/jwt.php          # JWT settings
```

### Constants
```
config/constants.php    # Path constants
```

### Database
```
config/database.php     # Database connections
```

### Mail
```
config/mail.php         # Email configuration
```

---

## 🎨 Frontend Assets

### CSS
```
Tailwind CSS 4.0
Bootstrap 5
Custom CSS in public/assets/css/
```

### JavaScript
```
jQuery
Vanilla JS
DataTables (Yajra)
SweetAlert
Toastr
```

### Build
```
Vite 7.0.7
npm run dev     # Development
npm run build   # Production
```

---

## 🔐 Security Features

### Implemented
- ✅ Multi-guard authentication (5 guards)
- ✅ Two-Factor Authentication (2FA)
- ✅ Email verification
- ✅ Google OAuth
- ✅ JWT tokens
- ✅ Session management
- ✅ Password hashing (Bcrypt)
- ✅ CSRF protection
- ✅ XSS prevention
- ✅ SQL injection protection

### Recommended
- ⚠️ Rate limiting
- ⚠️ API throttling
- ⚠️ Comprehensive logging

---

## 📊 Key Metrics

### Code
- **Files**: 242+
- **Lines**: 31,250+
- **Controllers**: 41
- **Models**: 34
- **Migrations**: 39
- **Views**: 100+
- **Routes**: 150+

### Features
- **User Roles**: 5
- **Auth Guards**: 5
- **Product Fields**: 89
- **Database Tables**: 39
- **Documentation Files**: 32

---

## 🚀 Quick Start

### 1. Clone & Setup
```bash
git clone <repository>
cd bmv
composer install
npm install
cp .env.example .env
php artisan key:generate
```

### 2. Configure Database
```bash
# Edit .env file
DB_DATABASE=bmv
DB_USERNAME=root
DB_PASSWORD=

# Run migrations
php artisan migrate
php artisan db:seed
```

### 3. Run Application
```bash
# Terminal 1: Server
php artisan serve

# Terminal 2: Assets
npm run dev
```

### 4. Access
```
Owner:    http://localhost:8000/owner/login
Admin:    http://localhost:8000/admin/login
Staff:    http://localhost:8000/staff/login
Seller:   http://localhost:8000/seller/login
API:      http://localhost:8000/api/v1/
```

---

## 📚 Documentation

### Main Documents
1. **COMPLETE_PROJECT_REVIEW_DEC_2025.md** - Full review
2. **PROJECT_FUNCTIONALITY_SUMMARY.md** - Quick summary
3. **SYSTEM_ARCHITECTURE_MAP.md** - Architecture diagrams
4. **API_DOCUMENTATION.md** - API guide
5. **ARCHITECTURE.md** - System architecture

### Feature Docs
- BRANDS_COLLECTIONS_IMPLEMENTATION.md
- CHILD_CATEGORY_IMPLEMENTATION.md
- PRODUCTS_TABLE_STRUCTURE.md
- SELLER_MANAGEMENT.md

### Google OAuth
- GOOGLE_AUTH_IMPLEMENTATION.md
- GOOGLE_AUTH_QUICK_REFERENCE.md
- GOOGLE_OAUTH_SETUP.md

### Support System
- SUPPORT_TEAM_MANAGEMENT.md
- SUPPORT_TEAM_QUICK_REF.md

---

## 🆘 Troubleshooting

### Common Issues

**Migration Error**:
```bash
php artisan migrate:fresh
php artisan db:seed
```

**Cache Issues**:
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

**Permission Issues**:
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

**Asset Build Issues**:
```bash
npm cache clean --force
rm -rf node_modules
npm install
npm run build
```

---

## 📞 Support

### Resources
- Documentation: `/docs` folder
- API Docs: `docs/API_DOCUMENTATION.md`
- Architecture: `docs/ARCHITECTURE.md`
- Postman Collection: `docs/BMV_Customer_API.postman_collection.json`

---

**Version**: 1.0.0  
**Last Updated**: December 26, 2025  
**Status**: ✅ Production Ready
