<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

include('../db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $menu_id = $_POST['menu_id'];

    // Delete menu item
    $sql = "DELETE FROM menus WHERE id='$menu_id'";
    $conn->query($sql);
    header("Location: menu.php"); // Redirect to menu management page after deletion
    exit();
}
?>
