<?php
// Include the database connection file
include('db.php');
include 'header.php';

// Check if the id is set in the URL
if (isset($_GET['id'])) {
    $menu_id = intval($_GET['id']);  // Ensure it's an integer for security

    // Prepare the SQL query to fetch the menu content
    $sql = "SELECT * FROM menus WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $menu_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the result
    if ($result->num_rows > 0) {
        $menu = $result->fetch_assoc();
        $menu_name = $menu['menu_name'];
        $menu_content = $menu['content'];
    } else {
        echo "Menu not found.";
        exit;
    }
} else {
    echo "No menu selected.";
    exit;
}

// Update the image paths in the content to point to the correct directory
$menu_content = str_replace('src="uploads/', 'src="/uploads/', $menu_content);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $menu_name; ?></title>
</head>
<body>
    <div>
        <?php echo $menu_content; ?> <!-- Raw HTML content will be rendered here -->
    </div>
</body>
</html>
