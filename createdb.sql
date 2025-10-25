-- --------------------------------------------------------
-- Coffeein Database Setup (users, admins, menu_items, orders)
-- --------------------------------------------------------

-- Create Database
CREATE DATABASE IF NOT EXISTS coffeein_db;
USE coffeein_db;

-- Users table
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  mobile VARCHAR(50),
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Admins table
CREATE TABLE IF NOT EXISTS admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  mobile VARCHAR(50),
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Menu Items table
CREATE TABLE IF NOT EXISTS menu_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  description TEXT,
  category ENUM('Coffee','Dessert') NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Orders table (COD)
CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  item_id INT NOT NULL,
  item_name VARCHAR(150) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  customer_name VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL,
  mobile VARCHAR(50) NOT NULL,
  payment_method VARCHAR(20) NOT NULL DEFAULT 'COD',
  status VARCHAR(50) NOT NULL DEFAULT 'COD-PLACED',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample data
INSERT INTO users (name, email, mobile, password)
VALUES ('Test User', 'test@coffeein.com', '01700000000', '123456');

INSERT INTO admins (name, email, password)
VALUES ('Admin', 'admin@coffeein.com', 'admin123');

INSERT INTO menu_items (name, price, description, category) VALUES
('Cappuccino', 3.50, 'Perfect balance of coffee, milk, and foam.', 'Coffee'),
('Creamy Latte', 3.80, 'Rich espresso blended with steamed milk.', 'Coffee'),
('Cold Brew', 3.20, 'Slow-brewed for a smooth, refreshing flavor.', 'Coffee'),
('Cheesecake', 4.50, 'Classic creamy cheesecake slice.', 'Dessert');
