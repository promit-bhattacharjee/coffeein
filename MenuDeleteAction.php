<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header('Location: AdminLogin.html');
  exit;
}
include 'dbconnect.php';
$id = isset($_POST['id']) ? $_POST['id'] : '';
$id_safe = mysqli_real_escape_string($conn, $id);
$sql = "DELETE FROM menu_items WHERE id='".$id_safe."' LIMIT 1";
if (mysqli_query($conn, $sql)) {
  header('Location: AdminMenuList.php');
  exit;
} else {
  echo "Error deleting item: " . $conn->error . " <a href='AdminMenuList.php'>Back</a>";
}
mysqli_close($conn);
?>
