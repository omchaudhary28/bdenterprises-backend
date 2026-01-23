/**
 * BD Enterprises Backend API
 * Main server file with Express setup and all API routes
 */

const express = require('express');
const cors = require('cors');
const bodyParser = require('body-parser');
require('dotenv').config();

// Import database setup
const pool = require('./setup');

const app = express();

// ============================================
// MIDDLEWARE SETUP
// ============================================

// CORS Configuration
app.use(cors({
  origin: process.env.FRONTEND_URL || 'http://localhost:3000',
  credentials: true
}));

// Body Parser Middleware
app.use(bodyParser.json({ limit: '10mb' }));
app.use(bodyParser.urlencoded({ limit: '10mb', extended: true }));

// Request Logging Middleware (for development)
app.use((req, res, next) => {
  if (process.env.NODE_ENV !== 'production') {
    console.log(`${new Date().toISOString()} - ${req.method} ${req.path}`);
  }
  next();
});

// ============================================
// UTILITY FUNCTION: Consistent JSON Response
// ============================================
const sendResponse = (res, statusCode, success, message, data = null) => {
  res.status(statusCode).json({
    success,
    message,
    data: data || {}
  });
};

// ============================================
// API ROUTES
// ============================================

/**
 * Health Check Endpoint
 * GET /api/health
 */
app.get('/api/health', (req, res) => {
  sendResponse(res, 200, true, 'Server is running', { status: 'healthy' });
});

/**
 * Submit Contact Form
 * POST /api/contacts
 */
app.post('/api/contacts', async (req, res) => {
  try {
    const {
      firstName,
      lastName,
      email,
      phone,
      companyName,
      serviceType,
      message,
      preferredMethod
    } = req.body;

    // Validation
    if (!firstName || !lastName || !email || !message) {
      return sendResponse(res, 400, false, 'Missing required fields: firstName, lastName, email, message');
    }

    // Insert into database
    const query = `
      INSERT INTO contact_submissions 
      (first_name, last_name, email, phone, company_name, service_type, message, preferred_contact_method, status)
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'new')
    `;

    const values = [
      firstName,
      lastName,
      email,
      phone || null,
      companyName || null,
      serviceType || null,
      message,
      preferredMethod || 'email'
    ];

    const connection = await pool.getConnection();
    const [result] = await connection.execute(query, values);
    connection.release();

    sendResponse(res, 201, true, 'Contact submission received successfully', {
      id: result.insertId,
      email: email
    });
  } catch (error) {
    console.error('Error submitting contact:', error);
    sendResponse(res, 500, false, 'Error processing contact submission: ' + error.message);
  }
});

/**
 * Get All Contacts
 * GET /api/contacts
 */
app.get('/api/contacts', async (req, res) => {
  try {
    const { status, limit = 50, offset = 0 } = req.query;

    let query = 'SELECT * FROM contact_submissions';
    const values = [];

    if (status) {
      query += ' WHERE status = ?';
      values.push(status);
    }

    query += ' ORDER BY created_at DESC LIMIT ? OFFSET ?';
    values.push(parseInt(limit), parseInt(offset));

    const connection = await pool.getConnection();
    const [contacts] = await connection.execute(query, values);
    connection.release();

    sendResponse(res, 200, true, 'Contacts retrieved successfully', {
      contacts,
      count: contacts.length
    });
  } catch (error) {
    console.error('Error fetching contacts:', error);
    sendResponse(res, 500, false, 'Error fetching contacts: ' + error.message);
  }
});

/**
 * Get Contact by ID
 * GET /api/contacts/:id
 */
app.get('/api/contacts/:id', async (req, res) => {
  try {
    const { id } = req.params;

    const query = 'SELECT * FROM contact_submissions WHERE id = ?';
    const connection = await pool.getConnection();
    const [contacts] = await connection.execute(query, [id]);
    connection.release();

    if (contacts.length === 0) {
      return sendResponse(res, 404, false, 'Contact not found');
    }

    sendResponse(res, 200, true, 'Contact retrieved successfully', contacts[0]);
  } catch (error) {
    console.error('Error fetching contact:', error);
    sendResponse(res, 500, false, 'Error fetching contact: ' + error.message);
  }
});

/**
 * Update Contact Status
 * PUT /api/contacts/:id/status
 */
app.put('/api/contacts/:id/status', async (req, res) => {
  try {
    const { id } = req.params;
    const { status } = req.body;

    // Validate status
    const validStatuses = ['new', 'in_progress', 'resolved', 'closed'];
    if (!status || !validStatuses.includes(status)) {
      return sendResponse(res, 400, false, `Invalid status. Must be one of: ${validStatuses.join(', ')}`);
    }

    const query = 'UPDATE contact_submissions SET status = ? WHERE id = ?';
    const connection = await pool.getConnection();
    const [result] = await connection.execute(query, [status, id]);
    connection.release();

    if (result.affectedRows === 0) {
      return sendResponse(res, 404, false, 'Contact not found');
    }

    sendResponse(res, 200, true, 'Contact status updated successfully', { id, status });
  } catch (error) {
    console.error('Error updating contact status:', error);
    sendResponse(res, 500, false, 'Error updating contact status: ' + error.message);
  }
});

/**
 * Get Company Contact Information
 * GET /api/company-info
 */
app.get('/api/company-info', async (req, res) => {
  try {
    const query = 'SELECT * FROM company_contact_info WHERE is_active = TRUE';

    const connection = await pool.getConnection();
    const [info] = await connection.execute(query);
    connection.release();

    // Convert to object format for easier frontend consumption
    const formatted = {};
    info.forEach(item => {
      formatted[item.contact_type] = {
        value: item.value,
        label: item.label
      };
    });

    sendResponse(res, 200, true, 'Company contact information retrieved successfully', formatted);
  } catch (error) {
    console.error('Error fetching company info:', error);
    sendResponse(res, 500, false, 'Error fetching company info: ' + error.message);
  }
});

/**
 * Get Social Media Links
 * GET /api/social-media
 */
app.get('/api/social-media', async (req, res) => {
  try {
    const query = 'SELECT * FROM social_media_links WHERE is_active = TRUE ORDER BY platform';

    const connection = await pool.getConnection();
    const [socialMedia] = await connection.execute(query);
    connection.release();

    sendResponse(res, 200, true, 'Social media links retrieved successfully', socialMedia);
  } catch (error) {
    console.error('Error fetching social media:', error);
    sendResponse(res, 500, false, 'Error fetching social media: ' + error.message);
  }
});

/**
 * Get All Locations
 * GET /api/locations
 */
app.get('/api/locations', async (req, res) => {
  try {
    const query = 'SELECT * FROM company_locations WHERE is_active = TRUE ORDER BY is_main_office DESC, name';

    const connection = await pool.getConnection();
    const [locations] = await connection.execute(query);
    connection.release();

    sendResponse(res, 200, true, 'Locations retrieved successfully', locations);
  } catch (error) {
    console.error('Error fetching locations:', error);
    sendResponse(res, 500, false, 'Error fetching locations: ' + error.message);
  }
});

/**
 * Get Main Office Location
 * GET /api/locations/main
 */
app.get('/api/locations/main', async (req, res) => {
  try {
    const query = 'SELECT * FROM company_locations WHERE is_main_office = TRUE AND is_active = TRUE LIMIT 1';

    const connection = await pool.getConnection();
    const [locations] = await connection.execute(query);
    connection.release();

    if (locations.length === 0) {
      return sendResponse(res, 404, false, 'Main office location not found');
    }

    sendResponse(res, 200, true, 'Main office location retrieved successfully', locations[0]);
  } catch (error) {
    console.error('Error fetching main location:', error);
    sendResponse(res, 500, false, 'Error fetching main location: ' + error.message);
  }
});

// ============================================
// ERROR HANDLING
// ============================================

// 404 Handler
app.use((req, res) => {
  sendResponse(res, 404, false, 'Route not found');
});

// Global Error Handler
app.use((err, req, res, next) => {
  console.error('Global error handler:', err);
  sendResponse(res, 500, false, 'Internal server error: ' + err.message);
});

// ============================================
// SERVER INITIALIZATION
// ============================================

const PORT = process.env.PORT || 5000;

// Only listen if not in serverless environment (Vercel)
if (process.env.NODE_ENV !== 'production' || !process.env.VERCEL) {
  app.listen(PORT, () => {
    console.log(`
╔════════════════════════════════════════════════╗
║  BD Enterprises Backend API                    ║
╠════════════════════════════════════════════════╣
║  Server running on port ${PORT}                  ║
║  Environment: ${process.env.NODE_ENV || 'development'}                      ║
║  CORS Origin: ${process.env.FRONTEND_URL || 'http://localhost:3000'}  ║
╚════════════════════════════════════════════════╝
    `);
  });
}

// Export for Vercel serverless deployment
module.exports = app;
