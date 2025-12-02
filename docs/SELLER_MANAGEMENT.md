# Seller Management System

## Overview

This system tracks seller account creation, approval, rejection, and suspension. It uses a polymorphic relationship to identify which user (Admin, Owner, Staff, or Seller) performed each action.

## Database Schema

### Sellers Table - New Columns
- `approved_by_type` (string, nullable): The model type of the user who approved the seller (e.g., 'App\Models\Admin')
- `approved_by_id` (bigint, nullable): The ID of the user who approved the seller

### Seller Management Table
- `id`: Primary key
- `seller_id`: Foreign key to sellers table
- `created_by_type`: Polymorphic type (Admin, Owner, Staff, Seller)
- `created_by_id`: Polymorphic ID
- `action`: Enum ('created', 'approved', 'rejected', 'suspended')
- `notes`: Optional text notes
- `ip_address`: IP address of the user who performed the action
- `created_at`, `updated_at`: Timestamps

## Usage Examples

### 1. Creating a Seller Account (Manual Creation by Admin)

```php
use App\Traits\ManagesSellerActions;
use App\Models\Seller;

class SellerController extends Controller
{
    use ManagesSellerActions;

    public function store(Request $request)
    {
        // Validate and create the seller
        $seller = Seller::create([
            'business_name' => $request->business_name,
            'owner_name' => $request->owner_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'status' => 'pending',
            // ... other fields
        ]);

        // Log who created this seller account
        $this->logSellerCreation(
            $seller->id,
            auth()->user(), // Could be Admin, Owner, Staff, or Seller
            'Manually created by ' . auth()->user()->name
        );

        return redirect()->back()->with('success', 'Seller created successfully');
    }
}
```

### 2. Approving a Seller

```php
use App\Traits\ManagesSellerActions;
use App\Models\Seller;

class SellerApprovalController extends Controller
{
    use ManagesSellerActions;

    public function approve(Seller $seller)
    {
        // This will update the seller and log the approval
        $this->logSellerApproval(
            $seller,
            auth()->user(), // The admin/owner/staff approving
            'Approved after KYC verification'
        );

        return redirect()->back()->with('success', 'Seller approved successfully');
    }
}
```

### 3. Rejecting a Seller

```php
use App\Traits\ManagesSellerActions;
use App\Models\Seller;

class SellerApprovalController extends Controller
{
    use ManagesSellerActions;

    public function reject(Request $request, Seller $seller)
    {
        $this->logSellerRejection(
            $seller,
            auth()->user(),
            $request->rejection_reason
        );

        return redirect()->back()->with('success', 'Seller rejected');
    }
}
```

### 4. Suspending a Seller

```php
use App\Traits\ManagesSellerActions;
use App\Models\Seller;

class SellerController extends Controller
{
    use ManagesSellerActions;

    public function suspend(Request $request, Seller $seller)
    {
        $this->logSellerSuspension(
            $seller,
            auth()->user(),
            $request->suspension_reason
        );

        return redirect()->back()->with('success', 'Seller suspended');
    }
}
```

### 5. Viewing Seller Management History

```php
use App\Models\Seller;

// Get all management actions for a specific seller
$seller = Seller::with(['managementRecords.createdBy'])->find($id);

foreach ($seller->managementRecords as $record) {
    echo "Action: {$record->action}\n";
    echo "By: {$record->createdBy->name}\n";
    echo "At: {$record->created_at}\n";
    echo "Notes: {$record->notes}\n";
    echo "IP: {$record->ip_address}\n\n";
}

// Get who approved a seller
$seller = Seller::with('approvedBy')->find($id);
if ($seller->approvedBy) {
    echo "Approved by: {$seller->approvedBy->name}";
}
```

### 6. Querying Management Records

```php
use App\Models\SellerManagement;

// Get all sellers created by a specific admin
$adminCreatedSellers = SellerManagement::where('created_by_type', 'App\Models\Admin')
    ->where('created_by_id', $adminId)
    ->where('action', 'created')
    ->with('seller')
    ->get();

// Get all approval actions
$approvals = SellerManagement::byAction('created')->get();

// Get all actions for a specific seller
$sellerActions = SellerManagement::bySeller($sellerId)->get();
```

## Model Relationships

### Seller Model

```php
// Get who approved this seller
$seller->approvedBy; // Returns Admin, Owner, Staff, or Seller model

// Get all management records
$seller->managementRecords; // Returns collection of SellerManagement records
```

### SellerManagement Model

```php
// Get the seller
$record->seller; // Returns Seller model

// Get who performed the action
$record->createdBy; // Returns Admin, Owner, Staff, or Seller model
```

## Notes

- All actions are automatically logged with IP addresses
- The system uses polymorphic relationships, so any user type can create or approve sellers
- The `ManagesSellerActions` trait provides convenient methods for common operations
- Always use the trait methods to ensure proper logging and status updates
