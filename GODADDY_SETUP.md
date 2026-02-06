# PHP Backend Setup Guide for GoDaddy

Quick reference for deploying the PHP backend to GoDaddy shared hosting.

## Files to Upload

Upload these files to `public_html/` or your website root:

```
api/
├── index.php              ← Main router
├── .htaccess              ← URL rewriting
├── db.php                 ← Database connection
├── health.php
├── contacts.php
├── get_contacts.php
├── get_contact.php
├── update_status.php
├── company_info.php
├── social_media.php
├── locations.php
└── locations_main.php
```

Also upload:
- `.htaccess` (root level for clean URLs)
- `database.sql` (for reference)

## Step 1: Create Database

1. Login to GoDaddy cPanel
2. Click "phpMyAdmin"
3. Click "Create database"
4. Name it: `bd_enterprises`
5. Click "Create"
6. Select the database
7. Click "Import"
8. Choose `database.sql`
9. Click "Go"

## Step 2: Configure Connection

Edit `api/db.php` and set your database details:

```php
$db_host = 'localhost';  // GoDaddy MySQL host
$db_name = 'bd_enterprises';
$db_user = 'your_cpanel_username_bd_enterprises';  // CPanal user_database
$db_password = 'your_password';  // From GoDaddy
```

**Find your credentials:**
1. cPanel → MySQL Databases
2. Look for database users
3. Use the username and password listed there

## Step 3: Test

Visit: `https://yourdomain.com/api/health`

Expected response:
```json
{
  "success": true,
  "message": "Server is running",
  "data": []
}
```

## Step 4: Update Frontend

In React frontend (`src/components/Contact.js`), update API URL:

```javascript
// OLD
const API_URL = 'https://bdenterprises-backend-t4p.vercel.app';

// NEW (GoDaddy)
const API_URL = 'https://yourdomain.com/api';
```

Or better, use environment variable:

```javascript
const API_URL = process.env.REACT_APP_API_URL || 'https://yourdomain.com/api';
```

## Common Issues

**404 errors on /api endpoints:**
- Check .htaccess file is present
- Verify mod_rewrite is enabled (GoDaddy usually has it on)
- Try accessing .php file directly: `/api/health.php`

**Database connection failed:**
- Verify username/password in db.php
- Check database name is correct
- Ensure MySQL service is running in GoDaddy

**CORS errors:**
- All endpoints already support CORS
- Check browser console for exact error
- Verify frontend API_URL is correct

## Files Reference

| File | Purpose |
|------|---------|
| `db.php` | PDO MySQL connection |
| `health.php` | Health check endpoint |
| `contacts.php` | Submit & get contacts |
| `get_contacts.php` | Get all contacts |
| `get_contact.php` | Get single contact |
| `update_status.php` | Update contact status |
| `company_info.php` | Company info endpoint |
| `social_media.php` | Social media links |
| `locations.php` | All locations |
| `locations_main.php` | Main office location |
| `index.php` | Router entry point |
| `.htaccess` | URL rewriting rules |

## Database Credentials Location

**In GoDaddy cPanel:**
1. MySQL Databases section
2. Find your database user
3. Username format: `cpaneluser_databasename`
4. Password is what you set during creation

**Or find password in cPanel:**
1. Account → My Credentials
2. Scroll to Database Users
3. Find the user for `bd_enterprises`

## Next Steps

1. ✅ Upload API files to `/api/`
2. ✅ Import database.sql
3. ✅ Configure db.php with credentials
4. ✅ Test health endpoint
5. ✅ Update frontend API_URL
6. ✅ Deploy frontend to GoDaddy

## Support

- Check GoDaddy error logs: cPanel → Error Log
- Test API with Postman: Import the endpoints
- Verify MySQL is running: cPanel → MySQL Databases
- Check file permissions: Files should be 644, directories 755

