<?php
session_start();
include 'dbconnect.php';

if (!isset($_SESSION['user_id'])) {
  echo "Please login to view your orders. <a href='Login.html'>Login</a>";
  exit;
}

// Ensure orders table exists
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS orders (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Backfill schema if existing table lacks user_id
$colCheck = mysqli_query($conn, "SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'orders' AND COLUMN_NAME = 'user_id'");
if ($colCheck && mysqli_num_rows($colCheck) === 0) {
  @mysqli_query($conn, "ALTER TABLE orders ADD COLUMN user_id INT NULL");
}

$uid = mysqli_real_escape_string($conn, $_SESSION['user_id']);
$orders = [];
$res = mysqli_query($conn, "SELECT id, item_name, price, status, created_at FROM orders WHERE user_id='".$uid."' ORDER BY created_at DESC");
if ($res) { while ($row = mysqli_fetch_assoc($res)) { $orders[] = $row; } }
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>My Orders | Coffeein</title>
  <style>
    * { box-sizing: border-box; font-family: 'Poppins', sans-serif; }
    body { background:#fffaf3; color:#333; margin:0; }
    nav { background:#3e2723; padding:15px 30px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap }
    .logo { font-size:24px; font-weight:700; color:#fff }
    nav ul { list-style:none; display:flex; align-items:center; gap:25px }
    nav ul li a { text-decoration:none; color:#fff; font-weight:500; padding:8px 14px; border-radius:6px }
    nav ul li a:hover { background:#795548 }
    .auth a { background:#795548; color:#fff; border-radius:6px; padding:8px 16px }
    .auth a:hover { background:#5d4037 }

    .container { max-width:960px; margin:32px auto; padding:0 16px; }
    h1 { color:#3e2723; }
    table{width:100%;border-collapse:collapse;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 6px 16px rgba(0,0,0,.06)}
    th,td{padding:12px 14px;border-bottom:1px solid #eee;text-align:left}
    th{background:#fff3e0;color:#3e2723}
    .badge{display:inline-block;padding:2px 8px;border-radius:999px;border:1px solid #d7ccc8;background:#fff8f1;color:#5d4037;font-size:.85rem}
  </style>
</head>
<body>
  <nav>
    <div class="logo">☕ Coffeein</div>
    <ul>
      <li><a href="Home.php">Home</a></li>
      <li><a href="MenuList.php">Menu</a></li>
      <li><a href="MyOrders.php">My Orders</a></li>
      <li><a href="Contact.php">Contact</a></li>
      <li class="auth"><a href="UserLogout.php">Logout</a></li>
    </ul>
  </nav>
  <div class="container">
    <h1>My Orders</h1>
    <table>
      <thead><tr><th>ID</th><th>Item</th><th>Price</th><th>Status</th><th>Time</th></tr></thead>
      <tbody>
        <?php if (empty($orders)) { echo '<tr><td colspan="5">You have no orders yet.</td></tr>'; } ?>
        <?php foreach ($orders as $o): ?>
          <tr>
            <td><?php echo htmlspecialchars($o['id']); ?></td>
            <td><?php echo htmlspecialchars($o['item_name']); ?></td>
            <td>$<?php echo htmlspecialchars(number_format((float)$o['price'],2)); ?></td>
            <td><span class="badge"><?php echo htmlspecialchars($o['status']); ?></span></td>
            <td><?php echo htmlspecialchars($o['created_at']); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
