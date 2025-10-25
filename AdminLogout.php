<?php
session_start();
unset($_SESSION['admin_id']);
unset($_SESSION['admin_name']);
session_write_close();
header('Location: AdminLogin.html');
exit;
?>
