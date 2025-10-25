<?php
session_start();
include 'dbconnect.php';

// Ensure table exists so page doesn't fail if empty setup
$createSql = "CREATE TABLE IF NOT EXISTS menu_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  description TEXT,
  category ENUM('Coffee','Dessert') NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
mysqli_query($conn, $createSql);

$items = [];
$result = mysqli_query($conn, "SELECT id, name, price, description, category FROM menu_items ORDER BY category, name");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $items[] = $row;
    }
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Menu | Coffeein</title>
  <style>
    * { box-sizing: border-box; font-family: 'Poppins', sans-serif; }
    body { background:#fffaf3; color:#333; margin:0; }
    /* Nav copied from Home.php */
    nav { background:#3e2723; padding:15px 30px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap }
    .logo { font-size:24px; font-weight:700; color:#fff }
    nav ul { list-style:none; display:flex; align-items:center; gap:25px }
    nav ul li a { text-decoration:none; color:#fff; font-weight:500; padding:8px 14px; border-radius:6px; transition:.3s }
    nav ul li a:hover { background:#795548 }
    .auth a { background:#795548; color:#fff; border-radius:6px; padding:8px 16px }
    .auth a:hover { background:#5d4037 }

    .container { max-width:960px; margin:32px auto; padding:0 16px; }
    h1 { color:#3e2723; }
    .section { margin-top:24px; }
    .grid { display:grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap:16px; }
    .card { background:#fff; border:1px solid #d7ccc8; border-radius:12px; padding:16px; box-shadow:0 8px 20px rgba(0,0,0,0.06); }
    .title { font-weight:600; color:#3e2723; margin-bottom:6px; }
    .price { color:#5d4037; margin-bottom:8px; }
    .desc { color:#6d4c41; font-size:0.95rem; }
    .chips { margin:0 0 12px; }
    .chip { display:inline-block; background:#fff8f1; border:1px solid #d7ccc8; color:#5d4037; border-radius:999px; padding:4px 10px; font-size:0.85rem; }
    .links { margin:16px 0; }
    .links a { color:#3e2723; text-decoration:none; }
    .links a:hover { text-decoration:underline; }
  </style>
</head>
<body>
  <nav>
    <div class="logo">☕ Coffeein</div>
    <ul>
      <li><a href="Home.php">Home</a></li>
      <li><a href="MenuList.php">Menu</a></li>
      <?php if (isset($_SESSION['user_id'])): ?>
        <li><a href="MyOrders.php">My Orders</a></li>
      <?php endif; ?>
      <li><a href="Contact.php">Contact</a></li>
      <?php if (!isset($_SESSION['user_id'])): ?>
        <li class="auth"><a href="Login.html">Login</a></li>
        <li class="auth"><a href="Signup.html">Sign Up</a></li>
      <?php else: ?>
        <li class="auth"><a href="UserLogout.php">Logout</a></li>
      <?php endif; ?>
    </ul>
  </nav>
  <div class="container">
    <h1>Our Menu</h1>

    <?php
    $categories = ['Coffee', 'Dessert'];
    foreach ($categories as $cat) {
        echo '<div class="section">';
        echo '<h2>' . htmlspecialchars($cat) . '</h2>';
        echo '<div class="grid">';
        $hasAny = false;
        foreach ($items as $it) {
            if ($it['category'] !== $cat) continue;
            $hasAny = true;
            echo '<div class="card">';
            echo '<div class="chips"><span class="chip">' . htmlspecialchars($it['category']) . '</span></div>';
            echo '<div class="title">' . htmlspecialchars($it['name']) . '</div>';
            echo '<div class="price">$' . htmlspecialchars(number_format((float)$it['price'], 2)) . '</div>';
            echo '<div class="desc">' . nl2br(htmlspecialchars($it['description'])) . '</div>';
            echo '<div style="margin-top:10px">';
            if (isset($_SESSION['user_id'])) {
              echo '<a href="OrderConfirm.php?item_id=' . htmlspecialchars($it['id']) . '" style="display:inline-block;background:#795548;color:#fff;text-decoration:none;padding:8px 12px;border-radius:8px">Order (COD)</a>';
            } else {
              echo '<a href="Login.html" style="display:inline-block;background:#795548;color:#fff;text-decoration:none;padding:8px 12px;border-radius:8px">Login to order</a>';
            }
            echo '</div>';
            echo '</div>';
        }
        if (!$hasAny) {
            echo '<p class="desc">No items yet in this category.</p>';
        }
        echo '</div></div>';
    }
    ?>
  </div>
</body>
</html>
