<?php
include('../db.php');
include 'header.php';

// Fetch menu details for editing
if (isset($_GET['id'])) {
    $menu_id = $_GET['id'];
    $sql = "SELECT * FROM menus WHERE id='$menu_id'";
    $result = $conn->query($sql);
    $menu = $result->fetch_assoc();
    if (!$menu) {
        echo "Menu not found!";
        exit();
    }
}

// Handle form submission for updating menu
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $menu_name = $_POST['menu_name'];
    $menu_link = $_POST['menu_link'];
    $parent_id = $_POST['parent_id'];
    $menu_order = $_POST['menu_order'];
    $content = $_POST['content'];
    // Update the image paths in content to reflect public directory
    $content = str_replace('src="uploads/', 'src="http://localhost/college/admin/uploads/', $content);

    $sql = "UPDATE menus SET 
                menu_name='$menu_name', 
                menu_link='$menu_link', 
                parent_id=" . ($parent_id ? "'$parent_id'" : "NULL") . ", 
                menu_order='$menu_order', 
                content='$content' 
            WHERE id='$menu_id'";
    if ($conn->query($sql) === TRUE) {
        echo "Menu updated successfully.";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Fetch all menus
$menus = [];
$sql = "SELECT * FROM menus ORDER BY parent_id, menu_order ASC";
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
    <title>Edit Menu</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
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
    </style>
</head>
<body>
<div class="container">
    <h2>Edit Menu</h2>
    <form method="POST" id="menuForm">
        <input type="hidden" name="menu_id" value="<?php echo $menu['id']; ?>">
        <div class="form-group">
            <label for="menu_name">Menu Name</label>
            <input type="text" name="menu_name" id="menuName" value="<?php echo $menu['menu_name']; ?>" required>
        </div>
        <div class="form-group">
            <label for="menu_link">Menu Link</label>
            <input type="text" name="menu_link" id="menuLink" value="<?php echo $menu['menu_link']; ?>" required>
        </div>
        <div class="form-group">
            <label for="parent_id">Parent Menu (if this is a submenu)</label>
            <select name="parent_id" id="parentId">
                <option value="">None (Top-level menu)</option>
                <?php foreach ($menus as $m): ?>
                    <?php if ($m['id'] != $menu['id']): // Prevent selecting the current menu as parent ?>
                        <option value="<?php echo $m['id']; ?>" <?php echo ($m['id'] == $menu['parent_id']) ? 'selected' : ''; ?>><?php echo $m['menu_name']; ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="menu_order">Menu Order</label>
            <input type="number" name="menu_order" id="menuOrder" value="<?php echo $menu['menu_order']; ?>" required>
        </div>
        <div class="form-group">
            <label for="content">Content</label>
            <textarea name="content" id="contentEditor"><?php echo htmlspecialchars($menu['content']); ?></textarea>
        </div>
        <button type="submit" class="action-btn">Update Menu</button>
    </form>
</div>
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
                data.append("file", files[0]);
                $.ajax({
                    url: 'upload.php',
                    method: 'POST',
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        response = JSON.parse(response);
                        if (response.error) {
                            console.log(response.error);
                        } else {
                            $('#contentEditor').summernote('insertImage', response.location);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log('Error: ' + textStatus + ' - ' + errorThrown);
                    }
                });
            }
        }
    });
});
</script>
</body>
</html>
