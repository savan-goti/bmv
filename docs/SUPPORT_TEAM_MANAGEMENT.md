# Support Team Management System

## Overview

This comprehensive Support Team Management System allows owners and admins to manage support team members with full CRUD operations, role-based access control, department/queue assignments, and detailed audit logging.

## Features

### Core Functionality
- ✅ **Create/Read/Update/Delete** support team members
- ✅ **Role Assignment**: Admin, Staff, Seller, Customer (using enum)
- ✅ **Department Management**: Assign one or more departments
- ✅ **Queue Management**: Assign default ticket queues
- ✅ **Status Control**: Toggle active/disabled status
- ✅ **Notification Preferences**: Email, In-App, or Both
- ✅ **Quick Statistics**: Tickets assigned, open tickets, avg response time
- ✅ **Server-side Validation**: Comprehensive validation rules
- ✅ **Authorization**: Policy-based access control
- ✅ **Audit Logging**: Complete activity tracking
- ✅ **Unit Tests**: Full test coverage

### User Roles
The system supports four distinct roles via the `SupportRole` enum:
- **Admin**: Full administrative access
- **Staff**: Support staff members
- **Seller**: Seller support representatives
- **Customer**: Customer service representatives

## Database Structure

### Tables Created

#### 1. `support_team_members`
Main table for support team members with the following fields:
- `id`: Primary key
- `created_by_id`, `created_by_type`: Polymorphic creator tracking
- `profile_image`: Profile photo
- `name`, `email`, `phone`: Contact information
- `password`: Hashed password
- `role`: Enum (admin, staff, seller, customer)
- `departments`: JSON array of department IDs
- `default_queues`: JSON array of queue IDs
- `status`: Enum (active, disabled)
- `notification_method`: Enum (email, in_app, both)
- `tickets_assigned`, `open_tickets`, `avg_response_time`: Statistics
- `last_login_at`, `last_login_ip`: Login tracking
- `email_verified_at`: Email verification
- Timestamps and soft deletes

#### 2. `support_departments`
Departments for organizing support teams:
- `id`, `name`, `description`, `status`
- Timestamps and soft deletes

#### 3. `support_queues`
Ticket queues for routing:
- `id`, `name`, `description`, `department_id`, `status`
- Timestamps and soft deletes

#### 4. `support_audit_logs`
Complete audit trail:
- `id`, `support_team_member_id`
- `performed_by_id`, `performed_by_type`: Polymorphic actor
- `action`, `description`
- `old_values`, `new_values`: JSON change tracking
- `ip_address`, `user_agent`
- Timestamps

## File Structure

```
app/
├── Enums/
│   └── SupportRole.php                    # Role enum definition
├── Models/
│   ├── SupportTeamMember.php              # Main model
│   ├── SupportDepartment.php              # Department model
│   ├── SupportQueue.php                   # Queue model
│   └── SupportAuditLog.php                # Audit log model
├── Policies/
│   └── SupportTeamMemberPolicy.php        # Authorization policy
└── Http/Controllers/
    ├── Admin/
    │   └── SupportTeamController.php      # Admin controller
    └── Owner/
        └── SupportTeamController.php      # Owner controller

database/
├── migrations/
│   ├── 2025_12_07_193000_create_support_team_members_table.php
│   ├── 2025_12_07_193100_create_support_departments_table.php
│   ├── 2025_12_07_193200_create_support_queues_table.php
│   └── 2025_12_07_193300_create_support_audit_logs_table.php
├── factories/
│   ├── SupportTeamMemberFactory.php
│   ├── SupportDepartmentFactory.php
│   └── SupportQueueFactory.php
└── seeders/
    └── SupportTeamSeeder.php

resources/views/
├── admin/support-team/
│   ├── index.blade.php                    # List view
│   ├── create.blade.php                   # Create form
│   ├── edit.blade.php                     # Edit form
│   └── show.blade.php                     # Detail view
└── owner/support-team/
    ├── index.blade.php
    ├── create.blade.php
    ├── edit.blade.php
    └── show.blade.php

routes/
├── admin.php                              # Admin routes
└── owner.php                              # Owner routes

tests/Feature/
└── SupportTeamTest.php                    # Comprehensive tests
```

## Routes

### Admin Panel Routes
```php
// List all support team members
GET /admin/support-team

// Show create form
GET /admin/support-team/create

// Store new member
POST /admin/support-team

// Show member details
GET /admin/support-team/{id}

// Show edit form
GET /admin/support-team/{id}/edit

// Update member
PUT /admin/support-team/{id}

// Delete member
DELETE /admin/support-team/{id}

// Ajax data for DataTables
GET /admin/support-team/ajax-data

// Toggle status
POST /admin/support-team/{id}/status
```

### Owner Panel Routes
Same structure as admin, but prefixed with `/owner/support-team`

## Usage Examples

### Creating a Support Team Member

```php
use App\Models\SupportTeamMember;
use App\Enums\SupportRole;
use Illuminate\Support\Facades\Hash;

$member = SupportTeamMember::create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'phone' => '+1234567890',
    'password' => Hash::make('password'),
    'role' => SupportRole::STAFF,
    'departments' => [1, 2],
    'default_queues' => [1, 3],
    'status' => 'active',
    'notification_method' => 'both',
    'created_by_id' => auth()->id(),
    'created_by_type' => get_class(auth()->user()),
]);
```

### Querying Support Team Members

```php
// Get all active staff members
$staffMembers = SupportTeamMember::active()
    ->byRole('staff')
    ->get();

// Get members with departments
$member = SupportTeamMember::find(1);
$departments = $member->departmentRecords();
$queues = $member->queueRecords();

// Get audit logs
$logs = $member->auditLogs()->latest()->get();
```

### Creating Audit Logs

```php
use App\Models\SupportAuditLog;

SupportAuditLog::log(
    supportTeamMemberId: $member->id,
    action: 'updated',
    description: 'Member information updated',
    oldValues: ['name' => 'Old Name'],
    newValues: ['name' => 'New Name'],
    performedBy: auth()->user()
);
```

## Validation Rules

### Create/Update Validation
- **name**: Required, string, max 255 characters
- **email**: Required, valid email, unique
- **phone**: Optional, string, unique
- **password**: Required on create, min 8 characters, confirmed
- **role**: Required, must be valid enum value (admin, staff, seller, customer)
- **departments**: Optional, array, each must exist in support_departments
- **default_queues**: Optional, array, each must exist in support_queues
- **status**: Required, must be 'active' or 'disabled'
- **notification_method**: Required, must be 'email', 'in_app', or 'both'
- **profile_image**: Optional, image file, max 2MB

## Testing

### Running Tests
```bash
# Run all support team tests
php artisan test --filter=SupportTeamTest

# Run specific test
php artisan test --filter=admin_can_create_support_team_member
```

### Test Coverage
- ✅ Admin can view index
- ✅ Owner can view index
- ✅ Create support team member
- ✅ Update support team member
- ✅ Delete support team member
- ✅ Validation failures
- ✅ Unique email constraint
- ✅ Password confirmation
- ✅ Status changes
- ✅ Role validation
- ✅ Department/queue existence validation

## Seeding Sample Data

```bash
# Run the support team seeder
php artisan db:seed --class=SupportTeamSeeder
```

This will create:
- 3 departments (Technical Support, Billing & Payments, General Inquiries)
- 4 queues (Level 1/2 Technical, Billing, General)
- 3 sample support team members with different roles

## API Responses

### Success Response
```json
{
    "status": true,
    "message": "Support team member created successfully.",
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        ...
    }
}
```

### Error Response
```json
{
    "status": false,
    "error": {
        "email": ["The email has already been taken."],
        "password": ["The password must be at least 8 characters."]
    }
}
```

## Security Features

1. **Password Hashing**: All passwords are hashed using bcrypt
2. **Authorization**: Policy-based access control
3. **Validation**: Server-side validation for all inputs
4. **Audit Logging**: Complete activity tracking with IP and user agent
5. **Soft Deletes**: Members are soft-deleted, not permanently removed
6. **CSRF Protection**: All forms include CSRF tokens
7. **Mass Assignment Protection**: Fillable attributes defined in models

## Statistics Tracking

The system tracks three key metrics for each support team member:

1. **Tickets Assigned**: Total number of tickets assigned to the member
2. **Open Tickets**: Current number of open/unresolved tickets
3. **Avg Response Time**: Average response time in minutes

These can be updated programmatically or via scheduled jobs.

## Future Enhancements

Potential features for future development:
- Real-time ticket assignment
- Performance analytics dashboard
- Email notifications for assignments
- Integration with ticketing system
- Team performance reports
- Shift scheduling
- Workload balancing
- SLA tracking

## Support

For issues or questions, please contact the development team or refer to the main project documentation.

---

**Version**: 1.0.0  
**Last Updated**: December 7, 2025  
**Author**: BMV Development Team
