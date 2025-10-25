<?php
session_start();

// Include database connection (uses mysqli)
require_once 'dbconnect.php';

// Helper to redirect back with a simple message
function back_with_message($msg) {
    echo $msg . " <a href='Login.html'>Go back</a>";
    exit;
}



// Get form inputs
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// Basic validation

// Fetch user by email using mysqli_query
$email_safe = mysqli_real_escape_string($conn, $email);
$sql = "SELECT id, name, email, mobile, password FROM users WHERE email = '" . $email_safe . "' LIMIT 1";
$result = mysqli_query($conn, $sql);

if ($result && $row = mysqli_fetch_assoc($result)) {
    // Compare plain text password (no hashing)
    if ($password === $row['password']) {
        // Login successful: store user info in session
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['name'] = $row['name'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['mobile'] = isset($row['mobile']) ? $row['mobile'] : '';

        // Redirect to Home page (PHP version for dynamic nav)
        header('Location: Home.php');
        exit;
    } else {
        back_with_message('Incorrect password.');
    }
} else {
    back_with_message('No account found with that email.');
}

$conn->close();
?>
