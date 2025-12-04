# Mammoth Self Store - Admin Panel Installation Guide

## Overview
This admin panel allows you to manage specials and unit descriptions for the Mammoth Self Store booking system. It integrates seamlessly with your existing CodeIgniter 3 project without modifying any existing code.

## Features
- **Manage Specials**: Create, edit, activate/deactivate special offers
- **Bulk Apply**: Apply specials to multiple locations, sizes, and types
- **Unit Descriptions**: Manage "What it Fits" filters and descriptions for unit sizes
- **Modern UI**: Clean, responsive interface with Bootstrap 5

---

## Installation Steps

### Step 1: Run Database Migrations

1. Open **phpMyAdmin** in your browser: `http://localhost/phpmyadmin`
2. Select your database: `mammoths_checkout_db`
3. Go to the **SQL** tab
4. Open the file: `database_migrations/001_admin_panel_setup.sql`
5. Copy the entire SQL content and paste it into the SQL tab
6. Click **Go** to execute

This will:
- Add new columns to `tbl_unit_specials` (free_months, months_to_pay, is_active)
- Create mapping tables for specials (locations, sizes, types)
- Create unit types and fit filters tables
- Insert default data

### Step 2: Verify File Structure

All new files have been created in the following structure:

```
book.mammothstorage.com.au/
├── application/
│   ├── controllers/
│   │   └── admin/
│   │       ├── Specials.php          ✓ NEW
│   │       └── Units.php              ✓ NEW
│   ├── models/
│   │   └── admin/
│   │       ├── AdminSpecialsModel.php ✓ NEW
│   │       └── AdminUnitsModel.php    ✓ NEW
│   ├── views/
│   │   └── admin/
│   │       ├── layout/
│   │       │   ├── header.php         ✓ NEW
│   │       │   └── footer.php         ✓ NEW
│   │       ├── specials/
│   │       │   └── index.php          ✓ NEW
│   │       └── units/
│   │           └── index.php          ✓ NEW
│   └── config/
│       └── routes.php                 ✓ UPDATED (new routes added)
└── database_migrations/
    └── 001_admin_panel_setup.sql      ✓ NEW
```

### Step 3: Verify Routes

The routes have been automatically added to `application/config/routes.php`:

```php
// Admin Specials Routes
$route['admin/specials'] = 'admin/Specials/index';
$route['admin/specials/list'] = 'admin/Specials/list_json';
$route['admin/specials/get/(:num)'] = 'admin/Specials/get/$1';
$route['admin/specials/create'] = 'admin/Specials/create';
$route['admin/specials/update/(:num)'] = 'admin/Specials/update/$1';
$route['admin/specials/toggle'] = 'admin/Specials/toggle';
$route['admin/specials/meta'] = 'admin/Specials/meta';

// Admin Units Routes
$route['admin/units'] = 'admin/Units/index';
$route['admin/units/meta'] = 'admin/Units/meta';
$route['admin/units/bulk-update-descriptions'] = 'admin/Units/bulk_update_descriptions';
```

### Step 4: Test the Admin Panel

1. Make sure XAMPP Apache and MySQL are running
2. Open your browser and navigate to:
   - **Manage Specials**: `http://localhost/book.mammothstorage.com.au/admin/specials`
   - **Manage Units**: `http://localhost/book.mammothstorage.com.au/admin/units`

---

## Usage Guide

### Managing Specials

#### Creating a New Special

1. Go to `http://localhost/book.mammothstorage.com.au/admin/specials`
2. Click **"+ Create New Special"** button
3. Fill in the form:
   - **Special Name**: e.g., "Second Month Free - All Locations"
   - **Discount Type**: Choose from:
     - Free Months (Buy X, Get Y)
     - Percentage Off
     - Fixed Amount Off
   - If "Free Months" is selected:
     - Enter "Number of Months to Pay For (Buy X)"
     - Enter "Number of Free Months (Get Y)"
4. Select where to apply:
   - **Locations**: Check individual locations or "All Locations"
   - **Sizes**: Check individual sizes or "All Sizes"
   - **Types**: Check individual types or "All Types"
5. Click **"Save Special"**

#### Editing a Special

1. Find the special in the list
2. Click the **"Edit"** button
3. Modify the fields
4. Click **"Save Special"**

#### Activating/Deactivating a Special

- Use the toggle switch on the right side of each special card
- Blue = Active, Gray = Inactive

### Managing Unit Descriptions

1. Go to `http://localhost/book.mammothstorage.com.au/admin/units`
2. **Select Unit Sizes**: Check the sizes you want to update
3. **Apply "What it Fits" Filters**: Check relevant filters (Small, Medium, 1 Bedroom, etc.)
4. **Apply Basic Description**: Edit the description text
5. Click **"Apply Changes to Selected Units"**

---

## Integration with Existing Code

### How It Works

The admin panel **does NOT modify** your existing code. It only:

1. **Adds new columns** to `tbl_unit_specials` (compatible with existing structure)
2. **Creates new mapping tables** for better organization
3. **Adds new admin controllers/models** in separate directories
4. **Adds new routes** without touching existing ones

### Existing Code Still Works

Your existing `UnitModel::getUnitSpecials()` function continues to work because:
- The `tbl_unit_specials` table structure is preserved
- New columns have default values
- The existing query still returns all the original fields

### Data Flow

```
Admin Panel → Creates/Updates Specials → tbl_unit_specials
                                              ↓
                            UnitModel::getUnitSpecials() reads data
                                              ↓
                            Public Booking Site displays specials
```

---

## Troubleshooting

### Issue: "404 Page Not Found" when accessing admin URLs

**Solution**: 
1. Check that `.htaccess` file exists in your project root
2. Make sure Apache `mod_rewrite` is enabled in XAMPP
3. Verify routes are correctly added to `application/config/routes.php`

### Issue: Database errors about missing columns

**Solution**:
1. Re-run the SQL migration file
2. Check that you're using the correct database: `mammoths_checkout_db`

### Issue: Specials not showing on public site

**Solution**:
1. Make sure the special is set to **Active** (toggle switch is blue)
2. Verify that `UnitController::fetchUnits()` is calling `$this->UnitModel->getUnitSpecials()`
3. Check browser console for JavaScript errors

### Issue: Styles not loading properly

**Solution**:
1. Check your internet connection (Bootstrap 5 loads from CDN)
2. Clear browser cache
3. Check browser console for CSS loading errors

---

## Security Recommendations

### For Production Use

1. **Add Authentication**:
   - Create an admin login system
   - Protect all `/admin/*` routes with session checks
   - Example:
   ```php
   // In each admin controller's __construct()
   if (!$this->session->userdata('admin_logged_in')) {
       redirect('admin/login');
   }
   ```

2. **Add CSRF Protection**:
   - Enable CSRF in `application/config/config.php`:
   ```php
   $config['csrf_protection'] = TRUE;
   ```

3. **Restrict Access by IP** (optional):
   - In `.htaccess`, add:
   ```apache
   <Location /admin>
       Order Deny,Allow
       Deny from all
       Allow from 127.0.0.1
       Allow from YOUR_OFFICE_IP
   </Location>
   ```

---

## API Endpoints Reference

### Specials Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/admin/specials` | Load admin page |
| GET | `/admin/specials/list` | Get all specials (JSON) |
| GET | `/admin/specials/get/{id}` | Get single special (JSON) |
| POST | `/admin/specials/create` | Create new special |
| POST | `/admin/specials/update/{id}` | Update special |
| POST | `/admin/specials/toggle` | Toggle active status |
| GET | `/admin/specials/meta` | Get locations, sizes, types |

### Units Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/admin/units` | Load admin page |
| GET | `/admin/units/meta` | Get sizes, filters, descriptions |
| POST | `/admin/units/bulk-update-descriptions` | Bulk update descriptions |

---

## Support

If you encounter any issues:

1. Check the browser console for JavaScript errors
2. Check Apache error logs: `c:/xampp/apache/logs/error.log`
3. Check PHP errors in CodeIgniter: `application/logs/`
4. Verify database connection in `application/config/database.php`

---

## Credits

Built for Mammoth Self Store  
Framework: CodeIgniter 3  
UI: Bootstrap 5  
Date: November 2025
