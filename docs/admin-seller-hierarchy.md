# Admin Seller Hierarchy Feature

## Overview
Implemented a hierarchical seller visibility feature where admins can now see not only the sellers they created directly, but also sellers created by their staff members.

## Changes Made

### 1. Admin Model (`app/Models/Admin.php`)
- **Added**: `staffs()` relationship method
- **Purpose**: Enables querying all staff members belonging to an admin
- **Code**:
  ```php
  public function staffs()
  {
      return $this->hasMany(Staff::class, 'admin_id');
  }
  ```

### 2. Admin Dashboard Controller (`app/Http/Controllers/Admin/DashboardController.php`)
- **Updated**: Seller count query in `index()` method
- **Changes**: 
  - Now retrieves IDs of all staff members belonging to the admin
  - Counts sellers created by both the admin AND their staff members
- **Logic**:
  - Gets staff IDs: `Staff::where('admin_id', $admin_data->id)->pluck('id')`
  - Queries sellers where:
    - Created by the admin directly, OR
    - Created by any of the admin's staff members

### 3. Admin Seller Controller (`app/Http/Controllers/Admin/SellerController.php`)
- **Updated**: Seller listing query in `ajaxData()` method
- **Changes**:
  - Retrieves IDs of all staff members belonging to the admin
  - Filters sellers to show those created by admin OR their staff members
- **Benefits**:
  - Admins can now view and manage all sellers in their hierarchy
  - The "Created By" column will show the actual creator (admin or staff member)

## How It Works

### Database Structure
```
Admin (id: 1)
  ├── Staff (id: 1, admin_id: 1)
  │     └── Seller (created via seller_management)
  ├── Staff (id: 2, admin_id: 1)
  │     └── Seller (created via seller_management)
  └── Seller (created directly by admin)
```

### Query Logic
The implementation uses the `seller_management` table to track who created each seller:
- `created_by_type`: The model class (Admin or Staff)
- `created_by_id`: The ID of the creator
- `action`: Set to 'created' for creation records

The query filters sellers where:
```sql
WHERE (
  (created_by_type = 'App\Models\Admin' AND created_by_id = {admin_id})
  OR
  (created_by_type = 'App\Models\Staff' AND created_by_id IN ({staff_ids}))
) AND action = 'created'
```

## Testing Recommendations

1. **As Admin**:
   - Login to admin panel
   - Check dashboard - seller count should include staff-created sellers
   - Navigate to sellers list - should see sellers created by you and your staff
   - Verify "Created By" column shows correct creator names

2. **As Staff**:
   - Login to staff panel
   - Verify you only see sellers you created (no change in behavior)

3. **Create Test Data**:
   - Create an admin
   - Create 2-3 staff members under that admin
   - Have the admin create 2 sellers
   - Have each staff member create 1-2 sellers
   - Login as admin and verify all sellers are visible

## Benefits

1. **Better Oversight**: Admins can monitor all sellers in their team
2. **Simplified Management**: No need to switch between different views
3. **Maintains Attribution**: Still shows who actually created each seller
4. **Scalable**: Works regardless of the number of staff members
