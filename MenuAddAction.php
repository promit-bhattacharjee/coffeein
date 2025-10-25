<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: AdminLogin.html');
    exit;
}
include 'dbconnect.php';

// Ensure table exists (id, name, price, description, category)
$createSql = "CREATE TABLE IF NOT EXISTS menu_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  description TEXT,
  category ENUM('Coffee','Dessert') NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
mysqli_query($conn, $createSql);

// Get POST values (no validation as requested)
$name = isset($_POST['name']) ? $_POST['name'] : '';
$price = isset($_POST['price']) ? $_POST['price'] : '0';
$description = isset($_POST['description']) ? $_POST['description'] : '';
$category = isset($_POST['category']) ? $_POST['category'] : 'Coffee';

// Escape and insert
$name_safe = mysqli_real_escape_string($conn, $name);
$price_safe = mysqli_real_escape_string($conn, $price);
$description_safe = mysqli_real_escape_string($conn, $description);
$category_safe = mysqli_real_escape_string($conn, $category);

$sql = "INSERT INTO menu_items (name, price, description, category) VALUES ('" . $name_safe . "', '" . $price_safe . "', '" . $description_safe . "', '" . $category_safe . "')";

if (mysqli_query($conn, $sql)) {
    // Render a simple page with CSS-only modal
    echo "<!DOCTYPE html>\n";
    echo "<html lang='en'>\n";
    echo "<head>\n";
    echo "  <meta charset='UTF-8'>\n";
    echo "  <meta name='viewport' content='width=device-width, initial-scale=1.0'>\n";
    echo "  <title>Item Added | Coffeein</title>\n";
    echo "  <style>\n";
    echo "    *{box-sizing:border-box;font-family:'Poppins',sans-serif;}\n";
    echo "    body{margin:0;background:#fffaf3;color:#333;}\n";
    echo "    .overlay{position:fixed;inset:0;background:rgba(0,0,0,0.4);display:flex;align-items:center;justify-content:center;padding:16px;}\n";
    echo "    .modal{background:#fff;border-radius:14px;max-width:520px;width:100%;padding:24px;box-shadow:0 10px 30px rgba(0,0,0,0.25);}\n";
    echo "    .title{font-size:1.25rem;font-weight:700;color:#3e2723;margin-bottom:8px;}\n";
    echo "    .text{color:#5d4037;margin-bottom:16px;}\n";
    echo "    .actions{display:flex;gap:10px;flex-wrap:wrap;}\n";
    echo "    .btn{display:inline-block;background:#795548;color:#fff;text-decoration:none;padding:10px 14px;border-radius:8px;}\n";
    echo "    .btn:hover{background:#5d4037;}\n";
    echo "    .btn.secondary{background:#fff;color:#5d4037;border:1px solid #d7ccc8;}\n";
    echo "    .btn.secondary:hover{background:#fff8f1;}\n";
    echo "  </style>\n";
    echo "</head>\n";
    echo "<body>\n";
    echo "  <div class='overlay'>\n";
    echo "    <div class='modal'>\n";
    echo "      <div class='title'>Item added!</div>\n";
    echo "      <div class='text'>Your menu item was created successfully.</div>\n";
    echo "      <div class='actions'>\n";
    echo "        <a class='btn' href='AdminMenu.html'>Add another</a>\n";
    echo "        <a class='btn secondary' href='MenuList.php'>View menu</a>\n";
    echo "      </div>\n";
    echo "    </div>\n";
    echo "  </div>\n";
    echo "</body>\n";
    echo "</html>\n";
} else {
    echo "Error adding item: " . $conn->error . " <a href='AdminMenu.html'>Go back</a>";
}

mysqli_close($conn);
?>
