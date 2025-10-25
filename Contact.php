<?php
session_start();
include 'dbconnect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact | Coffeein</title>
  <style>
    * { box-sizing: border-box; font-family: 'Poppins', sans-serif; }
    body { background:#fffaf3; color:#333; margin:0; }
    /* Navbar (same style as Home.php) */
    nav { background:#3e2723; padding:15px 30px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap }
    .logo { font-size:24px; font-weight:700; color:#fff }
    nav ul { list-style:none; display:flex; align-items:center; gap:25px }
    nav ul li a { text-decoration:none; color:#fff; font-weight:500; padding:8px 14px; border-radius:6px; transition:.3s }
    nav ul li a:hover { background:#795548 }
    .auth a { background:#795548; color:#fff; border-radius:6px; padding:8px 16px }
    .auth a:hover { background:#5d4037 }

    .container { max-width:960px; margin:32px auto; padding:0 16px; }
    h1 { color:#3e2723; }
    .card { background:#fff; border:1px solid #d7ccc8; border-radius:12px; padding:20px; box-shadow:0 8px 20px rgba(0,0,0,0.06); }
    .row { margin:8px 0; color:#5d4037 }
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
    <h1>Contact Us</h1>
    <div class="card">
      <div class="row">📍 123 Roast Street, Coffeeville</div>
      <div class="row">📞 (555) 987-6543</div>
      <div class="row">🕒 Open daily: 8:00 AM - 9:00 PM</div>
      <div class="row">✉️ hello@coffeein.com</div>
    </div>
  </div>
</body>
</html>
