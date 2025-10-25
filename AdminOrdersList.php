<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header('Location: AdminLogin.html');
  exit;
}
include 'dbconnect.php';

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

$orders = [];
$res = mysqli_query($conn, "SELECT id, item_name, price, customer_name, email, mobile, status, created_at FROM orders ORDER BY created_at DESC");
if ($res) { while ($row = mysqli_fetch_assoc($res)) { $orders[] = $row; } }
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Orders | Coffeein Admin</title>
  <style>
    *{box-sizing:border-box;font-family:'Poppins',sans-serif}
    body{margin:0;background:#fffaf3;color:#333}
    nav{background:#3e2723;color:#fff;padding:16px 24px;text-align:center;font-weight:700}
    .container{max-width:1100px;margin:24px auto;padding:0 16px}
    .links{margin:12px 0}
    .links a{color:#3e2723;text-decoration:none;margin-right:10px}
    .links a:hover{text-decoration:underline}
    table{width:100%;border-collapse:collapse;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 6px 16px rgba(0,0,0,.06)}
    th,td{padding:12px 14px;border-bottom:1px solid #eee;text-align:left}
    th{background:#fff3e0;color:#3e2723}
    .badge{display:inline-block;padding:2px 8px;border-radius:999px;border:1px solid #d7ccc8;background:#fff8f1;color:#5d4037;font-size:.85rem}
  </style>
</head>
<body>
  <nav>☕ Coffeein Admin Orders</nav>
  <div class="container">
    <div class="links">
      <a href="AdminDashboard.php">Dashboard</a>
      <a href="AdminMenuList.php">Manage Menu</a>
      <a href="AdminMenu.html">Add Item</a>
    </div>

    <table>
      <thead><tr><th>ID</th><th>Item</th><th>Price</th><th>Customer</th><th>Email</th><th>Mobile</th><th>Status</th><th>Time</th></tr></thead>
      <tbody>
        <?php if (empty($orders)) { echo '<tr><td colspan="8">No orders yet.</td></tr>'; } ?>
        <?php foreach ($orders as $o): ?>
          <tr>
            <td><?php echo htmlspecialchars($o['id']); ?></td>
            <td><?php echo htmlspecialchars($o['item_name']); ?></td>
            <td>$<?php echo htmlspecialchars(number_format((float)$o['price'],2)); ?></td>
            <td><?php echo htmlspecialchars($o['customer_name']); ?></td>
            <td><?php echo htmlspecialchars($o['email']); ?></td>
            <td><?php echo htmlspecialchars($o['mobile']); ?></td>
            <td><span class="badge"><?php echo htmlspecialchars($o['status']); ?></span></td>
            <td><?php echo htmlspecialchars($o['created_at']); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
