<?php
// Include the database connection file
include('db.php');

// Fetch menus from the database in the correct order
$sql = "SELECT * FROM menus ORDER BY menu_order ASC";
$result = $conn->query($sql);

// Check if there was a result from the database query
if (!$result) {
    die("Error executing query: " . $conn->error);
}

// Function to display submenus recursively
function display_submenus($parent_id, $conn) {
    $sql = "SELECT * FROM menus WHERE parent_id = $parent_id ORDER BY menu_order ASC";
    $submenus = $conn->query($sql);

    if ($submenus->num_rows > 0) {
        echo "<ul class='submenu'>"; // Start submenu list with 'submenu' class
        while ($submenu = $submenus->fetch_assoc()) {
            echo '<li><a href="menu.php?id=' . $submenu['id'] . '">' . htmlspecialchars($submenu['menu_name']) . '</a>';
            // Recursively display any submenus for the current submenu
            display_submenus($submenu['id'], $conn);
            echo '</li>';
        }
        echo "</ul>"; // End submenu list
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deepak Tech</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        header {
            background-color: #2c3e50;
            padding: 15px 0;
        }

        /* Logo styling */
        .logo h2 {
            color: #ecf0f1;
            margin-left: 20px;
        }

        /* Main navigation styling */
        nav ul {
            list-style: none;
            display: flex;
            justify-content: flex-start;
            align-items: center;
            padding-left: 20px;
        }

        nav ul li {
            position: relative;
            margin-right: 20px;
        }

        nav ul li a {
            display: block;
            padding: 10px 15px;
            color: #ecf0f1;
            text-decoration: none;
            transition: background-color 0.3s ease, color 0.3s ease;
            border-radius: 4px;
        }

        nav ul li a:hover {
            background-color: #34495e;
            color: #ecf0f1;
        }

        /* Submenu styling */
        ul.submenu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: #34495e;
            min-width: 200px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            border-radius: 4px;
        }

        ul.submenu li {
            width: 100%;
        }

        ul.submenu li a {
            padding: 10px 15px;
            white-space: nowrap;
            color: #ecf0f1;
        }

        ul.submenu li a:hover {
            background-color: #2c3e50;
        }

        /* Show submenu on hover */
        nav ul li:hover > ul.submenu {
            display: block;
        }

        /* Submenu styling for mobile */
        @media (max-width: 768px) {
            nav ul {
                flex-direction: column;
            }

            ul.submenu {
                position: relative;
                top: auto;
                left: auto;
                background-color: #34495e;
            }

            ul.submenu li a {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header>
        <!-- Logo Section -->
        <div class="logo">
            <h2>Deepak Tech</h2>
        </div>

        <!-- Menu Section -->
        <nav>
            <ul>
                <!-- Add Home as the first item in the menu -->
                <li><a href="index.php">Home</a></li>
                <li><a href="notice.php">Notice</a></li>

                <?php
                // If there are menu items, loop through them
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Extract the menu id, name, and parent id
                        $menu_id = $row['id'];
                        $menu_name = htmlspecialchars($row['menu_name']);  // Sanitize output
                        $parent_id = $row['parent_id'];

                        // Only display parent menus (those with parent_id = NULL)
                        if (is_null($parent_id)) {
                            echo '<li><a href="menu.php?id=' . $menu_id . '">' . $menu_name . '</a>';

                            // Call function to display submenus under this parent menu
                            display_submenus($menu_id, $conn);

                            echo '</li>';
                        }
                    }
                } else {
                    echo '<li>No menus available</li>';
                }
                ?>
            </ul>
        </nav>
    </header>
</body>
</html>
