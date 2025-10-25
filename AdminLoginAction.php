<?php
session_start();
include 'dbconnect.php';

// Ensure admins table exists
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$email = isset($_POST['email']) ? $_POST['email'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

$email_safe = mysqli_real_escape_string($conn, $email);
$sql = "SELECT id, name, email, password FROM admins WHERE email='".$email_safe."' LIMIT 1";
$res = mysqli_query($conn, $sql);
if ($res && $row = mysqli_fetch_assoc($res)) {
  // Plain text password compare
  if ($password === $row['password']) {
    $_SESSION['admin_id'] = $row['id'];
    $_SESSION['admin_name'] = $row['name'];
    header('Location: AdminDashboard.php');
    exit;
  } else {
    echo "Wrong password. <a href='AdminLogin.html'>Back</a>";
  }
} else {
  echo "Admin not found. <a href='AdminLogin.html'>Back</a>";
}
mysqli_close($conn);
?>
