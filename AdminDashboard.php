<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header('Location: AdminLogin.html');
  exit;
}
include 'dbconnect.php';

// Ensure tables exist
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS menu_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  description TEXT,
  category ENUM('Coffee','Dessert') NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

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

// Counts
$cItems = 0; $cOrders = 0; $cPending = 0;
if ($res = mysqli_query($conn, "SELECT COUNT(*) c FROM menu_items")) { if ($row = mysqli_fetch_assoc($res)) { $cItems = (int)$row['c']; }}
if ($res = mysqli_query($conn, "SELECT COUNT(*) c FROM orders")) { if ($row = mysqli_fetch_assoc($res)) { $cOrders = (int)$row['c']; }}
if ($res = mysqli_query($conn, "SELECT COUNT(*) c FROM orders WHERE status='COD-PLACED'")) { if ($row = mysqli_fetch_assoc($res)) { $cPending = (int)$row['c']; }}

// Recent orders
$orders = [];
if ($res = mysqli_query($conn, "SELECT id, item_name, price, customer_name, mobile, status, created_at FROM orders ORDER BY created_at DESC LIMIT 10")) {
  while ($row = mysqli_fetch_assoc($res)) { $orders[] = $row; }
}

// Recent items
$items = [];
if ($res = mysqli_query($conn, "SELECT id, name, category, price, created_at FROM menu_items ORDER BY created_at DESC LIMIT 5")) {
  while ($row = mysqli_fetch_assoc($res)) { $items[] = $row; }
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard | Coffeein</title>
  <style>
    *{box-sizing:border-box;font-family:'Poppins',sans-serif}
    body{margin:0;background:#fffaf3;color:#333}
    nav{background:#3e2723;color:#fff;padding:16px 24px;text-align:center;font-weight:700}
    .container{max-width:1100px;margin:24px auto;padding:0 16px}
    .cards{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:16px;margin-bottom:20px}
    .card{background:#fff;padding:16px;border-radius:10px;border:1px solid #d7ccc8;box-shadow:0 6px 16px rgba(0,0,0,.06)}
    .card .title{color:#3e2723;font-weight:600;margin-bottom:6px}
    .metric{font-size:1.8rem;color:#5d4037}
    h2{color:#3e2723;margin-top:24px}
    table{width:100%;border-collapse:collapse;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 6px 16px rgba(0,0,0,.06)}
    th,td{padding:12px 14px;border-bottom:1px solid #eee;text-align:left}
    th{background:#fff3e0;color:#3e2723}
    .links{margin:12px 0}
    .links a{color:#3e2723;text-decoration:none;margin-right:10px}
    .links a:hover{text-decoration:underline}
    .badge{display:inline-block;padding:2px 8px;border-radius:999px;border:1px solid #d7ccc8;background:#fff8f1;color:#5d4037;font-size:.85rem}
  </style>
</head>
<body>
  <nav>☕ Coffeein Admin Dashboard</nav>
  <div class="container">
    <div class="links">
      <a href="AdminDashboard.php">Dashboard</a>
      <a href="AdminMenuList.php">Manage Menu</a>
      <a href="AdminMenu.html">Add Item</a>
      <a href="AdminOrdersList.php">Orders</a>
      <a href="Home.php">View Site</a>
      <a href="AdminLogout.php">Logout</a>
    </div>

    <div class="cards">
      <div class="card"><div class="title">Total Menu Items</div><div class="metric"><?php echo (int)$cItems; ?></div></div>
      <div class="card"><div class="title">Total Orders</div><div class="metric"><?php echo (int)$cOrders; ?></div></div>
      <div class="card"><div class="title">Pending (COD)</div><div class="metric"><?php echo (int)$cPending; ?></div></div>
    </div>

    <h2>Recent Orders</h2>
    <table>
      <thead><tr><th>ID</th><th>Item</th><th>Price</th><th>Customer</th><th>Mobile</th><th>Status</th><th>Time</th></tr></thead>
      <tbody>
        <?php if (empty($orders)) { echo '<tr><td colspan="7">No orders yet.</td></tr>'; } ?>
        <?php foreach ($orders as $o): ?>
          <tr>
            <td><?php echo htmlspecialchars($o['id']); ?></td>
            <td><?php echo htmlspecialchars($o['item_name']); ?></td>
            <td>$<?php echo htmlspecialchars(number_format((float)$o['price'],2)); ?></td>
            <td><?php echo htmlspecialchars($o['customer_name']); ?></td>
            <td><?php echo htmlspecialchars($o['mobile']); ?></td>
            <td><span class="badge"><?php echo htmlspecialchars($o['status']); ?></span></td>
            <td><?php echo htmlspecialchars($o['created_at']); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <h2>Newest Menu Items</h2>
    <table>
      <thead><tr><th>ID</th><th>Name</th><th>Category</th><th>Price</th><th>Time</th></tr></thead>
      <tbody>
        <?php if (empty($items)) { echo '<tr><td colspan="5">No menu items yet.</td></tr>'; } ?>
        <?php foreach ($items as $it): ?>
          <tr>
            <td><?php echo htmlspecialchars($it['id']); ?></td>
            <td><?php echo htmlspecialchars($it['name']); ?></td>
            <td><?php echo htmlspecialchars($it['category']); ?></td>
            <td>$<?php echo htmlspecialchars(number_format((float)$it['price'],2)); ?></td>
            <td><?php echo htmlspecialchars($it['created_at']); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
