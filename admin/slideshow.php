<?php

include('../db.php');
include 'header.php';

// Handle form submission for adding and deleting slideshow images
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'add') {
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $target_dir = "uploads/slideshow/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Check if image file is a valid image
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check !== false) {
                // Check file size (limit to 8MB)
                if ($_FILES["image"]["size"] > 8000000) {
                    echo "Sorry, your file is too large.";
                } else {
                    // Allow certain file formats
                    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                    } else {
                        // Move uploaded file to target directory
                        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                            $sql = "INSERT INTO slideshow (image_path) VALUES ('$target_file')";
                            if ($conn->query($sql) === TRUE) {
                                echo "The image has been uploaded successfully.";
                            } else {
                                echo "Error: " . $sql . "<br>" . $conn->error;
                            }
                        } else {
                            echo "Sorry, there was an error uploading your file.";
                        }
                    }
                }
            } else {
                echo "File is not an image.";
            }
        }
    } elseif ($action === 'delete') {
        $image_id = $_POST['image_id'];
        $sql = "SELECT image_path FROM slideshow WHERE id='$image_id'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        
        if ($row) {
            $file_path = $row['image_path'];
            if (unlink($file_path)) {
                $sql = "DELETE FROM slideshow WHERE id='$image_id'";
                if ($conn->query($sql) === TRUE) {
                    echo "Image deleted successfully.";
                } else {
                    echo "Error: " . $conn->error;
                }
            } else {
                echo "Error deleting file.";
            }
        } else {
            echo "Image not found.";
        }
    }
}

// Fetch slideshow images for display
$images = [];
$sql = "SELECT * FROM slideshow ORDER BY id DESC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $images[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Slideshow Images</title>

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
        input[type="file"] {
            padding: 8px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Manage Slideshow Images</h2>

    <!-- Add Image Form -->
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="add">
        <div class="form-group">
            <label for="image">Upload Image</label>
            <input type="file" name="image" id="image" accept="image/*" required>
        </div>
        <button type="submit" class="action-btn">Upload Image</button>
    </form>

    <h3>Existing Slideshow Images</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($images as $image): ?>
            <tr>
                <td><?php echo $image['id']; ?></td>
                <td><img src="<?php echo $image['image_path']; ?>" alt="Slideshow Image" style="width: 150px; height: auto;"></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="image_id" value="<?php echo $image['id']; ?>">
                        <button type="submit" class="action-btn" style="background-color: #e74c3c;">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

</body>
</html>