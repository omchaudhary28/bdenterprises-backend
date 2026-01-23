/**
 * Vercel Serverless Entry Point
 * Re-exports the Express app from server.js
 * This file is required by Vercel to properly route all requests
 */

const app = require('../server');

module.exports = app;
