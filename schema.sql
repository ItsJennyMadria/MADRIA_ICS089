-- Database structure setup for PRACTICE
CREATE DATABASE IF NOT EXISTS practice;
USE practice;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Seed initial test user
INSERT INTO users (email, password) 
VALUES ('test@example.com', '123456')
ON DUPLICATE KEY UPDATE email=email;

-- Add role column with 'user' as default
ALTER TABLE users 
ADD COLUMN role VARCHAR(50) NOT NULL DEFAULT 'user' AFTER password;