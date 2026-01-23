# BD Enterprises Backend API - Complete Documentation

## Overview

Production-ready Node.js Express backend with MySQL integration for BD Enterprises Fire Safety & IT Solutions.

**Status**: ✅ Production Ready  
**Framework**: Express.js  
**Database**: MySQL (8.0+)  
**Environment**: Node.js v14+  
**Deployment**: Vercel Compatible

---

## Features

✅ RESTful API with 9 endpoints  
✅ MySQL connection pooling  
✅ CORS support  
✅ Environment variable configuration  
✅ Consistent JSON response format  
✅ Error handling and validation  
✅ Production and development modes  
✅ Vercel serverless compatible  

---

## Quick Start

### 1. Prerequisites

```bash
# Check Node.js version (should be 14+)
node --version

# Check npm version
npm --version

# Ensure MySQL is running
mysql --version
```

### 2. Install Dependencies

```bash
npm install
```

### 3. Configure Environment

```bash
# Copy example configuration
cp .env.example .env

# Edit .env with your database credentials
# DB_HOST=localhost
# DB_USER=root
# DB_PASSWORD=your_password
# DB_NAME=bd_enterprises
# PORT=5000
# FRONTEND_URL=http://localhost:3000
```

### 4. Setup Database

```bash
# Import database schema
mysql -u root -p bd_enterprises < database.sql

# Verify tables
mysql -u root -p -e "USE bd_enterprises; SHOW TABLES;"
```

### 5. Start Server

```bash
# Development with auto-reload
npm run dev

# Production
npm start
```

Expected output:
```
╔════════════════════════════════════════════════╗
║  BD Enterprises Backend API                    ║
╠════════════════════════════════════════════════╣
║  Server running on port 5000                   ║
║  Environment: development                      ║
║  CORS Origin: http://localhost:3000            ║
╚════════════════════════════════════════════════╝
```

---

## API Endpoints

### Base URL
```
Local Development: http://localhost:5000
Production: https://your-domain.com/api
Vercel: https://your-project.vercel.app
```

### Response Format (All Endpoints)

```json
{
  "success": true,
  "message": "Description of response",
  "data": {}
}
```

---

## Endpoint Reference

### 1. Health Check

**Endpoint**: `GET /api/health`

**Description**: Check if server is running

**Response**:
```json
{
  "success": true,
  "message": "Server is running",
  "data": {
    "status": "healthy"
  }
}
```

---

### 2. Submit Contact Form

**Endpoint**: `POST /api/contacts`

**Description**: Submit a new contact form submission

**Request Body**:
```json
{
  "firstName": "John",
  "lastName": "Doe",
  "email": "john@example.com",
  "phone": "(123) 456-7890",
  "companyName": "Acme Corp",
  "serviceType": "Cloud Computing Solutions",
  "message": "I'm interested in your services",
  "preferredMethod": "email"
}
```

**Required Fields**: `firstName`, `lastName`, `email`, `message`

**Optional Fields**: `phone`, `companyName`, `serviceType`, `preferredMethod`

**preferredMethod Values**: `email`, `phone`, `whatsapp` (default: `email`)

**Success Response** (201):
```json
{
  "success": true,
  "message": "Contact submission received successfully",
  "data": {
    "id": 42,
    "email": "john@example.com"
  }
}
```

**Error Response** (400):
```json
{
  "success": false,
  "message": "Missing required fields: firstName, lastName, email, message",
  "data": {}
}
```

---

### 3. Get All Contacts

**Endpoint**: `GET /api/contacts`

**Description**: Retrieve all contact submissions with pagination and filtering

**Query Parameters**:
- `status` (optional): Filter by status (`new`, `in_progress`, `resolved`, `closed`)
- `limit` (optional): Number of results (default: 50, max: 100)
- `offset` (optional): Number of results to skip (default: 0)

**Example**:
```
GET /api/contacts?status=new&limit=10&offset=0
```

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
        "service_type": "Cloud Computing Solutions",
        "message": "I'm interested in your services",
        "preferred_contact_method": "email",
        "status": "new",
        "created_at": "2026-01-23T10:30:00.000Z",
        "updated_at": "2026-01-23T10:30:00.000Z"
      }
    ],
    "count": 1
  }
}
```

---

### 4. Get Contact by ID

**Endpoint**: `GET /api/contacts/:id`

**Description**: Retrieve a specific contact submission by ID

**Parameters**:
- `id` (required): Contact submission ID

**Example**:
```
GET /api/contacts/42
```

**Response** (200):
```json
{
  "success": true,
  "message": "Contact retrieved successfully",
  "data": {
    "id": 42,
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "phone": "(123) 456-7890",
    "company_name": "Acme Corp",
    "service_type": "Cloud Computing Solutions",
    "message": "I'm interested in your services",
    "preferred_contact_method": "email",
    "status": "new",
    "created_at": "2026-01-23T10:30:00.000Z",
    "updated_at": "2026-01-23T10:30:00.000Z"
  }
}
```

**Error Response** (404):
```json
{
  "success": false,
  "message": "Contact not found",
  "data": {}
}
```

---

### 5. Update Contact Status

**Endpoint**: `PUT /api/contacts/:id/status`

**Description**: Update the status of a contact submission

**Parameters**:
- `id` (required): Contact submission ID

**Request Body**:
```json
{
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
    "id": 42,
    "status": "in_progress"
  }
}
```

---

### 6. Get Company Contact Information

**Endpoint**: `GET /api/company-info`

**Description**: Get phone, email, WhatsApp, and address information

**Response** (200):
```json
{
  "success": true,
  "message": "Company contact information retrieved successfully",
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

**Endpoint**: `GET /api/social-media`

**Description**: Get all active social media links

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
      "icon_name": "facebook",
      "is_active": true,
      "created_at": "2026-01-23T10:00:00.000Z",
      "updated_at": "2026-01-23T10:00:00.000Z"
    },
    {
      "id": 2,
      "platform": "twitter",
      "url": "https://twitter.com/bdenterprises",
      "icon_name": "twitter",
      "is_active": true,
      "created_at": "2026-01-23T10:00:00.000Z",
      "updated_at": "2026-01-23T10:00:00.000Z"
    },
    {
      "id": 3,
      "platform": "linkedin",
      "url": "https://linkedin.com/company/bdenterprises",
      "icon_name": "linkedin",
      "is_active": true,
      "created_at": "2026-01-23T10:00:00.000Z",
      "updated_at": "2026-01-23T10:00:00.000Z"
    },
    {
      "id": 4,
      "platform": "instagram",
      "url": "https://instagram.com/bdenterprises",
      "icon_name": "instagram",
      "is_active": true,
      "created_at": "2026-01-23T10:00:00.000Z",
      "updated_at": "2026-01-23T10:00:00.000Z"
    }
  ]
}
```

---

### 8. Get All Office Locations

**Endpoint**: `GET /api/locations`

**Description**: Get all active office locations with coordinates for mapping

**Response** (200):
```json
{
  "success": true,
  "message": "Locations retrieved successfully",
  "data": [
    {
      "id": 1,
      "name": "BD Enterprises - Main Office",
      "latitude": "40.71280000",
      "longitude": "-74.00600000",
      "address": "123 Safety Avenue, Fire District",
      "city": "New York",
      "state": "NY",
      "zip_code": "10001",
      "phone": "7499953708",
      "email": "omchaudhary2111@gmail.com",
      "is_main_office": true,
      "is_active": true,
      "created_at": "2026-01-23T10:00:00.000Z",
      "updated_at": "2026-01-23T10:00:00.000Z"
    }
  ]
}
```

---

### 9. Get Main Office Location

**Endpoint**: `GET /api/locations/main`

**Description**: Get the main office location (convenience endpoint)

**Response** (200):
```json
{
  "success": true,
  "message": "Main office location retrieved successfully",
  "data": {
    "id": 1,
    "name": "BD Enterprises - Main Office",
    "latitude": "40.71280000",
    "longitude": "-74.00600000",
    "address": "123 Safety Avenue, Fire District",
    "city": "New York",
    "state": "NY",
    "zip_code": "10001",
    "phone": "7499953708",
    "email": "omchaudhary2111@gmail.com",
    "is_main_office": true,
    "is_active": true,
    "created_at": "2026-01-23T10:00:00.000Z",
    "updated_at": "2026-01-23T10:00:00.000Z"
  }
}
```

---

## Frontend Integration

### Example: Fetch Company Info

```javascript
// In your React component
useEffect(() => {
  fetch('http://localhost:5000/api/company-info')
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        console.log('Phone:', data.data.phone.value);
        console.log('Email:', data.data.email.value);
      }
    });
}, []);
```

### Example: Submit Contact Form

```javascript
const handleSubmit = async (formData) => {
  try {
    const response = await fetch('http://localhost:5000/api/contacts', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        firstName: formData.firstName,
        lastName: formData.lastName,
        email: formData.email,
        phone: formData.phone,
        companyName: formData.company,
        serviceType: formData.service,
        message: formData.message,
        preferredMethod: formData.method
      })
    });

    const result = await response.json();
    
    if (result.success) {
      alert('Message sent successfully!');
    } else {
      alert('Error: ' + result.message);
    }
  } catch (error) {
    console.error('Error:', error);
  }
};
```

---

## Database Schema

### contact_submissions
```sql
id                          - Auto-increment primary key
first_name                  - VARCHAR(100)
last_name                   - VARCHAR(100)
email                       - VARCHAR(150)
phone                       - VARCHAR(20) [optional]
company_name                - VARCHAR(150) [optional]
service_type                - VARCHAR(100) [optional]
message                     - LONGTEXT
preferred_contact_method    - ENUM('email', 'phone', 'whatsapp')
status                      - ENUM('new', 'in_progress', 'resolved', 'closed')
created_at                  - TIMESTAMP
updated_at                  - TIMESTAMP
```

### company_contact_info
```sql
id                  - Auto-increment primary key
contact_type        - ENUM('phone', 'email', 'address', 'whatsapp')
value               - VARCHAR(255)
label               - VARCHAR(100)
is_active           - BOOLEAN
created_at          - TIMESTAMP
updated_at          - TIMESTAMP
```

### social_media_links
```sql
id          - Auto-increment primary key
platform    - VARCHAR(50)
url         - VARCHAR(255)
icon_name   - VARCHAR(50)
is_active   - BOOLEAN
created_at  - TIMESTAMP
updated_at  - TIMESTAMP
```

### company_locations
```sql
id              - Auto-increment primary key
name            - VARCHAR(150)
latitude        - DECIMAL(10, 8)
longitude       - DECIMAL(11, 8)
address         - VARCHAR(255)
city            - VARCHAR(100)
state           - VARCHAR(100)
zip_code        - VARCHAR(20)
phone           - VARCHAR(20)
email           - VARCHAR(150)
is_main_office  - BOOLEAN
is_active       - BOOLEAN
created_at      - TIMESTAMP
updated_at      - TIMESTAMP
```

---

## Environment Variables

```env
# Required
DB_HOST=localhost
DB_USER=root
DB_PASSWORD=your_password
DB_NAME=bd_enterprises

# Optional
PORT=5000
NODE_ENV=development
FRONTEND_URL=http://localhost:3000

# Optional: Twilio (for SMS/WhatsApp notifications)
TWILIO_ACCOUNT_SID=
TWILIO_AUTH_TOKEN=
TWILIO_PHONE_NUMBER=
TWILIO_WHATSAPP_NUMBER=
ADMIN_PHONE=

# Optional: Email notifications
EMAIL_HOST=smtp.gmail.com
EMAIL_PORT=587
EMAIL_USER=
EMAIL_PASSWORD=
```

---

## Deployment

### Local Development

```bash
npm install
npm run dev
```

### Production (Node.js Server)

```bash
npm install --production
npm start
```

### Vercel Deployment

1. **Connect repository** to Vercel
2. **Environment variables**: Add in Vercel dashboard:
   - `DB_HOST`
   - `DB_USER`
   - `DB_PASSWORD`
   - `DB_NAME`
   - `FRONTEND_URL`
   - `NODE_ENV=production`

3. **Deploy**: Vercel automatically detects `server.js`

**File**: `vercel.json` (optional)
```json
{
  "functions": {
    "server.js": {
      "memory": 1024,
      "maxDuration": 30
    }
  }
}
```

---

## Error Handling

All errors follow this format:

```json
{
  "success": false,
  "message": "Error description",
  "data": {}
}
```

**HTTP Status Codes**:
- `200` - Success
- `201` - Created (POST)
- `400` - Bad Request (validation error)
- `404` - Not Found
- `500` - Server Error

---

## Monitoring & Logs

Enable logging in development:

```env
NODE_ENV=development
```

Logs include:
- Request method and path
- Database connection status
- Error messages with stack traces

Disable request logging in production:

```env
NODE_ENV=production
```

---

## Performance Tips

1. **Database Indexes**: Already configured on frequently queried columns
2. **Connection Pooling**: Configured with 10 connections
3. **CORS Caching**: Configure CDN headers on frontend
4. **API Pagination**: Use `limit` and `offset` for large datasets
5. **Compression**: Use `gzip` on production servers

---

## Troubleshooting

### "Cannot find module 'mysql2'"

```bash
npm install mysql2
```

### "Database connection failed"

1. Check MySQL is running
2. Verify credentials in `.env`
3. Verify database `bd_enterprises` exists
4. Run: `mysql -u root -p < database.sql`

### "CORS errors in frontend"

1. Check `FRONTEND_URL` in `.env`
2. Verify frontend is running on correct port
3. Check browser console for exact error

### "Contact submission fails"

1. Check all required fields are provided
2. Verify database is connected
3. Check MySQL user has INSERT permissions
4. Review server logs for errors

---

## Security Best Practices

✅ Environment variables for all credentials  
✅ Input validation on all endpoints  
✅ SQL prepared statements (mysql2)  
✅ CORS whitelist  
✅ No sensitive data in logs  
✅ HTTPS in production  
✅ Regular security updates  

---

## Support & Resources

- **Node.js**: https://nodejs.org/docs/
- **Express.js**: https://expressjs.com/
- **MySQL Docs**: https://dev.mysql.com/doc/
- **Vercel Docs**: https://vercel.com/docs

---

## License

ISC

---

**Last Updated**: January 23, 2026  
**Status**: ✅ Production Ready
