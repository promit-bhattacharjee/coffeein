<?php
session_start();
include 'dbconnect.php';

// Ensure menu_items exists so page works on fresh setup
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS menu_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  description TEXT,
  category ENUM('Coffee','Dessert') NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$random = [];
$res = mysqli_query($conn, "SELECT id, name, price, description, category FROM menu_items ORDER BY RAND() LIMIT 3");
if ($res) {
  while ($row = mysqli_fetch_assoc($res)) { $random[] = $row; }
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Coffeein</title>
  <style>
    * { margin:0; padding:0; box-sizing:border-box; font-family:'Poppins',sans-serif }
    body { background:#fffaf3; color:#333 }
    nav { background:#3e2723; padding:15px 30px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap }
    .logo { font-size:24px; font-weight:700; color:#fff }
    nav ul { list-style:none; display:flex; align-items:center; gap:25px }
    nav ul li a { text-decoration:none; color:#fff; font-weight:500; padding:8px 14px; border-radius:6px }
    nav ul li a:hover { background:#795548 }
    .auth a { background:#795548; color:#fff; border-radius:6px; padding:8px 16px }
    .auth a:hover { background:#5d4037 }

    .hero { background:#3e2723; background-image:linear-gradient(rgba(0,0,0,.3),rgba(0,0,0,.3)), url('https://images.unsplash.com/photo-1509042239860-f550ce710b93'); background-size:cover; background-position:center; height:70vh; display:flex; flex-direction:column; justify-content:center; align-items:center; text-align:center; color:#fff; padding:0 20px }
    .hero h1 { font-size:3rem; margin-bottom:10px }
    .hero p { font-size:1.2rem; margin-bottom:25px; max-width:500px }
    .hero a { display:inline-block; padding:12px 28px; background:#795548; color:#fff; text-decoration:none; border-radius:6px }
    .hero a:hover { background:#5d4037 }

    .section { padding:60px 10%; text-align:center }
    .section h2 { font-size:2rem; color:#3e2723; margin-bottom:20px }
    .about p { max-width:700px; margin:auto; font-size:1.1rem; color:#5d4037; line-height:1.6 }

    .favorites { background:#fff3e0 }
    .grid { display:grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap:16px; margin-top:20px }
    .card { background:#fff; border:1px solid #d7ccc8; border-radius:12px; padding:16px; box-shadow:0 8px 20px rgba(0,0,0,.06); text-align:left }
    .title { font-weight:600; color:#3e2723; margin-bottom:6px }
    .price { color:#5d4037; margin-bottom:8px }
    .desc { color:#6d4c41; font-size:.95rem }

    .contact { background:#3e2723; color:#fff }
    footer { text-align:center; background:#2e1c17; color:#fff; padding:15px 0; font-size:.9rem }
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

  <section class="hero">
    <h1>Welcome to Coffeein</h1>
    <p>Freshly brewed coffee, cozy vibes, and great conversations await you.</p>
    <a href="MenuList.php">Explore Menu</a>
  </section>

  <section class="section about">
    <h2>About Us</h2>
    <p>At Coffeein, we believe coffee is more than just a drink — it’s a ritual. We source our beans ethically and brew them with love to bring you the perfect cup every time.</p>
  </section>

  <section class="section favorites">
    <h2>Our Favourites</h2>
    <div class="grid">
      <?php if (empty($random)): ?>
        <p class="desc" style="grid-column:1/-1">No items yet. Please ask admin to add some.</p>
      <?php else: ?>
        <?php foreach ($random as $it): ?>
          <div class="card">
            <div class="title"><?php echo htmlspecialchars($it['name']); ?></div>
            <div class="price">$<?php echo htmlspecialchars(number_format((float)$it['price'], 2)); ?></div>
            <div class="desc"><?php echo nl2br(htmlspecialchars($it['description'])); ?></div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>

  <section id="contact" class="section contact">
    <h2>Visit Us</h2>
    <p>📍 123 Roast Street, Coffeeville</p>
    <p>📞 (555) 987-6543</p>
    <p>🕒 Open daily: 8:00 AM - 9:00 PM</p>
  </section>

  <footer>© 2025 Coffeein | Crafted with ❤️</footer>
</body>
</html>
