<?php
include('../db.php');
include 'header.php';

// Handle form submission for adding and deleting menus
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'add') {
        $menu_name = $_POST['menu_name'];
        $menu_link = $_POST['menu_link'];
        $parent_id = $_POST['parent_id'] ?: NULL; 
        $menu_order = $_POST['menu_order'];
        $content = $_POST['content'];

        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO menus (menu_name, menu_link, parent_id, menu_order, content) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiss", $menu_name, $menu_link, $parent_id, $menu_order, $content);

        if ($stmt->execute()) {
            echo "New menu added successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();

    } elseif ($action === 'delete') {
        $menu_id = $_POST['menu_id'];

        // Use prepared statement for deletion
        $stmt = $conn->prepare("DELETE FROM menus WHERE id=?");
        $stmt->bind_param("i", $menu_id);

        if ($stmt->execute()) {
            echo "Menu deleted successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close(); 
    }
}

// Fetch menus for display
$menus = [];
$sql = "SELECT * FROM menus ORDER BY menu_order ASC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $menus[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Menus</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <!-- include summernote css/js -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f4f4f9;
            border-radius: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .action-btn {
            padding: 5px 10px;
            background-color: #5c67f5;
            color: white;
            border: none;
            cursor: pointer;
        }
        .action-btn:hover {
            background-color: #4b56e2;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        input[type="text"], input[type="number"], select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
		
		ul#menuList, ul.submenu {
    list-style-type: none;
    padding: 0;
}

ul.submenu {
    margin-left: 20px;
}

.menu-item div {
    padding: 10px;
    background-color: #f0f0f0;
    border: 1px solid #ccc;
    margin-bottom: 5px;
    cursor: move;
}

.ui-state-highlight {
    height: 40px;
    background-color: #f0f9ff;
    border: 1px dashed #ccc;
    margin-bottom: 5px;
}

    </style>
</head>
<body>
<div class="container">
    <h2>Manage Menus & Submenus</h2>
    <!-- Add Menu Form -->
    <form method="POST" id="menuForm">
        <input type="hidden" name="action" value="add" id="formAction">
        <input type="hidden" name="menu_id" id="menuId">
        <div class="form-group">
            <label for="menu_name">Menu Name</label>
            <input type="text" name="menu_name" id="menuName" required>
        </div>
        <div class="form-group">
            <label for="menu_link">Menu Link</label>
            <input type="text" name="menu_link" id="menuLink" required>
        </div>
        <div class="form-group">
            <label for="parent_id">Parent Menu (if this is a submenu)</label>
            <select name="parent_id" id="parentId">
                <option value="">None (Top-level menu)</option>
                <?php foreach ($menus as $menu): ?>
                    <?php if ($menu['parent_id'] == NULL): ?>
                        <option value="<?php echo $menu['id']; ?>"><?php echo $menu['menu_name']; ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="menu_order">Menu Order</label>
            <input type="number" name="menu_order" id="menuOrder" required>
        </div>
        <div class="form-group">
            <label for="content">Content</label>
            <textarea name="content" id="contentEditor"></textarea>
        </div>
        <button type="submit" class="action-btn">Submit</button>
    </form>
    <h3>Existing Menus</h3>
<ul id="menuList">
    <?php foreach ($menus as $menu): ?>
        <?php if (!$menu['parent_id']): // Only show top-level menus ?>
            <li id="menu-<?php echo $menu['id']; ?>" class="menu-item" data-id="<?php echo $menu['id']; ?>">
                <div>
                    <span><?php echo $menu['menu_name']; ?></span>
                    <a href="menuedit.php?id=<?php echo $menu['id']; ?>" class="action-btn">Edit</a>
                    <a href="menuview.php?id=<?php echo $menu['id']; ?>" class="action-btn">View</a>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="menu_id" value="<?php echo $menu['id']; ?>">
                        <button type="submit" class="action-btn" style="background-color: #e74c3c;">Delete</button>
                    </form>
                </div>
                <ul class="submenu">
                    <?php foreach ($menus as $submenu): ?>
                        <?php if ($submenu['parent_id'] == $menu['id']): ?>
                            <li id="menu-<?php echo $submenu['id']; ?>" class="menu-item" data-id="<?php echo $submenu['id']; ?>">
                                <div>
                                    <span><?php echo $submenu['menu_name']; ?></span>
                                    <a href="menuedit.php?id=<?php echo $submenu['id']; ?>" class="action-btn">Edit</a>
                                    <a href="menuview.php?id=<?php echo $submenu['id']; ?>" class="action-btn">View</a>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="menu_id" value="<?php echo $submenu['id']; ?>">
                                        <button type="submit" class="action-btn" style="background-color: #e74c3c;">Delete</button>
                                    </form>
                                </div>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </li>
        <?php endif; ?>
    <?php endforeach; ?>
</ul>

</div>
<!-- Add Summernote with image upload functionality -->
<script>
function buildHierarchy($elements) {
    var hierarchy = [];
    $elements.each(function() {
        var id = $(this).data('id');
        var children = $(this).find('> ul > li');
        var submenu = buildHierarchy(children);
        hierarchy.push({ id: id, children: submenu });
    });
    return hierarchy;
}

$(document).ready(function() {
    $('#menuList, .submenu').sortable({
        connectWith: '#menuList, .submenu',
        update: function(event, ui) {
            var hierarchy = buildHierarchy($('#menuList > li'));

            // Check if hierarchy is being built correctly
            console.log(hierarchy);

            $.ajax({
                url: 'update_order.php',
                type: 'POST',
                data: { order: hierarchy },
                success: function(response) {
                    console.log('Save response:', response);  // Check the server response here
                },
                error: function(xhr, status, error) {
                    alert("Error: " + error);
                }
            });
        }
    }).disableSelection();
});


</script>
</body>
</html>