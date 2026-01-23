# BD Enterprises Backend API

A complete backend solution for managing contact form submissions, company information, and location data for the BD Enterprises fire safety website.

## Features

- **Contact Form Management**: Store and manage customer inquiries
- **Company Information**: Centralized management of phone, email, WhatsApp, and address
- **Social Media Links**: Track and manage social media profiles
- **Location Management**: Store multiple office locations with coordinates for mapping
- **RESTful API**: Easy-to-use API endpoints for all operations

## Prerequisites

- **Node.js** (v14 or higher)
- **MySQL** (v5.7 or higher)
- **npm** (comes with Node.js)

## Installation

### 1. Database Setup

1. Open MySQL command line or MySQL Workbench
2. Import the `database.sql` file to create all tables and insert sample data:

```bash
mysql -u root -p < database.sql
```

Or if you have a password:
```bash
mysql -u root -p your_password < database.sql
```

3. Verify the database was created:
```bash
mysql -u root -p
USE bd_enterprises;
SHOW TABLES;
```

### 2. Backend Setup

1. Navigate to the backend directory:
```bash
cd bd-enterprises-backend
```

2. Install dependencies:
```bash
npm install
```

3. Create a `.env` file (copy from `.env.example`):
```bash
cp .env.example .env
```

4. Update `.env` with your database credentials:
```env
DB_HOST=localhost
DB_USER=root
DB_PASSWORD=your_password
DB_NAME=bd_enterprises
PORT=5000
NODE_ENV=development
FRONTEND_URL=http://localhost:3000

# Optional: Twilio for SMS/WhatsApp notifications
TWILIO_ACCOUNT_SID=
TWILIO_AUTH_TOKEN=
TWILIO_PHONE_NUMBER=
TWILIO_WHATSAPP_NUMBER=
ADMIN_PHONE=
```

5. Start the server:
```bash
npm start
```

Or for development with auto-reload:
```bash
npm run dev
```

The server should start on `http://localhost:5000`

## API Endpoints

### Contact Submissions

#### Submit Contact Form
- **POST** `/api/contacts`
- **Body**:
```json
{
  "firstName": "John",
  "lastName": "Doe",
  "email": "john@example.com",
  "phone": "+1 (555) 123-4567",
  "companyName": "Acme Corp",
  "serviceType": "Fire Extinguisher Systems",
  "message": "I need fire safety consultation",
  "preferredMethod": "email"
}
```
- **Response**:
```json
{
  "success": true,
  "message": "Contact form submitted successfully",
  "data": {
    "id": 1,
    "firstName": "John",
    "lastName": "Doe",
    "email": "john@example.com"
  }
}
```

#### Get All Contacts (Admin)
- **GET** `/api/contacts`
- **Response**: Array of all contact submissions

#### Get Contact by ID
- **GET** `/api/contacts/:id`
- **Response**: Single contact submission

#### Update Contact Status
- **PUT** `/api/contacts/:id/status`
- **Body**:
```json
{
  "status": "in_progress"
}
```
- **Valid Status Values**: `new`, `in_progress`, `resolved`, `closed`

### Company Information

#### Get Company Contact Info
- **GET** `/api/company-info`
- **Response**:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "contact_type": "phone",
      "value": "+1 (555) 123-4567",
      "label": "Main Phone",
      "is_active": true
    }
  ]
}
```

### Social Media

#### Get Social Media Links
- **GET** `/api/social-media`
- **Response**:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "platform": "facebook",
      "url": "https://facebook.com/bdenterprises",
      "icon_name": "facebook",
      "is_active": true
    }
  ]
}
```

### Locations

#### Get All Locations
- **GET** `/api/locations`

#### Get Main Office Location
- **GET** `/api/locations/main`
- **Response**:
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "BD Enterprises - Main Office",
    "latitude": 40.7128,
    "longitude": -74.0060,
    "address": "123 Safety Avenue, Fire District",
    "city": "New York",
    "state": "NY",
    "zip_code": "10001",
    "phone": "+1 (555) 123-4567",
    "email": "contact@bdenterprises.com",
    "is_main_office": true
  }
}
```

### Health Check

#### Server Status
- **GET** `/api/health`
- **Response**:
```json
{
  "message": "Server is running",
  "timestamp": "2024-01-10T12:00:00.000Z"
}
```

## Database Schema

### contact_submissions
- `id`: Auto-incremented primary key
- `first_name`: Customer first name
- `last_name`: Customer last name
- `email`: Customer email
- `phone`: Customer phone number
- `company_name`: Customer company
- `service_type`: Service interest
- `message`: Message content
- `preferred_contact_method`: email/phone/whatsapp
- `status`: new/in_progress/resolved/closed
- `created_at`: Submission timestamp
- `updated_at`: Last update timestamp

### company_contact_info
- `id`: Primary key
- `contact_type`: phone/email/address/whatsapp
- `value`: Contact detail
- `label`: Display label
- `is_active`: Availability flag

### social_media_links
- `id`: Primary key
- `platform`: Social media platform name
- `url`: Profile URL
- `icon_name`: Icon identifier
- `is_active`: Availability flag

### company_locations
- `id`: Primary key
- `name`: Office name
- `latitude`: Map latitude
- `longitude`: Map longitude
- `address`: Full address
- `city`: City name
- `state`: State/Province
- `zip_code`: Postal code
- `phone`: Office phone
- `email`: Office email
- `is_main_office`: Flag for main office
- `is_active`: Availability flag

## Frontend Integration

The React frontend at `../bd-enterprises/src/components/Contact.js` automatically:

1. Fetches company information from `/api/company-info`
2. Fetches social media links from `/api/social-media`
3. Fetches main location from `/api/locations/main`
4. Submits contact forms to `/api/contacts`

### Required Environment Variables (Frontend)

No specific env vars needed, but ensure the backend is running on `http://localhost:5000`

## Managing Data

### Update Company Contact Information

Use MySQL to update company information:

```sql
UPDATE company_contact_info 
SET value = '+1 (555) 999-9999' 
WHERE contact_type = 'phone' AND contact_type = 'phone';
```

### Add New Location

```sql
INSERT INTO company_locations 
(name, latitude, longitude, address, city, state, zip_code, phone, email) 
VALUES 
('BD Enterprises - Branch Office', 34.0522, -118.2437, '456 Fire Safety Blvd', 'Los Angeles', 'CA', '90001', '+1 (555) 234-5678', 'la@bdenterprises.com');
```

### Update Social Media Link

```sql
UPDATE social_media_links 
SET url = 'https://facebook.com/newprofile' 
WHERE platform = 'facebook';
```

## Error Handling

All endpoints return consistent error responses:

```json
{
  "success": false,
  "message": "Error description",
  "error": "Detailed error (development only)"
}
```

## Security Notes

- Always use HTTPS in production
- Implement authentication for admin endpoints
- Validate all input data
- Use environment variables for sensitive data
- Never commit `.env` file to version control

## Troubleshooting

### Connection Refused
- Ensure MySQL is running
- Check database credentials in `.env`
- Verify database name

### CORS Errors
- Check `FRONTEND_URL` in `.env` matches your frontend URL
- Ensure backend is running on the correct port

### Map Not Loading
- Google Maps API key may be invalid
- Replace the key in Contact.js with your own from Google Cloud Console

## Production Deployment

1. Set `NODE_ENV=production` in `.env`
2. Use a process manager like PM2
3. Set up SSL certificates
4. Use environment variables for all sensitive data
5. Implement rate limiting
6. Set up database backups
7. Use a reverse proxy (nginx)

## Support

For issues or questions, contact the development team.
