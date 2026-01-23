/**
 * Database Setup and Connection Pool
 * Manages MySQL connection using mysql2/promise for better async/await support
 */

const mysql = require('mysql2/promise');
require('dotenv').config();

// Create connection pool
const pool = mysql.createPool({
  host: process.env.DB_HOST || 'localhost',
  user: process.env.DB_USER || 'root',
  password: process.env.DB_PASSWORD || '',
  database: process.env.DB_NAME || 'bd_enterprises',
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0,
  enableKeepAlive: true,
  keepAliveInitialDelayMs: 0
});

// Test connection on startup
pool.getConnection()
  .then(connection => {
    console.log('✅ Database connection successful');
    connection.release();
  })
  .catch(error => {
    console.error('❌ Database connection failed:', error.message);
    if (process.env.NODE_ENV !== 'production') {
      console.error('Please ensure MySQL is running and credentials in .env are correct');
    }
  });

// Export pool for use in other files
module.exports = pool;
