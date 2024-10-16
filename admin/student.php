<?php

include('../db.php');
include 'header.php';

// Handling form submission for adding/editing students
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    // Adding new student
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

        // Insert student details
        $sql = "INSERT INTO students (name, email, mobile, address, photo, stream_id, subject_id) 
                VALUES ('$name', '$email', '$mobile', '$address', '$photo', '$stream_id', '$subject_id')";
        $conn->query($sql);
        $student_id = $conn->insert_id;

        // Insert qualifications
        foreach ($_POST['qualifications'] as $qualification) {
            $class = $qualification['class'];
            $board_name = $qualification['board_name'];
            $institute_name = $qualification['institute_name'];
            $full_mark = $qualification['full_mark'];
            $secure_mark = $qualification['secure_mark'];
            $percentage = ($secure_mark / $full_mark) * 100;

            $sql_qualification = "INSERT INTO student_qualifications 
            (student_id, class, board_name, institute_name, full_mark, secure_mark, percentage) 
            VALUES ('$student_id', '$class', '$board_name', '$institute_name', '$full_mark', '$secure_mark', '$percentage')";
            $conn->query($sql_qualification);
        }
    } elseif ($action === 'edit') {
        // Edit student logic
        $student_id = $_POST['student_id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $mobile = $_POST['mobile'];
        $address = $_POST['address'];
        $stream_id = $_POST['stream_id'];
        $subject_id = $_POST['subject_id'];

        // Update student details
        $sql = "UPDATE students SET name='$name', email='$email', mobile='$mobile', address='$address', stream_id='$stream_id', subject_id='$subject_id' WHERE id='$student_id'";
        $conn->query($sql);

        // Handling photo upload if any
        if (!empty($_FILES['photo']['name'])) {
            $photo = $_FILES['photo']['name'];
            $photo_tmp = $_FILES['photo']['tmp_name'];
            move_uploaded_file($photo_tmp, "uploads/$photo");

            $sql_photo = "UPDATE students SET photo='$photo' WHERE id='$student_id'";
            $conn->query($sql_photo);
        }

        // Update qualifications
        $conn->query("DELETE FROM student_qualifications WHERE student_id='$student_id'");
        foreach ($_POST['qualifications'] as $qualification) {
            $class = $qualification['class'];
            $board_name = $qualification['board_name'];
            $institute_name = $qualification['institute_name'];
            $full_mark = $qualification['full_mark'];
            $secure_mark = $qualification['secure_mark'];
            $percentage = ($secure_mark / $full_mark) * 100;

            $sql_qualification = "INSERT INTO student_qualifications 
            (student_id, class, board_name, institute_name, full_mark, secure_mark, percentage) 
            VALUES ('$student_id', '$class', '$board_name', '$institute_name', '$full_mark', '$secure_mark', '$percentage')";
            $conn->query($sql_qualification);
        }
    } elseif ($action === 'delete') {
        // Delete student logic
        $student_id = $_POST['student_id'];
        $conn->query("DELETE FROM students WHERE id='$student_id'");
        $conn->query("DELETE FROM student_qualifications WHERE student_id='$student_id'");
    }
}

// Fetch all students for display
$students = [];
$sql = "SELECT s.*, st.stream_name, sub.subject_name FROM students s 
        LEFT JOIN streams st ON s.stream_id = st.id
        LEFT JOIN subjects sub ON s.subject_id = sub.id";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="container">
    <h2>Manage Students</h2>

    <!-- Add/Edit Student Form -->
    <form method="POST" enctype="multipart/form-data" id="studentForm">
        <input type="hidden" name="action" value="add" id="formAction">
        <input type="hidden" name="student_id" id="studentId">

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
            <input type="file" name="photo" id="photo">
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

        <!-- Educational Qualification Section -->
        <h4>Educational Qualification</h4>
        <div id="qualificationContainer">
            <div class="qualificationRow">
                <input type="text" name="qualifications[0][class]" placeholder="Class">
                <input type="text" name="qualifications[0][board_name]" placeholder="Board Name">
                <input type="text" name="qualifications[0][institute_name]" placeholder="Institute Name">
                <input type="number" name="qualifications[0][full_mark]" placeholder="Full Mark">
                <input type="number" name="qualifications[0][secure_mark]" placeholder="Secure Mark">
                <input type="text" name="qualifications[0][percentage]" placeholder="Percentage" readonly>
                <button type="button" onclick="removeRow(this)">Remove</button>
            </div>
        </div>
        <button type="button" onclick="addQualificationRow()">Add More</button>

        <button type="submit">Submit</button>
    </form>

    <!-- Display Student Table -->
    <h3>Student List</h3>
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
        <?php foreach ($students as $student): ?>
            <tr>
                <td><?php echo $student['name']; ?></td>
                <td><?php echo $student['email']; ?></td>
                <td><?php echo $student['mobile']; ?></td>
                <td><?php echo $student['stream_name']; ?></td>
                <td><?php echo $student['subject_name']; ?></td>
                <td>
                    <button onclick="editStudent(<?php echo $student['id']; ?>)">Edit</button>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                        <button type="submit" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</div>

<script src="main.js"></script>
<script>
    function addQualificationRow() {
        let container = document.getElementById('qualificationContainer');
        let index = container.children.length;
        let row = document.createElement('div');
        row.className = 'qualificationRow';
        row.innerHTML = `<input type="text" name="qualifications[${index}][class]" placeholder="Class">
                         <input type="text" name="qualifications[${index}][board_name]" placeholder="Board Name">
                         <input type="text" name="qualifications[${index}][institute_name]" placeholder="Institute Name">
                         <input type="number" name="qualifications[${index}][full_mark]" placeholder="Full Mark">
                         <input type="number" name="qualifications[${index}][secure_mark]" placeholder="Secure Mark">
                         <input type="text" name="qualifications[${index}][percentage]" placeholder="Percentage" readonly>
                         <button type="button" onclick="removeRow(this)">Remove</button>`;
        container.appendChild(row);
    }

    function removeRow(button) {
        button.parentElement.remove();
    }

    function editStudent(id) {
        // Fetch student details via AJAX and populate form for editing
        // Set form action to 'edit' and populate fields accordingly
        document.getElementById('formAction').value = 'edit';
        document.getElementById('studentId').value = id;
        // You would use AJAX here to fetch the student details from the database
        // and populate the form with those values.
    }
</script>

</body>
</html>

<?php include 'footer.php'; ?>
