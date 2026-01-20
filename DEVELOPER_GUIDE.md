# BMV Project - Developer Quick Start Guide

## 🚀 Getting Started

This guide will help you quickly understand and start working with the BMV/INDSTARY e-commerce platform.

---

## 📋 Prerequisites

Before you begin, ensure you have:

- ✅ PHP 8.2 or higher
- ✅ Composer
- ✅ MySQL 5.7+ or MariaDB 10.3+
- ✅ Node.js & NPM
- ✅ WAMP/XAMPP/MAMP (for local development)
- ✅ Git

---

## 🛠️ Installation

### 1. Clone the Repository
```bash
cd c:\wamp64\www
git clone <repository-url> bmv
cd bmv
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### 3. Environment Setup
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Generate JWT secret
php artisan jwt:secret
```

### 4. Configure Database
Edit `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bmv_new
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Run Migrations
```bash
php artisan migrate
```

### 6. Configure Services

#### Twilio (for OTP)
```env
TWILIO_SID=your_twilio_sid
TWILIO_AUTH_TOKEN=your_twilio_token
TWILIO_PHONE_NUMBER=+1234567890
TWILIO_OTP_EXPIRATION=10
```

#### Google OAuth
```env
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
```

### 7. Build Assets
```bash
npm run build
# or for development
npm run dev
```

### 8. Start Development Server
```bash
php artisan serve
```

Visit: `http://localhost:8000`

---

## 📁 Project Structure Overview

```
bmv/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          # Admin panel controllers
│   │   ├── Api/            # API controllers (Customer)
│   │   ├── Owner/          # Owner panel controllers
│   │   ├── Seller/         # Seller panel controllers
│   │   └── Staff/          # Staff panel controllers
│   ├── Models/             # Eloquent models
│   ├── Services/           # Business logic services
│   ├── Enums/              # Enum classes
│   └── Http/Traits/        # Reusable traits
├── routes/
│   ├── api.php             # API routes (v1)
│   ├── admin.php           # Admin routes
│   ├── owner.php           # Owner routes
│   ├── seller.php          # Seller routes
│   └── staff.php           # Staff routes
├── database/
│   └── migrations/         # Database migrations
├── resources/
│   └── views/              # Blade templates
└── public/
    └── uploads/            # File uploads
```

---

## 🔐 Authentication System

### Web Authentication (Multi-Guard)

The system uses separate guards for each user type:

```php
// Owner login
Route::post('/owner/login', [OwnerAuthController::class, 'authenticate']);

// Admin login  
Route::post('/admin/login', [AdminAuthController::class, 'authenticate']);

// Staff login
Route::post('/staff/login', [StaffAuthController::class, 'authenticate']);

// Seller login
Route::post('/seller/login', [SellerAuthController::class, 'authenticate']);
```

**Access URLs:**
- Owner: `http://localhost/owner/login`
- Admin: `http://localhost/admin/login`
- Staff: `http://localhost/staff/login`
- Seller: `http://localhost/seller/login`

### API Authentication (JWT)

Customer authentication uses JWT tokens:

```php
// Register
POST /api/v1/auth/register

// Login
POST /api/v1/auth/login

// Get Profile (with token)
GET /api/v1/auth/profile
Headers: Authorization: Bearer {token}
```

---

## 🧪 Testing the API

### Using cURL

**1. Register a Customer:**
```bash
curl -X POST http://localhost/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "phone": "1234567890",
    "country_code": "+91",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

**2. Login:**
```bash
curl -X POST http://localhost/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "type": "email",
    "email": "test@example.com",
    "password": "password123"
  }'
```

**3. Get Profile:**
```bash
curl -X GET http://localhost/api/v1/auth/profile \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"
```

### Using Postman

1. Import the API collection from `api-documentation/`
2. Set base URL: `http://localhost/api/v1`
3. Use environment variables for tokens
4. Test all endpoints

---

## 🗄️ Database Quick Reference

### Key Tables

**Users:**
- `owners` - Business owners
- `admins` - Admin users
- `staffs` - Staff members
- `sellers` - Vendors
- `customers` - End users (API)

**Products:**
- `products` - Main product table (50+ fields)
- `product_images` - Product gallery
- `product_variants` - Product variations
- `product_information` - Extended details

**Categories:**
- `categories` - Top-level categories
- `sub_categories` - Second-level
- `child_categories` - Third-level

**Master Data:**
- `brands`, `collections`, `units`, `hsn_sacs`
- `colors`, `sizes`, `suppliers`, `keywords`

### Common Queries

**Get all active products:**
```php
Product::where('is_active', 'active')
    ->where('product_status', 'approved')
    ->with(['category', 'brand', 'productImages'])
    ->get();
```

**Get categories with sub-categories:**
```php
Category::where('status', 'active')
    ->with('subCategories')
    ->get();
```

**Get customer with profile:**
```php
Customer::where('email', $email)
    ->where('status', 'active')
    ->first();
```

---

## 🎨 Frontend Development

### Admin Panel

The admin panel uses:
- **Blade Templates** - Server-side rendering
- **DataTables** - AJAX-based tables
- **jQuery** - AJAX forms and interactions
- **Bootstrap** - UI framework

**Example DataTable:**
```javascript
$('#products-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{ route('owner.products.ajaxData') }}",
    columns: [
        { data: 'id', name: 'id' },
        { data: 'product_name', name: 'product_name' },
        { data: 'sku', name: 'sku' },
        { data: 'status', name: 'status' }
    ]
});
```

### API Development

**Standard Response Format:**
```php
// Success
return response()->json([
    'success' => true,
    'message' => 'Operation successful',
    'data' => $data
], 200);

// Error
return response()->json([
    'success' => false,
    'message' => 'Error message',
    'errors' => $errors
], 400);
```

**Using ResponseTrait:**
```php
use App\Http\Traits\ResponseTrait;

class MyController extends Controller
{
    use ResponseTrait;
    
    public function index()
    {
        $data = Model::all();
        return $this->sendResponse('Data retrieved', $data);
    }
    
    public function error()
    {
        return $this->sendError('Error occurred', 400);
    }
}
```

---

## 🔧 Common Tasks

### Creating a New Model

```bash
# Create model with migration
php artisan make:model MyModel -m

# Create model with migration, controller, and factory
php artisan make:model MyModel -mcf
```

### Creating a New Controller

```bash
# API Controller
php artisan make:controller Api/MyController

# Resource Controller
php artisan make:controller Owner/MyController --resource
```

### Creating a New Migration

```bash
# Create migration
php artisan make:migration create_my_table

# Run migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Refresh all migrations
php artisan migrate:fresh
```

### Creating an Enum

```php
<?php

namespace App\Enums;

enum MyEnum: string
{
    case Option1 = 'option1';
    case Option2 = 'option2';
    
    public function label(): string
    {
        return match($this) {
            self::Option1 => 'Option 1',
            self::Option2 => 'Option 2',
        };
    }
}
```

---

## 🐛 Debugging

### Enable Debug Mode

```env
APP_DEBUG=true
APP_ENV=local
```

### View Logs

```bash
# Tail logs
tail -f storage/logs/laravel.log

# Or use Laravel Pail (if installed)
php artisan pail
```

### Clear Cache

```bash
# Clear all cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Or clear everything
php artisan optimize:clear
```

### Database Debugging

```php
// Enable query logging
DB::enableQueryLog();

// Your queries here
$users = User::all();

// Get executed queries
dd(DB::getQueryLog());
```

---

## 📝 Code Standards

### Naming Conventions

**Models:** PascalCase, singular
```php
Product, Customer, ProductImage
```

**Controllers:** PascalCase, plural + Controller
```php
ProductsController, CustomersController
```

**Routes:** kebab-case
```php
Route::get('/product-categories', ...);
```

**Database Tables:** snake_case, plural
```php
products, product_images, customers
```

**Variables:** camelCase
```php
$productName, $userId, $isActive
```

### Code Style

Follow PSR-12 coding standards:

```php
<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::where('is_active', 'active')
            ->paginate(15);
            
        return $this->sendResponse(
            'Products retrieved successfully',
            $products
        );
    }
}
```

---

## 🔒 Security Best Practices

### 1. Never Commit Sensitive Data
```bash
# Add to .gitignore
.env
.env.backup
.env.production
```

### 2. Use Form Request Validation
```php
php artisan make:request StoreProductRequest
```

### 3. Protect Routes
```php
Route::middleware(['auth:api'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index']);
});
```

### 4. Sanitize User Input
```php
$validated = $request->validate([
    'email' => 'required|email|max:255',
    'name' => 'required|string|max:255',
]);
```

---

## 📚 Useful Commands

### Artisan Commands

```bash
# List all routes
php artisan route:list

# List all routes for a specific guard
php artisan route:list --path=api

# Create a new seeder
php artisan make:seeder ProductSeeder

# Run seeders
php artisan db:seed

# Create a new middleware
php artisan make:middleware CheckRole

# Create a new service provider
php artisan make:provider MyServiceProvider

# Clear and cache routes
php artisan route:cache

# Clear and cache config
php artisan config:cache
```

### Composer Commands

```bash
# Update dependencies
composer update

# Install specific package
composer require package/name

# Remove package
composer remove package/name

# Dump autoload
composer dump-autoload
```

### NPM Commands

```bash
# Install dependencies
npm install

# Build for production
npm run build

# Build for development
npm run dev

# Watch for changes
npm run watch
```

---

## 🆘 Troubleshooting

### Common Issues

**1. "Class not found" error**
```bash
composer dump-autoload
```

**2. "Route not found" error**
```bash
php artisan route:clear
php artisan route:cache
```

**3. "Permission denied" on storage**
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

**4. "SQLSTATE connection refused"**
- Check MySQL is running
- Verify database credentials in `.env`
- Check DB_HOST and DB_PORT

**5. "JWT secret not set"**
```bash
php artisan jwt:secret
```

---

## 📖 Documentation Links

### Project Documentation
- [Project Review](.agent/PROJECT_REVIEW.md)
- [Functionality Checklist](.agent/FUNCTIONALITY_CHECKLIST.md)
- [API Reference](api-documentation/API_REFERENCE.md)
- [Project Summary](PROJECT_SUMMARY.md)

### Laravel Documentation
- [Laravel 12 Docs](https://laravel.com/docs/12.x)
- [Eloquent ORM](https://laravel.com/docs/12.x/eloquent)
- [Routing](https://laravel.com/docs/12.x/routing)
- [Validation](https://laravel.com/docs/12.x/validation)

### Package Documentation
- [JWT Auth](https://jwt-auth.readthedocs.io/)
- [Intervention Image](https://image.intervention.io/)
- [DataTables](https://yajrabox.com/docs/laravel-datatables)
- [Twilio PHP](https://www.twilio.com/docs/libraries/php)

---

## 🎯 Next Steps

### For New Developers

1. ✅ Set up local environment
2. ✅ Review project structure
3. ✅ Understand authentication system
4. ✅ Test API endpoints
5. ✅ Review database schema
6. ✅ Read existing code
7. ✅ Start with small tasks

### For Contributing

1. Create a new branch
2. Make your changes
3. Write tests (when testing is set up)
4. Submit pull request
5. Wait for code review

---

## 💬 Getting Help

### Resources
- Check documentation first
- Review existing code for examples
- Search Laravel documentation
- Check package documentation

### Contact
- Project Lead: [Contact Info]
- Development Team: [Contact Info]
- Issue Tracker: [GitHub/GitLab URL]

---

**Last Updated:** January 20, 2026  
**Version:** 1.0.0  
**Maintained By:** Development Team

---

## Quick Reference Card

```
🚀 Start Server:     php artisan serve
🗄️ Run Migrations:   php artisan migrate
🧹 Clear Cache:      php artisan optimize:clear
📋 List Routes:      php artisan route:list
🔍 Tail Logs:        tail -f storage/logs/laravel.log
🎨 Build Assets:     npm run build
🧪 Run Tests:        php artisan test (when available)

📍 Owner Panel:      http://localhost/owner/login
📍 Admin Panel:      http://localhost/admin/login
📍 API Base:         http://localhost/api/v1
📍 API Health:       http://localhost/api/health
```
