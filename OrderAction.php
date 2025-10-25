<?php
session_start();
include 'dbconnect.php';

// Ensure orders table exists (with user_id)
$createOrders = "CREATE TABLE IF NOT EXISTS orders (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
mysqli_query($conn, $createOrders);

if (!isset($_SESSION['user_id'])) {
  echo "Please login first. <a href='Login.html'>Login</a>";
  exit;
}

// Read POST (only item_id required)
$item_id = isset($_POST['item_id']) ? $_POST['item_id'] : '';
$item_id_s = mysqli_real_escape_string($conn, $item_id);

// Load item
$item = null;
$res = mysqli_query($conn, "SELECT id, name, price FROM menu_items WHERE id='".$item_id_s."' LIMIT 1");
if ($res && $row = mysqli_fetch_assoc($res)) { $item = $row; }
if (!$item) {
  echo "Item not found. <a href='MenuList.php'>Back to Menu</a>";
  exit;
}

// Load user from DB
$uid = mysqli_real_escape_string($conn, $_SESSION['user_id']);
$ures = mysqli_query($conn, "SELECT id, name, email, mobile FROM users WHERE id='".$uid."' LIMIT 1");
if (!$ures || !($urow = mysqli_fetch_assoc($ures))) {
  echo "User not found. <a href='Login.html'>Login</a>";
  exit;
}

$name_s = mysqli_real_escape_string($conn, $urow['name']);
$email_s = mysqli_real_escape_string($conn, $urow['email']);
$mobile_s = mysqli_real_escape_string($conn, (string)($urow['mobile'] ?? ''));
$item_name_s = mysqli_real_escape_string($conn, $item['name']);
$price_s = mysqli_real_escape_string($conn, (string)$item['price']);

$ins = "INSERT INTO orders (user_id, item_id, item_name, price, customer_name, email, mobile, payment_method, status) VALUES ('".$uid."', '".$item_id_s."', '".$item_name_s."', '".$price_s."', '".$name_s."', '".$email_s."', '".$mobile_s."', 'COD', 'COD-PLACED')";

if (mysqli_query($conn, $ins)) {
  // CSS/HTML-only modal success page
  echo "<!DOCTYPE html>\n";
  echo "<html lang='en'>\n<head>\n<meta charset='UTF-8'>\n<meta name='viewport' content='width=device-width, initial-scale=1.0'>\n<title>Order Placed | Coffeein</title>\n";
  echo "<style>*{box-sizing:border-box;font-family:'Poppins',sans-serif}body{margin:0;background:#fffaf3;color:#333}.overlay{position:fixed;inset:0;background:rgba(0,0,0,.4);display:flex;align-items:center;justify-content:center;padding:16px}.modal{background:#fff;border-radius:14px;max-width:520px;width:100%;padding:24px;box-shadow:0 10px 30px rgba(0,0,0,.25)}.title{font-size:1.25rem;font-weight:700;color:#3e2723;margin-bottom:8px}.text{color:#5d4037;margin-bottom:16px}.actions{display:flex;gap:10px;flex-wrap:wrap}.btn{display:inline-block;background:#795548;color:#fff;text-decoration:none;padding:10px 14px;border-radius:8px}.btn:hover{background:#5d4037}.btn.secondary{background:#fff;color:#5d4037;border:1px solid #d7ccc8}.btn.secondary:hover{background:#fff8f1}</style>\n";
  echo "</head><body>\n<div class='overlay'><div class='modal'>\n<div class='title'>Order placed (COD)!</div>\n<div class='text'>Thank you, ".htmlspecialchars($urow['name']).". We will deliver <strong>".htmlspecialchars($item['name'])."</strong> soon. Payment method: Cash on Delivery.</div>\n<div class='actions'>\n<a class='btn' href='MenuList.php'>Back to Menu</a>\n<a class='btn secondary' href='MyOrders.php'>My Orders</a>\n</div></div></div></body></html>";
} else {
  echo "Failed to place order: " . $conn->error . " <a href='MenuList.php'>Back to Menu</a>";
}

mysqli_close($conn);
?>
