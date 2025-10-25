-- --------------------------------------------------------
-- SQL Script to Create Coffeein Database and Users Table
-- --------------------------------------------------------

-- 1️⃣ Create Database
CREATE DATABASE IF NOT EXISTS coffeein_db;
USE coffeein_db;

-- 2️⃣ Create Users Table
CREATE TABLE IF NOT EXISTS users (
  id INT(11) NOT NULL AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3️⃣ Optional: Insert a Test User (for checking login later)
INSERT INTO users (name, email, password)
VALUES ('Test User', 'test@coffeein.com', '$2y$10$FjBPK/3wA97j6Rn2FheMFeQokFTtuFvK4ePjHYfT8XnSlgi84lW2a'); 
-- password = "123456"
