<?php

include('../db.php');
include 'header.php';
$menu_id = $_GET['id'];
$sql = "SELECT * FROM menus WHERE id='$menu_id'";
$result = $conn->query($sql);
$menu = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Menu</title>
</head>
<body>
    <h2>Menu Details</h2>
    <p><strong>Menu Name:</strong> <?php echo $menu['menu_name']; ?></p>
    <p><strong>Menu Link:</strong> <?php echo $menu['menu_link']; ?></p>
    <p><strong>Parent Menu:</strong> <?php echo $menu['parent_id'] ? 'Submenu' : 'Main Menu'; ?></p>
    <p><strong>Order:</strong> <?php echo $menu['menu_order']; ?></p>
    <p><strong>Content:</strong></p>
    <div><?php echo $menu['content']; ?></div>
    <a href="menu.php">Back to Menus</a>
</body>
</html>
