<?php
include 'dbconnect.php';

// Ensure admins table exists
$create = "CREATE TABLE IF NOT EXISTS admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
mysqli_query($conn, $create);

// Read POST (no validation, plain text password per project style)
$name = isset($_POST['name']) ? $_POST['name'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

$name_safe = mysqli_real_escape_string($conn, $name);
$email_safe = mysqli_real_escape_string($conn, $email);
$pass_safe = mysqli_real_escape_string($conn, $password);

$sql = "INSERT INTO admins (name, email, password) VALUES ('".$name_safe."', '".$email_safe."', '".$pass_safe."')";
if (mysqli_query($conn, $sql)) {
  echo "Admin signup successful! <a href='AdminLogin.html'>Login</a>";
} else {
  if ($conn->errno === 1062) {
    echo "Admin email already exists. <a href='AdminSignup.html'>Go back</a>";
  } else {
    echo "Error: " . $conn->error . " <a href='AdminSignup.html'>Back</a>";
  }
}
mysqli_close($conn);
?>
