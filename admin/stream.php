<?php

include('../db.php');
include 'header.php';

// Handle form submission for adding and editing streams
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'add') {
        $stream_name = $_POST['stream_name'];
        $sql = "INSERT INTO streams (stream_name) VALUES ('$stream_name')";
        $conn->query($sql);
    } elseif ($action === 'edit') {
        $stream_id = $_POST['stream_id'];
        $stream_name = $_POST['stream_name'];
        $sql = "UPDATE streams SET stream_name='$stream_name' WHERE id='$stream_id'";
        $conn->query($sql);
    } elseif ($action === 'delete') {
        $stream_id = $_POST['stream_id'];
        $sql = "DELETE FROM streams WHERE id='$stream_id'";
        $conn->query($sql);
    }
}

// Fetch streams for display
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
    <title>Manage Streams</title>
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
        input[type="text"] {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Manage Streams</h2>

    <!-- Add/Edit Stream Form -->
    <form method="POST" id="streamForm">
        <input type="hidden" name="action" value="add" id="formAction">
        <input type="hidden" name="stream_id" id="streamId">

        <div class="form-group">
            <label for="stream_name">Stream Name</label>
            <input type="text" name="stream_name" id="streamName" required>
        </div>

        <button type="submit" class="action-btn">Submit</button>
    </form>

    <h3>Existing Streams</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Stream Name</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($streams as $stream): ?>
            <tr>
                <td><?php echo $stream['id']; ?></td>
                <td><?php echo $stream['stream_name']; ?></td>
                <td>
                    <button class="action-btn" onclick="editStream(<?php echo $stream['id']; ?>, '<?php echo $stream['stream_name']; ?>')">Edit</button>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="stream_id" value="<?php echo $stream['id']; ?>">
                        <button type="submit" class="action-btn" style="background-color: #e74c3c;">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<script>
    // JavaScript for handling Edit functionality
    function editStream(id, name) {
        document.getElementById('formAction').value = 'edit';
        document.getElementById('streamId').value = id;
        document.getElementById('streamName').value = name;
    }
</script>

</body>
</html>
