<?php

include('../db.php');
include 'header.php';

// Handling form submission for adding faculty members
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'add') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $mobile = $_POST['mobile'];
        $address = $_POST['address'];
        $stream_id = $_POST['stream_id'];
        $subject_id = $_POST['subject_id'];

        // Handling photo upload
        $photo = $_FILES['photo']['name'];
        $photo_tmp = $_FILES['photo']['tmp_name'];
        move_uploaded_file($photo_tmp, "uploads/$photo");

        // Insert faculty details
        $sql = "INSERT INTO faculty (name, email, mobile, address, photo, stream_id, subject_id) 
                VALUES ('$name', '$email', '$mobile', '$address', '$photo', '$stream_id', '$subject_id')";
        $conn->query($sql);
        $faculty_id = $conn->insert_id;

        // Insert qualifications
        foreach ($_POST['qualifications'] as $qualification) {
            $class = $qualification['class'];
            $board_name = $qualification['board_name'];
            $institute_name = $qualification['institute_name'];
            $full_mark = $qualification['full_mark'];
            $secure_mark = $qualification['secure_mark'];
            $percentage = ($secure_mark / $full_mark) * 100;
            
            $sql_qualification = "INSERT INTO faculty_qualifications 
            (faculty_id, class, board_name, institute_name, full_mark, secure_mark, percentage) 
            VALUES ('$faculty_id', '$class', '$board_name', '$institute_name', '$full_mark', '$secure_mark', '$percentage')";
            $conn->query($sql_qualification);
        }
    } elseif ($action === 'edit') {
        // Editing logic here
    } elseif ($action === 'delete') {
        $faculty_id = $_POST['faculty_id'];
        $sql = "DELETE FROM faculty WHERE id='$faculty_id'";
        $conn->query($sql);
        $sql_qualification = "DELETE FROM faculty_qualifications WHERE faculty_id='$faculty_id'";
        $conn->query($sql_qualification);
    }
}

// Fetching all faculty members for display
$faculty = [];
$sql = "SELECT f.*, s.stream_name, sub.subject_name FROM faculty f 
        LEFT JOIN streams s ON f.stream_id = s.id
        LEFT JOIN subjects sub ON f.subject_id = sub.id";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $faculty[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Faculty</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="container">
    <h2>Manage Faculty</h2>

    <!-- Add/Edit Faculty Form -->
    <form method="POST" enctype="multipart/form-data" id="facultyForm">
        <input type="hidden" name="action" value="add" id="formAction">
        <input type="hidden" name="faculty_id" id="facultyId">

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>
        </div>

        <div class="form-group">
            <label for="mobile">Mobile</label>
            <input type="text" name="mobile" id="mobile" required>
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <textarea name="address" id="address" required></textarea>
        </div>

        <div class="form-group">
            <label for="photo">Photo</label>
            <input type="file" name="photo" id="photo" required>
        </div>

        <div class="form-group">
            <label for="stream_id">Stream</label>
            <select name="stream_id" id="stream_id">
                <!-- Fetch streams dynamically -->
                <?php
                $streams = $conn->query("SELECT * FROM streams");
                while ($row = $streams->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>{$row['stream_name']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="subject_id">Subject</label>
            <select name="subject_id" id="subject_id">
                <!-- Fetch subjects dynamically -->
                <?php
                $subjects = $conn->query("SELECT * FROM subjects");
                while ($row = $subjects->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>{$row['subject_name']}</option>";
                }
                ?>
            </select>
        </div>

		<table id="qualificationTable">
    <thead>
        <tr>
            <th>Class</th>
            <th>Board Name</th>
            <th>Institute Name</th>
            <th>Full Mark</th>
            <th>Secure Mark</th>
            <th>Percentage</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="qualificationContainer">
        <tr>
            <td><input type="text" name="qualifications[0][class]" placeholder="Class"></td>
            <td><input type="text" name="qualifications[0][board_name]" placeholder="Board Name"></td>
            <td><input type="text" name="qualifications[0][institute_name]" placeholder="Institute Name"></td>
            <td><input type="number" name="qualifications[0][full_mark]" placeholder="Full Mark" oninput="calculatePercentage(this)"></td>
            <td><input type="number" name="qualifications[0][secure_mark]" placeholder="Secure Mark" oninput="calculatePercentage(this)"></td>
            <td><input type="text" name="qualifications[0][percentage]" placeholder="Percentage" readonly></td>
            <td><button type="button" onclick="removeRow(this)">Remove</button></td>
        </tr>
    </tbody>
</table>
<br>
<button type="button" onclick="addQualificationRow()">Add More</button>
<button type="submit" class="action-btn">Submit</button>

    </form>

    <!-- Display Faculty Table -->
<!-- Display Faculty Table -->
<h3>Faculty List</h3>
<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Stream</th>
            <th>Subject</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($faculty as $fac): ?>
            <tr>
                <td><?php echo htmlspecialchars($fac['name']); ?></td>
                <td><?php echo htmlspecialchars($fac['email']); ?></td>
                <td><?php echo htmlspecialchars($fac['mobile']); ?></td>
                <td><?php echo htmlspecialchars($fac['stream_name']); ?></td>
                <td><?php echo htmlspecialchars($fac['subject_name']); ?></td>
                <td>
                    <!-- Edit Button -->
                    <a href="facultyedit.php?id=<?php echo htmlspecialchars($fac['id']); ?>" class="action-btn">Edit</a>

                    <!-- View Button -->
                    <a href="facultyview.php?id=<?php echo htmlspecialchars($fac['id']); ?>" class="action-btn">View</a>

                    <!-- Delete Button Form -->
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="faculty_id" value="<?php echo htmlspecialchars($fac['id']); ?>">
                        <button type="submit" class="action-btn" style="background-color: #e74c3c;">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</div>

<script>
    function addQualificationRow() {
    const table = document.getElementById('qualificationTable').getElementsByTagName('tbody')[0];
    const rowCount = table.rows.length;

    const row = table.insertRow(rowCount);
    row.innerHTML = `
        <td><input type="text" name="qualifications[${rowCount}][class]" placeholder="Class"></td>
        <td><input type="text" name="qualifications[${rowCount}][board_name]" placeholder="Board Name"></td>
        <td><input type="text" name="qualifications[${rowCount}][institute_name]" placeholder="Institute Name"></td>
        <td><input type="number" name="qualifications[${rowCount}][full_mark]" placeholder="Full Mark" oninput="calculatePercentage(this)"></td>
        <td><input type="number" name="qualifications[${rowCount}][secure_mark]" placeholder="Secure Mark" oninput="calculatePercentage(this)"></td>
        <td><input type="text" name="qualifications[${rowCount}][percentage]" placeholder="Percentage" readonly></td>
        <td><button type="button" onclick="removeRow(this)">Remove</button></td>
    `;
}

function removeRow(button) {
    const row = button.parentElement.parentElement;
    row.parentElement.removeChild(row);
}

function calculatePercentage(input) {
    const row = input.parentElement.parentElement;
    const fullMark = row.querySelector('input[name$="[full_mark]"]').value;
    const secureMark = row.querySelector('input[name$="[secure_mark]"]').value;
    const percentageField = row.querySelector('input[name$="[percentage]"]');

    if (fullMark && secureMark) {
        const percentage = (secureMark / fullMark) * 100;
        percentageField.value = percentage.toFixed(2);
    } else {
        percentageField.value = '';
    }
}

</script>

</body>
</html>
