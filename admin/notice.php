<?php
include('../db.php');
include 'header.php';

// Handle form submission for adding notice
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $notice_number = $_POST['notice_number'];
    $notice_name = $_POST['notice_name'];
    $notice_date = $_POST['notice_date'];

    if (isset($_FILES['notice_file']) && $_FILES['notice_file']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "uploads/notices/";
        $target_file = $target_dir . basename($_FILES["notice_file"]["name"]);
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Move uploaded file to target directory
        if (move_uploaded_file($_FILES["notice_file"]["tmp_name"], $target_file)) {
            $sql = "INSERT INTO notice (notice_number, notice_name, notice_date, notice_file) 
                    VALUES ('$notice_number', '$notice_name', '$notice_date', '$target_file')";
            if ($conn->query($sql) === TRUE) {
                echo "Notice added successfully.";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

// Fetch notices for display in the table
$notices = [];
$sql = "SELECT * FROM notice ORDER BY id DESC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $notices[] = $row;
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Notices</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
        .action-links a {
            margin-right: 10px;
            color: #007bff;
            text-decoration: none;
        }
        .action-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Add Notice</h2>

    <!-- Add Notice Form -->
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="notice_number">Notice Number</label>
            <input type="text" name="notice_number" id="notice_number" required>
        </div>
        <div class="form-group">
            <label for="notice_name">Notice Name</label>
            <input type="text" name="notice_name" id="notice_name" required>
        </div>
        <div class="form-group">
            <label for="notice_date">Notice Date</label>
            <input type="date" name="notice_date" id="notice_date" required>
        </div>
        <div class="form-group">
            <label for="notice_file">Upload Notice File</label>
            <input type="file" name="notice_file" id="notice_file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xlsx,.pptx" required>
        </div>
        <button type="submit">Add Notice</button>
    </form>

    <h3>Existing Notices</h3>
    <table>
        <tr>
            <th>Notice Number</th>
            <th>Notice Name</th>
            <th>Notice Date</th>
            <th>File</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($notices as $notice): ?>
            <tr>
                <td><?php echo $notice['notice_number']; ?></td>
                <td><?php echo $notice['notice_name']; ?></td>
                <td><?php echo $notice['notice_date']; ?></td>
                <td>
                    <?php
                    // Display file icon based on file type
                    $file_extension = pathinfo($notice['notice_file'], PATHINFO_EXTENSION);
                    if ($file_extension == 'pdf') {
                        echo '<i class="fas fa-file-pdf" style="color:red;"></i> PDF';
                    } elseif (in_array($file_extension, ['jpg', 'jpeg', 'png'])) {
                        echo '<i class="fas fa-image" style="color:green;"></i> Image';
                    } elseif ($file_extension == 'doc' || $file_extension == 'docx') {
                        echo '<i class="fas fa-file-word" style="color:blue;"></i> Word';
                    } elseif ($file_extension == 'xlsx') {
                        echo '<i class="fas fa-file-excel" style="color:green;"></i> Excel';
                    } elseif ($file_extension == 'pptx') {
                        echo '<i class="fas fa-file-powerpoint" style="color:orange;"></i> PowerPoint';
                    } else {
                        echo '<i class="fas fa-file" style="color:gray;"></i> Other';
                    }
                    ?>
                </td>
                <td class="action-links">
                    <a href="notice_edit.php?id=<?php echo $notice['id']; ?>">Edit</a> |
                    <a href="notice_delete.php?id=<?php echo $notice['id']; ?>" onclick="return confirm('Are you sure you want to delete this notice?');">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

</body>
</html>
