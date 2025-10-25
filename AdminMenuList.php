<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header('Location: AdminLogin.html');
  exit;
}
include 'dbconnect.php';

// Ensure table exists
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS menu_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  description TEXT,
  category ENUM('Coffee','Dessert') NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$result = mysqli_query($conn, "SELECT id, name, price, category FROM menu_items ORDER BY created_at DESC");
$items = [];
if ($result) { while ($row = mysqli_fetch_assoc($result)) { $items[] = $row; } }
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Manage Menu | Coffeein Admin</title>
  <style>
    *{box-sizing:border-box;font-family:'Poppins',sans-serif}
    body{margin:0;background:#fffaf3;color:#333}
    nav{background:#3e2723;color:#fff;padding:16px 24px;text-align:center;font-weight:700}
    .container{max-width:960px;margin:24px auto;padding:0 16px}
    table{width:100%;border-collapse:collapse;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 8px 20px rgba(0,0,0,.06)}
    th,td{padding:12px 14px;border-bottom:1px solid #eee;text-align:left}
    th{background:#fff3e0;color:#3e2723}
    .actions a,.actions form button{display:inline-block;margin-right:8px;padding:6px 10px;border-radius:6px;text-decoration:none;font-size:.95rem}
    .btn{background:#795548;color:#fff}
    .btn:hover{background:#5d4037}
    .btn-outline{background:#fff;border:1px solid #d7ccc8;color:#5d4037}
    .btn-outline:hover{background:#fff8f1}
    .top{display:flex;justify-content:space-between;align-items:center;margin-bottom:12px}
    .links a{color:#3e2723;text-decoration:none}
    .links a:hover{text-decoration:underline}
  </style>
</head>
<body>
  <nav>☕ Coffeein Admin</nav>
  <div class="container">
    <div class="top">
      <h1>Manage Menu</h1>
      <div class="links"><a href="AdminDashboard.php">Dashboard</a> | <a href="AdminOrdersList.php">Orders</a> | <a href="AdminMenu.html">Add New Item</a> | <a href="MenuList.php">View Public Menu</a></div>
    </div>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Category</th>
          <th>Price</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($items)) { echo '<tr><td colspan="5">No items yet.</td></tr>'; } ?>
        <?php foreach ($items as $it): ?>
          <tr>
            <td><?php echo htmlspecialchars($it['id']); ?></td>
            <td><?php echo htmlspecialchars($it['name']); ?></td>
            <td><?php echo htmlspecialchars($it['category']); ?></td>
            <td>$<?php echo htmlspecialchars(number_format((float)$it['price'], 2)); ?></td>
            <td class="actions">
              <a class="btn" href="AdminMenuEdit.php?id=<?php echo $it['id']; ?>">Edit</a>
              <form action="MenuDeleteAction.php" method="post" style="display:inline">
                <input type="hidden" name="id" value="<?php echo $it['id']; ?>" />
                <button type="submit" class="btn-outline">Delete</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
