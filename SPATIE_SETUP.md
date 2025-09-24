# Spatie Laravel Packages Setup

This document outlines the comprehensive setup of Spatie packages in the SylvaNet application.

## 📦 Installed Packages

### 1. **Spatie Laravel Permission** ✅
- **Package**: `spatie/laravel-permission`
- **Version**: `^6.21`
- **Status**: ✅ Installed and Configured

## 🎯 Features Implemented

### **Role-Based Access Control (RBAC)**

#### **Roles Created:**
- **Admin**: Full access to all features
- **Manager**: Management-level access with user management
- **Operator**: Operational access for daily tasks
- **User**: Basic user access for standard operations
- **Viewer**: Read-only access for viewing data

#### **Permissions System:**
- **67 Total Permissions** covering all application features
- **Granular Control** for each module (articles, exploitants, forests, etc.)
- **Action-Based Permissions** (view, create, edit, delete, export, import)

### **Activity Logging**
- **Custom LogsActivity Trait** for model activity tracking
- **Automatic Logging** of create, update, and delete operations
- **User Context** with IP address and user agent tracking

### **Backup System**
- **Comprehensive Backup Configuration** for files and databases
- **Automated Cleanup** with retention policies
- **Email Notifications** for backup status

## 🛠️ Components Created

### **1. Enhanced Role & Permission Seeder**
```php
// Location: database/seeders/RolePermissionSeeder.php
- 67 comprehensive permissions
- 5 role levels with appropriate access
- Automatic permission assignment
```

### **2. Permission Middleware**
```php
// Location: app/Http/Middleware/CheckPermission.php
- Route-level permission checking
- Automatic redirect for unauthorized access
- Custom error messages
```

### **3. Activity Logging Trait**
```php
// Location: app/Traits/LogsActivity.php
- Automatic model activity tracking
- User context preservation
- IP and user agent logging
```

### **4. Spatie Service**
```php
// Location: app/Services/SpatieService.php
- Centralized role/permission management
- User role assignment utilities
- Statistics and reporting methods
```

### **5. Management Command**
```php
// Location: app/Console/Commands/ManageRolesPermissions.php
- CLI interface for role/permission management
- User role assignment
- Statistics and reporting
```

### **6. Backup Configuration**
```php
// Location: config/backup.php
- File and database backup settings
- Retention policies
- Notification configuration
```

## 🚀 Usage Examples

### **Checking Permissions in Controllers**
```php
// Check if user has permission
if (auth()->user()->can('articles.create')) {
    // Allow article creation
}

// Using middleware
Route::middleware(['auth', 'permission:articles.create'])->group(function () {
    Route::post('/articles', [ArticleController::class, 'store']);
});
```

### **Checking Roles**
```php
// Check if user has role
if (auth()->user()->hasRole('admin')) {
    // Admin-only functionality
}

// Check multiple roles
if (auth()->user()->hasAnyRole(['admin', 'manager'])) {
    // Admin or manager functionality
}
```

### **Using the Management Command**
```bash
# List all roles
php artisan spatie:manage list-roles

# List all permissions
php artisan spatie:manage list-permissions

# Create a new role
php artisan spatie:manage create-role --role="supervisor" --permissions="articles.view,articles.create"

# Assign role to user
php artisan spatie:manage assign-role --user=1 --role="manager"

# Show user permissions
php artisan spatie:manage user-permissions --user=1
```

### **Using the Spatie Service**
```php
use App\Services\SpatieService;

$spatieService = app(SpatieService::class);

// Create role with permissions
$spatieService->createRole('supervisor', ['articles.view', 'articles.create']);

// Assign role to user
$spatieService->assignRoleToUser($user, 'manager');

// Check permissions
if ($spatieService->userHasPermission($user, 'articles.create')) {
    // User can create articles
}
```

### **Activity Logging in Models**
```php
use App\Traits\LogsActivity;

class Article extends Model
{
    use LogsActivity;
    
    // Automatic logging of create, update, delete operations
}
```

## 📊 Permission Structure

### **Dashboard**
- `dashboard.view` - View dashboard

### **Articles**
- `articles.view` - View articles
- `articles.create` - Create articles
- `articles.edit` - Edit articles
- `articles.delete` - Delete articles
- `articles.export` - Export articles
- `articles.import` - Import articles
- `articles.print` - Print articles

### **Exploitants**
- `exploitants.view` - View exploitants
- `exploitants.create` - Create exploitants
- `exploitants.edit` - Edit exploitants
- `exploitants.delete` - Delete exploitants
- `exploitants.export` - Export exploitants
- `exploitants.import` - Import exploitants
- `exploitants.print-card` - Print professional cards

### **Settings Modules**
- **Essences**: view, create, edit, delete, export, import
- **Forets**: view, create, edit, delete, export, import
- **Nature de Coupes**: view, create, edit, delete, export, import
- **Situation Administratives**: view, create, edit, delete, export, import
- **Localisations**: view, create, edit, delete, export, import

### **User Management**
- `users.view` - View users
- `users.create` - Create users
- `users.edit` - Edit users
- `users.delete` - Delete users
- `users.assign-roles` - Assign roles to users

### **System Administration**
- `roles.view` - View roles
- `roles.create` - Create roles
- `roles.edit` - Edit roles
- `roles.delete` - Delete roles
- `permissions.view` - View permissions
- `permissions.assign` - Assign permissions

### **Notifications**
- `notifications.view` - View notifications
- `notifications.create` - Create notifications
- `notifications.send` - Send notifications
- `notifications.manage` - Manage notifications

### **System**
- `system.backup` - Manage backups
- `system.maintenance` - System maintenance
- `system.settings` - System settings

## 🔧 Configuration Files

### **Permission Configuration**
- **File**: `config/permission.php`
- **Models**: Role and Permission models configured
- **Tables**: Custom table names if needed
- **Guards**: Web guard configuration

### **Backup Configuration**
- **File**: `config/backup.php`
- **Sources**: Files and databases to backup
- **Destinations**: Storage disks for backups
- **Notifications**: Email notifications for backup status
- **Cleanup**: Retention policies

## 🎯 Role Permissions Matrix

| Permission | Admin | Manager | Operator | User | Viewer |
|------------|-------|---------|----------|------|--------|
| Dashboard View | ✅ | ✅ | ✅ | ✅ | ✅ |
| Articles (Full) | ✅ | ✅ | ✅ | ✅ | ❌ |
| Exploitants (Full) | ✅ | ✅ | ✅ | ✅ | ❌ |
| User Management | ✅ | ✅ | ❌ | ❌ | ❌ |
| Role Management | ✅ | ❌ | ❌ | ❌ | ❌ |
| System Settings | ✅ | ❌ | ❌ | ❌ | ❌ |
| Export/Import | ✅ | ✅ | ✅ | ❌ | ❌ |

## 🚀 Next Steps

### **1. Apply Permissions to Routes**
```php
// Add permission middleware to routes
Route::middleware(['auth', 'permission:articles.create'])->group(function () {
    // Protected routes
});
```

### **2. Add Permission Checks to Views**
```blade
@can('articles.create')
    <a href="{{ route('articles.create') }}" class="btn btn-primary">Create Article</a>
@endcan
```

### **3. Implement Activity Logging**
```php
// Add trait to models that need activity tracking
use App\Traits\LogsActivity;

class YourModel extends Model
{
    use LogsActivity;
}
```

### **4. Set Up Backup Scheduling**
```php
// Add to app/Console/Kernel.php
$schedule->command('backup:run')->daily();
$schedule->command('backup:clean')->daily();
```

## 📝 Commands Available

```bash
# Role and Permission Management
php artisan spatie:manage list-roles
php artisan spatie:manage list-permissions
php artisan spatie:manage create-role --role="role_name"
php artisan spatie:manage assign-role --user=1 --role="admin"
php artisan spatie:manage user-permissions --user=1
php artisan spatie:manage statistics

# Database Seeding
php artisan db:seed --class=RolePermissionSeeder

# Backup Commands (when backup package is installed)
php artisan backup:run
php artisan backup:clean
php artisan backup:list
```

## 🔒 Security Features

- **Granular Permissions**: Fine-grained control over user actions
- **Role Hierarchy**: Structured role system with appropriate access levels
- **Activity Tracking**: Complete audit trail of user actions
- **Middleware Protection**: Route-level permission enforcement
- **Automatic Logging**: User context preservation for security

## 📈 Benefits

1. **Enhanced Security**: Role-based access control with granular permissions
2. **Audit Trail**: Complete activity logging for compliance
3. **Scalability**: Easy to add new roles and permissions
4. **Maintainability**: Centralized permission management
5. **User Experience**: Appropriate access levels for different user types
6. **Compliance**: Activity logging for regulatory requirements

The Spatie setup provides a robust foundation for user management, security, and audit capabilities in the SylvaNet application.
