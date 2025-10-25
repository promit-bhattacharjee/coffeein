<?php
include 'dbconnect.php';

// Get form values
$name = $_POST['name'];
$email = $_POST['email'];
$mobile = isset($_POST['mobile']) ? $_POST['mobile'] : '';
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Basic validation

// No hashing: use the password as-is (per your request)

// Ensure users table has a mobile column
@mysqli_query($conn, "ALTER TABLE users ADD COLUMN IF NOT EXISTS mobile VARCHAR(50)");

// Insert into database
// Escape inputs to avoid SQL injection
$name_safe = mysqli_real_escape_string($conn, $name);
$email_safe = mysqli_real_escape_string($conn, $email);
$mobile_safe = mysqli_real_escape_string($conn, $mobile);
$password_safe = mysqli_real_escape_string($conn, $password);

$sql = "INSERT INTO users (name, email, mobile, password) VALUES ('" . $name_safe . "', '" . $email_safe . "', '" . $mobile_safe . "', '" . $password_safe . "')";

if (mysqli_query($conn, $sql)) {
    echo "Signup successful! <a href='login.html'>Login here</a>";
} else {
    if ($conn->errno === 1062) {
        echo "Email already exists. <a href='signup.html'>Go back</a>";
    } else {
        echo "Error: " . $conn->error;
    }
}

mysqli_close($conn);
?>
