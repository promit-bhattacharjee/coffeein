<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header('Location: AdminLogin.html');
  exit;
}
include 'dbconnect.php';
$id = isset($_GET['id']) ? $_GET['id'] : '';
$id_safe = mysqli_real_escape_string($conn, $id);
$item = null;
$res = mysqli_query($conn, "SELECT id, name, price, description, category FROM menu_items WHERE id='".$id_safe."' LIMIT 1");
if ($res && $row = mysqli_fetch_assoc($res)) { $item = $row; }
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit Menu Item | Coffeein</title>
  <style>
    * { box-sizing: border-box; font-family: 'Poppins', sans-serif; }
    body { background:#fffaf3; color:#333; margin:0; }
    nav { background:#3e2723; color:#fff; padding:16px 24px; text-align:center; font-weight:700; }
    .container { max-width:720px; margin:32px auto; background:#fff; padding:24px; border-radius:12px; box-shadow:0 8px 20px rgba(0,0,0,0.08); }
    h1 { margin:0 0 8px; color:#3e2723; }
    .grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
    .full { grid-column:1 / -1; }
    label { display:block; font-size:0.9rem; color:#5d4037; margin-bottom:6px; }
    input, select, textarea { width:100%; padding:10px 12px; border:1px solid #d7ccc8; border-radius:8px; background:#fff8f1; font-size:1rem; }
    textarea { min-height:100px; resize:vertical; }
    button { background:#795548; color:#fff; border:none; padding:12px 16px; border-radius:8px; cursor:pointer; font-size:1rem; }
    button:hover { background:#5d4037; }
    .links { margin-top:16px; font-size:0.95rem; }
    .links a { color:#3e2723; text-decoration:none; }
    .links a:hover { text-decoration:underline; }
    @media (max-width:640px){ .grid { grid-template-columns:1fr; }}
  </style>
</head>
<body>
  <nav>☕ Coffeein Admin</nav>
  <div class="container">
    <h1>Edit Menu Item</h1>
    <?php if (!$item): ?>
      <p>Item not found. <a href="AdminMenuList.php">Back to list</a></p>
    <?php else: ?>
    <form action="MenuUpdateAction.php" method="post">
      <input type="hidden" name="id" value="<?php echo htmlspecialchars($item['id']); ?>" />
      <div class="grid">
        <div>
          <label for="name">Name</label>
          <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($item['name']); ?>" />
        </div>
        <div>
          <label for="price">Price</label>
          <input type="number" id="price" name="price" step="0.01" value="<?php echo htmlspecialchars($item['price']); ?>" />
        </div>
        <div class="full">
          <label for="category">Category</label>
          <select id="category" name="category">
            <option value="Coffee" <?php echo $item['category']==='Coffee'?'selected':''; ?>>Coffee</option>
            <option value="Dessert" <?php echo $item['category']==='Dessert'?'selected':''; ?>>Dessert</option>
          </select>
        </div>
        <div class="full">
          <label for="description">Description</label>
          <textarea id="description" name="description"><?php echo htmlspecialchars($item['description']); ?></textarea>
        </div>
      </div>
      <br />
      <button type="submit">Update Item</button>
    </form>
    <div class="links"><a href="AdminMenuList.php">Back to list</a></div>
    <?php endif; ?>
  </div>
</body>
</html>
