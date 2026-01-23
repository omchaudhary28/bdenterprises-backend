-- BD Enterprises Database Setup

-- Create Database
CREATE DATABASE IF NOT EXISTS bd_enterprises;
USE bd_enterprises;

-- Table for Contact Form Submissions
CREATE TABLE IF NOT EXISTS contact_submissions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL,
  phone VARCHAR(20),
  company_name VARCHAR(150),
  service_type VARCHAR(100),
  message LONGTEXT NOT NULL,
  preferred_contact_method ENUM('email', 'phone', 'whatsapp') DEFAULT 'email',
  status ENUM('new', 'in_progress', 'resolved', 'closed') DEFAULT 'new',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_email (email),
  INDEX idx_status (status),
  INDEX idx_created_at (created_at)
);

-- Table for Contact Information (Company Details)
CREATE TABLE IF NOT EXISTS company_contact_info (
  id INT AUTO_INCREMENT PRIMARY KEY,
  contact_type ENUM('phone', 'email', 'address', 'whatsapp') NOT NULL UNIQUE,
  value VARCHAR(255) NOT NULL,
  label VARCHAR(100),
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table for Social Media Links
CREATE TABLE IF NOT EXISTS social_media_links (
  id INT AUTO_INCREMENT PRIMARY KEY,
  platform VARCHAR(50) NOT NULL UNIQUE,
  url VARCHAR(255) NOT NULL,
  icon_name VARCHAR(50),
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table for Location/Map Data
CREATE TABLE IF NOT EXISTS company_locations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  latitude DECIMAL(10, 8) NOT NULL,
  longitude DECIMAL(11, 8) NOT NULL,
  address VARCHAR(255),
  city VARCHAR(100),
  state VARCHAR(100),
  zip_code VARCHAR(20),
  phone VARCHAR(20),
  email VARCHAR(150),
  is_main_office BOOLEAN DEFAULT FALSE,
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert Sample Company Contact Information
INSERT INTO company_contact_info (contact_type, value, label) VALUES
('phone', '7499953708', 'Main Phone'),
('email', 'omchaudhary2111@gmail.com', 'Main Email'),
('whatsapp', '7499953708', 'WhatsApp'),
('address', '123 Safety Avenue, Fire District, FD 12345', 'Main Office');

-- Insert Sample Social Media Links
INSERT INTO social_media_links (platform, url, icon_name) VALUES
('facebook', 'https://facebook.com/bdenterprises', 'facebook'),
('twitter', 'https://twitter.com/bdenterprises', 'twitter'),
('linkedin', 'https://linkedin.com/company/bdenterprises', 'linkedin'),
('instagram', 'https://instagram.com/bdenterprises', 'instagram');

-- Insert Sample Location (Main Office)
INSERT INTO company_locations (name, latitude, longitude, address, city, state, zip_code, phone, email, is_main_office, is_active) VALUES
('BD Enterprises - Main Office', 40.7128, -74.0060, '123 Safety Avenue, Fire District', 'New York', 'NY', '10001', '7499953708', 'omchaudhary2111@gmail.com', TRUE, TRUE);
