<?php
session_start();

// Check if the admin is logged in
if (isset($_SESSION['admin_logged_in'])) {
    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();
}

// Redirect to login page after logout
header("Location: login.php");
exit();
?>