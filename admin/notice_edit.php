<?php
include('../db.php');
include 'header.php';

// Check if the ID is provided in the URL
if (isset($_GET['id'])) {
    $notice_id = intval($_GET['id']); // Get the notice ID from the URL

    // Fetch the notice data based on the ID
    $sql = "SELECT * FROM notice WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $notice_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $notice = $result->fetch_assoc();
    } else {
        echo "Notice not found.";
        exit;
    }
} else {
    echo "No notice ID provided.";
    exit;
}

// Handle form submission to update the notice
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $notice_number = $_POST['notice_number'];
    $notice_name = $_POST['notice_name'];
    $notice_date = $_POST['notice_date'];
    $current_file = $notice['notice_file']; // Keep the current file path for the file update

    // Check if a new file is uploaded
    if (isset($_FILES['notice_file']) && $_FILES['notice_file']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "uploads/notices/";
        $target_file = $target_dir . basename($_FILES["notice_file"]["name"]);
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Move uploaded file to target directory
        if (move_uploaded_file($_FILES["notice_file"]["tmp_name"], $target_file)) {
            // If there's a new file, delete the old one
            if (file_exists($current_file)) {
                unlink($current_file);
            }
            $current_file = $target_file; // Update to the new file
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    // Update the database record with the new data
    $update_sql = "UPDATE notice SET notice_number = ?, notice_name = ?, notice_date = ?, notice_file = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssssi", $notice_number, $notice_name, $notice_date, $current_file, $notice_id);

    if ($update_stmt->execute()) {
        echo "Notice updated successfully.";
    } else {
        echo "Error updating notice: " . $update_stmt->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Notice</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
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
        input[type="text"], input[type="date"], input[type="file"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
        }
        button {
            padding: 10px 20px;
            background-color: #5c67f5;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #4b56e2;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Notice</h2>

    <!-- Edit Notice Form -->
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="notice_number">Notice Number</label>
            <input type="text" name="notice_number" id="notice_number" value="<?php echo $notice['notice_number']; ?>" required>
        </div>
        <div class="form-group">
            <label for="notice_name">Notice Name</label>
            <input type="text" name="notice_name" id="notice_name" value="<?php echo $notice['notice_name']; ?>" required>
        </div>
        <div class="form-group">
            <label for="notice_date">Notice Date</label>
            <input type="date" name="notice_date" id="notice_date" value="<?php echo $notice['notice_date']; ?>" required>
        </div>
        <div class="form-group">
            <label for="notice_file">Upload New File (Leave empty to keep current file)</label>
            <input type="file" name="notice_file" id="notice_file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xlsx,.pptx">
            <br>
            <small>Current File: <a href="<?php echo $notice['notice_file']; ?>" target="_blank"><?php echo basename($notice['notice_file']); ?></a></small>
        </div>
        <button type="submit">Update Notice</button>
    </form>

</div>

</body>
</html>
