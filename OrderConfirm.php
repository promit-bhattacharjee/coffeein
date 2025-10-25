<?php
session_start();
include 'dbconnect.php';

$item_id = isset($_GET['item_id']) ? $_GET['item_id'] : '';
$item_id_s = mysqli_real_escape_string($conn, $item_id);
$item = null;
$res = mysqli_query($conn, "SELECT id, name, price, description, category FROM menu_items WHERE id='".$item_id_s."' LIMIT 1");
if ($res && $row = mysqli_fetch_assoc($res)) { $item = $row; }

$logged_in = isset($_SESSION['user_id']);
$user = null;
if ($logged_in) {
  $uid = mysqli_real_escape_string($conn, $_SESSION['user_id']);
  $ures = mysqli_query($conn, "SELECT id, name, email, mobile FROM users WHERE id='".$uid."' LIMIT 1");
  if ($ures && $urow = mysqli_fetch_assoc($ures)) { $user = $urow; }
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Confirm Order | Coffeein</title>
  <style>
    *{box-sizing:border-box;font-family:'Poppins',sans-serif}
    body{margin:0;background:#fffaf3;color:#333}
    .overlay{position:fixed;inset:0;background:rgba(0,0,0,.4);display:flex;align-items:center;justify-content:center;padding:16px}
    .modal{background:#fff;border-radius:14px;max-width:560px;width:100%;padding:24px;box-shadow:0 10px 30px rgba(0,0,0,.25)}
    .title{font-size:1.25rem;font-weight:700;color:#3e2723;margin-bottom:8px}
    .text{color:#5d4037;margin-bottom:16px}
    .row{display:flex;gap:12px;flex-wrap:wrap;margin:8px 0}
    .chip{display:inline-block;background:#fff8f1;border:1px solid #d7ccc8;color:#5d4037;border-radius:999px;padding:4px 10px;font-size:.85rem}
    .actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:12px}
    .btn{display:inline-block;background:#795548;color:#fff;text-decoration:none;padding:10px 14px;border-radius:8px}
    .btn:hover{background:#5d4037}
    .btn.secondary{background:#fff;color:#5d4037;border:1px solid #d7ccc8}
    .btn.secondary:hover{background:#fff8f1}
    .muted{color:#6d4c41}
  </style>
</head>
<body>
  <div class="overlay">
    <div class="modal">
      <?php if (!$item): ?>
        <div class="title">Item not found</div>
        <div class="actions"><a class="btn secondary" href="MenuList.php">Back to Menu</a></div>
      <?php else: ?>
        <div class="title">Confirm your order</div>
        <div class="text">You're about to place a Cash on Delivery order.</div>
        <div class="row"><span class="chip"><?php echo htmlspecialchars($item['category']); ?></span></div>
        <div class="row"><strong><?php echo htmlspecialchars($item['name']); ?></strong> · $<?php echo htmlspecialchars(number_format((float)$item['price'], 2)); ?></div>
        <?php if (!$logged_in || !$user): ?>
          <p class="muted">Please login to continue.</p>
          <div class="actions">
            <a class="btn" href="Login.html">Login</a>
            <a class="btn secondary" href="MenuList.php">Cancel</a>
          </div>
        <?php else: ?>
          <div class="text">
            Ordering as: <strong><?php echo htmlspecialchars($user['name']); ?></strong><br/>
            Email: <?php echo htmlspecialchars($user['email']); ?><br/>
            Mobile: <?php echo htmlspecialchars($user['mobile'] ?? ''); ?>
          </div>
          <form action="OrderPlaceAction.php" method="post" class="actions">
            <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($item['id']); ?>" />
            <button type="submit" class="btn">Confirm Order (COD)</button>
            <a class="btn secondary" href="MenuList.php">Cancel</a>
          </form>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
