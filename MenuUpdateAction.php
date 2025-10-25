<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header('Location: AdminLogin.html');
  exit;
}
include 'dbconnect.php';
$id = isset($_POST['id']) ? $_POST['id'] : '';
$name = isset($_POST['name']) ? $_POST['name'] : '';
$price = isset($_POST['price']) ? $_POST['price'] : '0';
$category = isset($_POST['category']) ? $_POST['category'] : 'Coffee';
$description = isset($_POST['description']) ? $_POST['description'] : '';

$id_safe = mysqli_real_escape_string($conn, $id);
$name_safe = mysqli_real_escape_string($conn, $name);
$price_safe = mysqli_real_escape_string($conn, $price);
$category_safe = mysqli_real_escape_string($conn, $category);
$description_safe = mysqli_real_escape_string($conn, $description);

$sql = "UPDATE menu_items SET name='".$name_safe."', price='".$price_safe."', category='".$category_safe."', description='".$description_safe."' WHERE id='".$id_safe."' LIMIT 1";

if (mysqli_query($conn, $sql)) {
  header('Location: AdminMenuList.php');
  exit;
} else {
  echo "Error updating item: " . $conn->error . " <a href='AdminMenuList.php'>Back</a>";
}
mysqli_close($conn);
?>
