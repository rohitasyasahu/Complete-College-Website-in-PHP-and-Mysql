<?php

include('../db.php');
include 'header.php';

// Handle form submission for adding and editing subjects
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'add') {
        $subject_name = $_POST['subject_name'];
        $stream_id = $_POST['stream_id'];
        $sql = "INSERT INTO subjects (subject_name, stream_id) VALUES ('$subject_name', '$stream_id')";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Subject added successfully!');</script>";
        } else {
            echo "<script>alert('Error adding subject: " . $conn->error . "');</script>";
        }
    } elseif ($action === 'edit') {
        $subject_id = $_POST['subject_id'];
        $subject_name = $_POST['subject_name'];
        $stream_id = $_POST['stream_id'];
        $sql = "UPDATE subjects SET subject_name='$subject_name', stream_id='$stream_id' WHERE id='$subject_id'";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Subject updated successfully!');</script>";
        } else {
            echo "<script>alert('Error updating subject: " . $conn->error . "');</script>";
        }
    } elseif ($action === 'delete') {
        $subject_id = $_POST['subject_id'];
        $sql = "DELETE FROM subjects WHERE id='$subject_id'";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Subject deleted successfully!');</script>";
        } else {
            echo "<script>alert('Error deleting subject: " . $conn->error . "');</script>";
        }
    }
}

// Fetch subjects for display
$subjects = [];
$sql = "SELECT subjects.id, subjects.subject_name, subjects.stream_id, streams.stream_name 
        FROM subjects 
        JOIN streams ON subjects.stream_id = streams.id 
        ORDER BY subjects.id ASC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $subjects[] = $row;
    }
}

// Fetch streams for the dropdown
$streams = [];
$sql = "SELECT * FROM streams ORDER BY id ASC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $streams[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Subjects</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color:;
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
        input[type="text"], select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Manage Subjects</h2>

    <!-- Add/Edit Subject Form -->
    <form method="POST" id="subjectForm">
        <input type="hidden" name="action" value="add" id="formAction">
        <input type="hidden" name="subject_id" id="subjectId">

        <div class="form-group">
            <label for="subject_name">Subject Name</label>
            <input type="text" name="subject_name" id="subjectName" required>
        </div>

        <div class="form-group">
            <label for="stream_id">Stream</label>
            <select name="stream_id" id="streamId" required>
                <option value="">Select a Stream</option>
                <?php foreach ($streams as $stream): ?>
                    <option value="<?php echo $stream['id']; ?>"><?php echo $stream['stream_name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="action-btn">Submit</button>
    </form>

    <h3>Existing Subjects</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Subject Name</th>
            <th>Stream</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($subjects as $subject): ?>
            <tr>
                <td><?php echo $subject['id']; ?></td>
                <td><?php echo $subject['subject_name']; ?></td>
                <td><?php echo $subject['stream_name']; ?></td>
                <td>
                    <button class="action-btn" onclick="editSubject(<?php echo $subject['id']; ?>, '<?php echo addslashes($subject['subject_name']); ?>', <?php echo $subject['stream_id']; ?>)">Edit</button>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="subject_id" value="<?php echo $subject['id']; ?>">
                        <button type="submit" class="action-btn" style="background-color: #e74c3c;">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<script>
    // JavaScript for handling Edit functionality
    function editSubject(id, name, streamId) {
        document.getElementById('formAction').value = 'edit';
        document.getElementById('subjectId').value = id;
        document.getElementById('subjectName').value = name;
        document.getElementById('streamId').value = streamId;
    }
</script>

</body>
</html>
