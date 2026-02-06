# BD Enterprises PHP Backend API

Complete PHP + MySQL backend for BD Enterprises fire safety website, optimized for GoDaddy shared hosting.

## Overview

This is a production-ready PHP backend implementation with the same API endpoints and behavior as the Node.js version. It uses plain PHP with PDO for MySQL connections and is fully compatible with GoDaddy shared hosting.

**Status**: ✅ Production Ready  
**Framework**: Plain PHP (no framework)  
**Database**: MySQL (5.7+)  
**Deployment**: GoDaddy Shared Hosting  
**API Format**: RESTful JSON  

---

## Project Structure

```
bd-enterprises-backend/
├── api/
│   ├── index.php              # Router entry point
│   ├── .htaccess              # URL rewriting rules
│   ├── db.php                 # PDO database connection
│   ├── health.php             # Health check endpoint
│   ├── contacts.php           # Contact form submission
│   ├── get_contacts.php       # Get all contacts
│   ├── get_contact.php        # Get single contact
│   ├── update_status.php      # Update contact status
│   ├── company_info.php       # Company contact info
│   ├── social_media.php       # Social media links
│   ├── locations.php          # All office locations
│   └── locations_main.php     # Main office location
├── database.sql               # MySQL schema
└── .htaccess                  # Root rewrite rules
```

---

## Setup Instructions

### 1. Database Setup

Upload `database.sql` to your GoDaddy hosting and import it:

**Via phpMyAdmin:**
1. Login to GoDaddy cPanel
2. Open phpMyAdmin
3. Create a new database named `bd_enterprises`
4. Select the database
5. Click "Import"
6. Choose `database.sql`
7. Click "Go"

**Via command line (if available):**
```bash
mysql -u username -p database_name < database.sql
```

### 2. Upload Files

Upload all API files to your GoDaddy hosting:

```
public_html/
├── api/
│   ├── index.php
│   ├── .htaccess
│   ├── db.php
│   ├── health.php
│   ├── contacts.php
│   ├── get_contacts.php
│   ├── get_contact.php
│   ├── update_status.php
│   ├── company_info.php
│   ├── social_media.php
│   ├── locations.php
│   └── locations_main.php
└── (other frontend files)
```

### 3. Configure Database Connection

The database connection uses environment variables. Set them in GoDaddy cPanel:

**Option A: Using PHP Configuration (Recommended)**

Edit `api/db.php` and replace placeholder values:

```php
$db_host = 'your-godaddy-mysql-host';  // Usually localhost or IP
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_password = 'your_database_password';
```

**Option B: Using Environment Variables**

Create a `.env.php` file in the api directory:

```php
<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'bd_enterprises');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
?>
```

Then modify `api/db.php` to load it:

```php
require_once __DIR__ . '/.env.php';
$db_host = defined('DB_HOST') ? DB_HOST : 'localhost';
// etc...
```

### 4. Test the Installation

Visit your API endpoint:

```
https://yourdomain.com/api/health
```

Expected response:

```json
{
  "success": true,
  "message": "Server is running",
  "data": []
}
```

---

## API Endpoints

### Base URL

```
https://yourdomain.com/api/
```

### Response Format (All Endpoints)

```json
{
  "success": true,
  "message": "Description",
  "data": {}
}
```

### Endpoints Overview

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/health` | Server health check |
| POST | `/contacts` | Submit contact form |
| GET | `/get_contacts` | Get all contacts |
| GET | `/get_contact?id=X` | Get single contact |
| POST/PUT | `/update_status` | Update contact status |
| GET | `/company_info` | Get company contact info |
| GET | `/social_media` | Get social media links |
| GET | `/locations` | Get all office locations |
| GET | `/locations_main` | Get main office location |

---

## Endpoint Reference

### 1. Health Check

**Endpoint**: `GET /api/health`

**Response**:
```json
{
  "success": true,
  "message": "Server is running",
  "data": []
}
```

---

### 2. Submit Contact Form

**Endpoint**: `POST /api/contacts`

**Request Body**:
```json
{
  "firstName": "John",
  "lastName": "Doe",
  "email": "john@example.com",
  "phone": "(123) 456-7890",
  "companyName": "Acme Corp",
  "serviceType": "Fire Alarm Systems",
  "message": "I need fire safety services",
  "preferredMethod": "email"
}
```

**Required Fields**: `firstName`, `lastName`, `email`, `message`

**Success Response** (201):
```json
{
  "success": true,
  "message": "Contact submission received successfully",
  "data": {
    "id": 1,
    "email": "john@example.com"
  }
}
```

**Error Response** (400):
```json
{
  "success": false,
  "message": "Missing required fields: firstName, lastName, email, message",
  "data": []
}
```

---

### 3. Get All Contacts

**Endpoint**: `GET /api/get_contacts`

**Response** (200):
```json
{
  "success": true,
  "message": "Contacts retrieved successfully",
  "data": {
    "contacts": [
      {
        "id": 1,
        "first_name": "John",
        "last_name": "Doe",
        "email": "john@example.com",
        "phone": "(123) 456-7890",
        "company_name": "Acme Corp",
        "service_type": "Fire Alarm Systems",
        "message": "I need fire safety services",
        "preferred_contact_method": "email",
        "status": "new",
        "created_at": "2026-01-23 10:30:00",
        "updated_at": "2026-01-23 10:30:00"
      }
    ],
    "count": 1
  }
}
```

---

### 4. Get Single Contact

**Endpoint**: `GET /api/get_contact?id=1`

**Response** (200):
```json
{
  "success": true,
  "message": "Contact retrieved successfully",
  "data": {
    "id": 1,
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "phone": "(123) 456-7890",
    "company_name": "Acme Corp",
    "service_type": "Fire Alarm Systems",
    "message": "I need fire safety services",
    "preferred_contact_method": "email",
    "status": "new",
    "created_at": "2026-01-23 10:30:00",
    "updated_at": "2026-01-23 10:30:00"
  }
}
```

**Error Response** (404):
```json
{
  "success": false,
  "message": "Contact not found",
  "data": []
}
```

---

### 5. Update Contact Status

**Endpoint**: `POST /api/update_status` or `PUT /api/update_status`

**Request Body**:
```json
{
  "id": 1,
  "status": "in_progress"
}
```

**Valid Status Values**: `new`, `in_progress`, `resolved`, `closed`

**Response** (200):
```json
{
  "success": true,
  "message": "Contact status updated successfully",
  "data": {
    "id": 1,
    "status": "in_progress"
  }
}
```

**Error Response** (404):
```json
{
  "success": false,
  "message": "Contact not found",
  "data": []
}
```

---

### 6. Get Company Contact Info

**Endpoint**: `GET /api/company_info` or `GET /api/company-info`

**Response** (200):
```json
{
  "success": true,
  "message": "Company info retrieved successfully",
  "data": {
    "phone": {
      "value": "7499953708",
      "label": "Main Phone"
    },
    "email": {
      "value": "omchaudhary2111@gmail.com",
      "label": "Main Email"
    },
    "whatsapp": {
      "value": "7499953708",
      "label": "WhatsApp"
    },
    "address": {
      "value": "123 Safety Avenue, Fire District, FD 12345",
      "label": "Main Office"
    }
  }
}
```

---

### 7. Get Social Media Links

**Endpoint**: `GET /api/social_media` or `GET /api/social-media`

**Response** (200):
```json
{
  "success": true,
  "message": "Social media links retrieved successfully",
  "data": [
    {
      "id": 1,
      "platform": "facebook",
      "url": "https://facebook.com/bdenterprises",
      "icon_name": "facebook"
    },
    {
      "id": 2,
      "platform": "twitter",
      "url": "https://twitter.com/bdenterprises",
      "icon_name": "twitter"
    }
  ]
}
```

---

### 8. Get All Locations

**Endpoint**: `GET /api/locations`

**Response** (200):
```json
{
  "success": true,
  "message": "Locations retrieved successfully",
  "data": [
    {
      "id": 1,
      "name": "BD Enterprises - Main Office",
      "latitude": 40.7128,
      "longitude": -74.006,
      "address": "123 Safety Avenue, Fire District",
      "city": "New York",
      "state": "NY",
      "zip_code": "10001",
      "phone": "7499953708",
      "email": "omchaudhary2111@gmail.com",
      "is_main_office": true,
      "is_active": true,
      "created_at": "2026-01-23 10:30:00",
      "updated_at": "2026-01-23 10:30:00"
    }
  ]
}
```

---

### 9. Get Main Office Location

**Endpoint**: `GET /api/locations_main` or `GET /api/locations/main`

**Response** (200):
```json
{
  "success": true,
  "message": "Main office location retrieved successfully",
  "data": {
    "id": 1,
    "name": "BD Enterprises - Main Office",
    "latitude": 40.7128,
    "longitude": -74.006,
    "address": "123 Safety Avenue, Fire District",
    "city": "New York",
    "state": "NY",
    "zip_code": "10001",
    "phone": "7499953708",
    "email": "omchaudhary2111@gmail.com",
    "is_main_office": true,
    "is_active": true,
    "created_at": "2026-01-23 10:30:00",
    "updated_at": "2026-01-23 10:30:00"
  }
}
```

**Error Response** (404):
```json
{
  "success": false,
  "message": "Main office location not found",
  "data": []
}
```

---

## CORS Support

All endpoints support CORS with:

```
Access-Control-Allow-Origin: *
Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS
Access-Control-Allow-Headers: Content-Type, Accept
```

This allows requests from any domain, including your frontend on GoDaddy.

---

## Security Features

✅ **Prepared Statements**: All queries use parameterized prepared statements to prevent SQL injection

✅ **Input Validation**: All endpoints validate required fields and data types

✅ **Error Handling**: Proper HTTP status codes and error messages

✅ **No Secrets**: Database credentials configured via environment, never hardcoded

✅ **UTF-8 Encoding**: Full UTF-8MB4 character support for international text

---

## Troubleshooting

### 404 Errors

**Problem**: Endpoint returns 404

**Solutions**:
1. Verify files are uploaded to `/api/` directory
2. Check `.htaccess` is present and enabled
3. Verify GoDaddy has mod_rewrite enabled
4. Test direct file access: `https://yourdomain.com/api/health.php`

### Database Connection Errors

**Problem**: "Database connection failed"

**Solutions**:
1. Verify database credentials in `api/db.php`
2. Check database name is correct
3. Verify MySQL user has proper permissions
4. Ensure GoDaddy MySQL service is running

### CORS Issues

**Problem**: Frontend can't connect to backend

**Solutions**:
1. All endpoints already allow CORS
2. Check browser console for exact error
3. Verify API URL is correct
4. Test with curl or Postman

### UTF-8 Character Issues

If special characters appear garbled:
1. Ensure database table charset is utf8mb4
2. Verify connection charset in `api/db.php`
3. Set correct Content-Type header (already done)

---

## Database Schema

The backend uses 4 main tables:

1. **contact_submissions**: Customer contact form submissions
2. **company_contact_info**: Company phone, email, address, WhatsApp
3. **social_media_links**: Social media profiles
4. **company_locations**: Office locations with coordinates

See `database.sql` for complete schema.

---

## Production Checklist

Before going live:

- [ ] Upload all files to GoDaddy `/api/` directory
- [ ] Import `database.sql` into MySQL database
- [ ] Configure database credentials in `api/db.php`
- [ ] Test health endpoint: `GET /api/health`
- [ ] Test all endpoints with Postman or curl
- [ ] Verify CORS works from frontend domain
- [ ] Enable error logging (check GoDaddy error logs)
- [ ] Set proper file permissions (644 for files, 755 for directories)
- [ ] Backup database regularly

---

## Support

For issues or questions:
1. Check error logs in GoDaddy cPanel
2. Test endpoints directly with curl or Postman
3. Verify database connectivity
4. Review response JSON for error messages

