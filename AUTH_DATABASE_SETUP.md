# Authentication Database Setup Guide

## Overview
This guide explains how to set up a separate database connection for authentication in your Laravel application.

## Database Configuration

### 1. Environment Variables
Add these variables to your `.env` file:

```env
# Main Application Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=defatp_main
DB_USERNAME=root
DB_PASSWORD=your_main_db_password

# Authentication Database
AUTH_DB_CONNECTION=auth_mysql
AUTH_DB_HOST=127.0.0.1
AUTH_DB_PORT=3306
AUTH_DB_DATABASE=defatp_auth
AUTH_DB_USERNAME=root
AUTH_DB_PASSWORD=your_auth_db_password
```

### 2. Database Connections
The authentication database connection has been configured in `config/database.php`:

```php
'auth_mysql' => [
    'driver' => 'mysql',
    'url' => env('AUTH_DB_URL'),
    'host' => env('AUTH_DB_HOST', '127.0.0.1'),
    'port' => env('AUTH_DB_PORT', '3306'),
    'database' => env('AUTH_DB_DATABASE', 'auth_laravel'),
    'username' => env('AUTH_DB_USERNAME', 'root'),
    'password' => env('AUTH_DB_PASSWORD', ''),
    'unix_socket' => env('AUTH_DB_SOCKET', ''),
    'charset' => env('AUTH_DB_CHARSET', 'utf8mb4'),
    'collation' => env('AUTH_DB_COLLATION', 'utf8mb4_unicode_ci'),
    'prefix' => '',
    'prefix_indexes' => true,
    'strict' => true,
    'engine' => null,
    'options' => extension_loaded('pdo_mysql') ? array_filter([
        PDO::MYSQL_ATTR_SSL_CA => env('AUTH_MYSQL_ATTR_SSL_CA'),
    ]) : [],
],
```

### 3. User Model Configuration
The User model has been updated to use the authentication database:

```php
class User extends Authenticatable
{
    protected $connection = 'auth_mysql';
    // ... rest of the model
}
```

## Setup Steps

### 1. Create Databases
Create two separate MySQL databases:

```sql
-- Main application database
CREATE DATABASE defatp_main CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Authentication database
CREATE DATABASE defatp_auth CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 2. Run Migrations
Run migrations for both databases:

```bash
# Run main application migrations
php artisan migrate

# Run authentication database migration
php artisan migrate --database=auth_mysql
```

### 3. Seed Authentication Database
Create a seeder for the authentication database:

```bash
php artisan make:seeder AuthUserSeeder
```

## Benefits

1. **Security**: Authentication data is isolated from application data
2. **Performance**: Authentication queries don't affect main application performance
3. **Scalability**: Can scale authentication and application databases independently
4. **Maintenance**: Easier to backup and maintain authentication data separately

## Usage Examples

### Querying Users
```php
// This will automatically use the auth_mysql connection
$user = User::find(1);

// Or explicitly specify the connection
$user = User::on('auth_mysql')->find(1);
```

### Creating Users
```php
$user = User::create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'ppr' => '12345',
    'password' => Hash::make('password'),
]);
```

### Authentication
Authentication will automatically use the auth_mysql connection since the User model is configured with it.

## Troubleshooting

### Connection Issues
1. Verify database credentials in `.env`
2. Check if both databases exist
3. Ensure MySQL service is running
4. Test connections with `php artisan tinker`:

```php
// Test main database
DB::connection()->getPdo();

// Test auth database
DB::connection('auth_mysql')->getPdo();
```

### Migration Issues
If migrations fail, ensure:
1. Database connections are properly configured
2. User has proper permissions on both databases
3. Migration files are in the correct location

## Security Considerations

1. **Separate Credentials**: Use different usernames/passwords for each database
2. **Network Security**: Consider placing auth database on different server
3. **Backup Strategy**: Implement separate backup strategies for each database
4. **Access Control**: Limit access to authentication database to essential services only
