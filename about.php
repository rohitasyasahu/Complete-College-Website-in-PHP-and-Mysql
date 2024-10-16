<?php
include('db.php');

// Assuming the menu_link for the About page is 'about.php'
$menu_link = 'about.php';

$sql = "SELECT * FROM menus WHERE menu_link='profile'";
$result = $conn->query($sql);

// Check if any rows were returned
if ($result->num_rows > 0) {
    $menu = $result->fetch_assoc();

    // Now you can safely access $menu['menu_name'] and $menu['content']
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>About Us</title>
    </head>
    <body>

        <h1><?php echo $menu['menu_name']; ?></h1>
        <div><?php echo $menu['content']; ?></div>

    </body>
    </html>

    <?php
} else {
    // Handle the case where no menu item was found
    echo "Error: No menu item found for '$menu_link'";
    // Or display a 404 page
}
?>