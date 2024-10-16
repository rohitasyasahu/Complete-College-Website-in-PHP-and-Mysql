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

// Fetch menus for display (hierarchically)
function buildMenuTree($menus, $parent_id = NULL) {
    $branch = [];
    foreach ($menus as $menu) {
        if ($menu['parent_id'] == $parent_id) {
            $children = buildMenuTree($menus, $menu['id']);
            if ($children) {
                $menu['children'] = $children;
            }
            $branch[] = $menu;
        }
    }
    return $branch;
}

$menus = [];
$sql = "SELECT * FROM menus ORDER BY menu_order ASC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $menus[] = $row;
    }
}
$menuTree = buildMenuTree($menus);
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
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>
    <script src="https://github.com/ilikenwf/nestedSortable/blob/master/jquery.mjs.nestedSortable.js"></script>
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
        .sortable-placeholder {
            border: 1px dashed #ccc;
            height: 40px;
            background-color: #f4f4f4;
        }
        /* Add styles for the nested menu display */
        .dd { 
            position: relative;
            display: block;
            margin: 0;
            padding: 0;
            list-style: none;
            font-size: 13px;
            line-height: 20px;
        }
        .dd-list { 
            display: block;
            position: relative;
            margin: 0;
            padding: 0;
            list-style: none;
        }
        .dd-item,
        .dd-empty,
        .dd-placeholder { 
            display: block;
            position: relative;
            margin: 0;
            padding: 0;
            min-height: 20px;
            font-size: 13px;
            line-height: 20px;
        }
        .dd-handle {
            display: block;
            height: 30px;
            margin: 5px 0;
            padding: 5px 10px;
            color: #333;
            text-decoration: none;
            font-weight: bold;
            border: 1px solid #ccc;
            background: #fafafa;
            background: -webkit-linear-gradient(top, #fafafa 0%, #eee 100%);
            background:    -moz-linear-gradient(top, #fafafa 0%, #eee 100%);
            background:         linear-gradient(top, #fafafa 0%, #eee 100%);
            -webkit-border-radius: 3px;
                    border-radius: 3px;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }
        .dd-handle:hover {
            color: #2ea8e5;
            background: #fff;
        }
        .dd-item > button {
            display: block;
            position: relative;
            cursor: pointer;
            float: left;
            width: 25px;
            height: 20px;
            margin: 5px 0;
            padding: 0;
            text-indent: 100%;
            white-space: nowrap;
            overflow: hidden;
            border: 0;
            background: transparent;
            font-size: 12px;
            line-height: 1;
            text-align: center;
            font-weight: bold;
        }
        .dd-item > button:before { 
            content: '+';
            display: block;
            position: absolute;
            width: 100%;
            text-align: center;
            text-indent: 0;
        }
        .dd-item > button[data-action="collapse"]:before { 
            content: '-';
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Manage Menus & Submenus</h2>
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

    <table id="menuTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Menu Name</th>
                <th>Parent Menu</th>
                <th>Order</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($menus as $menu): ?>
                <tr data-id="<?php echo $menu['id']; ?>">
                    <td><?php echo $menu['id']; ?></td>
                    <td><?php echo $menu['menu_name']; ?></td>
                    <td><?php echo $menu['parent_id'] ? 'Submenu of ' . findParentName($menus, $menu['parent_id']) : 'Main Menu'; ?></td>
                    <td><?php echo $menu['menu_order']; ?></td>
                    <td>
                        <a href="menuedit.php?id=<?php echo $menu['id']; ?>" class="action-btn">Edit</a>
                        <a href="menuview.php?id=<?php echo $menu['id']; ?>" class="action-btn">View</a>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="menu_id" value="<?php echo $menu['id']; ?>">
                            <button type="submit" class="action-btn" style="background-color: #e74c3c;">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>


    <div class="dd" id="menuList">
        <ol class="dd-list">
            <?php
            function renderMenu($menu) {
                echo '<li class="dd-item" data-id="' . $menu['id'] . '">';
                echo '<div class="dd-handle">' . $menu['menu_name'] . '</div>';
                if (isset($menu['children'])) {
                    echo '<ol class="dd-list">';
                    foreach ($menu['children'] as $child) {
                        renderMenu($child);
                    }
                    echo '</ol>';
                }
                echo '</li>';
            }

            foreach ($menuTree as $menu) {
                renderMenu($menu);
            }
            ?>
        </ol>
    </div>

</div>

<?php
function findParentName($menus, $parent_id) {
    foreach ($menus as $menu) {
        if ($menu['id'] == $parent_id) {
            return $menu['menu_name'];
        }
    }
    return ''; 
}
?>

<script>
$(document).ready(function() {
    $('#contentEditor').summernote({
        height: 300,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ],
        callbacks: {
            onImageUpload: function(files) {
                var data = new FormData();
                data.append('file', files[0]);
                $.ajax({
                    url: 'upload_image.php',
                    type: 'POST',
                    data: data,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        var imageUrl = JSON.parse(response).url;
                        $('#contentEditor').summernote('insertImage', imageUrl);
                    },
                    error: function(xhr, status, error) {
                        alert("Error: " + error);
                    }
                });
            }
        }
    });

    $('#menuList').nestedSortable({
        handle: 'div',
        items: 'li',
        toleranceElement: '> div',
        placeholder: 'sortable-placeholder',
        update: function(event, ui) {
            var order = $(this).nestedSortable('toArray', { attribute: 'data-id' });
            console.log(order);

            $.ajax({
                url: 'update_order.php',
                type: 'POST',
                data: { order: order },
                success: function(response) {
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    alert("Error: " + error);
                }
            });
        }
    });
});
</script>
</body>
</html>