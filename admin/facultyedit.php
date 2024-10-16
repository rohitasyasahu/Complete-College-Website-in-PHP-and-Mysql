<?php
// Include necessary files
include('../db.php');
include 'header.php';

// Initialize variables
$faculty = null;
$qualifications = null;

// Check if 'id' is present in the GET request (for editing a faculty member)
if (isset($_GET['id'])) {
    $faculty_id = $_GET['id'];

    // Fetch the faculty details
    $sql = "SELECT f.*, s.stream_name, sub.subject_name FROM faculty f 
            LEFT JOIN streams s ON f.stream_id = s.id
            LEFT JOIN subjects sub ON f.subject_id = sub.id
            WHERE f.id = '$faculty_id'";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $faculty = $result->fetch_assoc();

        // Fetch the faculty qualifications
        $sql_qual = "SELECT * FROM faculty_qualifications WHERE faculty_id = '$faculty_id'";
        $qualifications = $conn->query($sql_qual);
    } else {
        echo "No faculty found with this ID.";
        exit;
    }
}

// Handling form submission for editing faculty members
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $faculty_id = $_POST['faculty_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];
    $stream_id = $_POST['stream_id'];
    $subject_id = $_POST['subject_id'];

    // Handle file upload for the photo if a new photo is uploaded
    if (!empty($_FILES['photo']['name'])) {
        $photo = $_FILES['photo']['name'];
        $photo_tmp = $_FILES['photo']['tmp_name'];
        move_uploaded_file($photo_tmp, "uploads/$photo");

        // Update faculty details with photo
        $sql = "UPDATE faculty 
                SET name='$name', email='$email', mobile='$mobile', address='$address', photo='$photo', stream_id='$stream_id', subject_id='$subject_id' 
                WHERE id='$faculty_id'";
    } else {
        // Update faculty details without changing the photo
        $sql = "UPDATE faculty 
                SET name='$name', email='$email', mobile='$mobile', address='$address', stream_id='$stream_id', subject_id='$subject_id' 
                WHERE id='$faculty_id'";
    }

    $conn->query($sql);

    // Update or Insert qualifications
    $qualification_ids = $_POST['qualification_id'];
    $classes = $_POST['class'];
    $boards = $_POST['board_name'];
    $institutes = $_POST['institute_name'];
    $full_marks = $_POST['full_mark'];
    $secure_marks = $_POST['secure_mark'];
    $percentages = $_POST['percentage'];

    // Prepare statements for better security
    $stmt = $conn->prepare("INSERT INTO faculty_qualifications (faculty_id, class, board_name, institute_name, full_mark, secure_mark, percentage) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssss", $faculty_id, $class, $board, $institute, $full_mark, $secure_mark, $percentage);

    foreach ($qualification_ids as $index => $qual_id) {
        $class = $classes[$index];
        $board = $boards[$index];
        $institute = $institutes[$index];
        $full_mark = $full_marks[$index];
        $secure_mark = $secure_marks[$index];
        $percentage = $percentages[$index];

        if ($qual_id) {
            // Update existing qualification
            $sql_qual = "UPDATE faculty_qualifications 
                         SET class=?, board_name=?, institute_name=?, full_mark=?, secure_mark=?, percentage=?
                         WHERE id=?";
            $stmt_update = $conn->prepare($sql_qual);
            $stmt_update->bind_param("ssssssi", $class, $board, $institute, $full_mark, $secure_mark, $percentage, $qual_id);
            $stmt_update->execute();
            $stmt_update->close();
        } else {
            // Insert new qualification
            $stmt->execute();
        }
    }
    
    $stmt->close();

    // Redirect back to the faculty management page after saving
    header('Location: faculty.php');
    exit;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Faculty Member</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        // JavaScript functions to handle adding/removing qualification rows and calculating percentage
        // Function to dynamically add qualification rows
function addQualificationRow() {
    const container = document.getElementById('qualification-container');
    const newRow = `
        <div class="qualification-row">
            <input type="hidden" name="qualification_id[]" value="">
            <input type="text" name="class[]" placeholder="Class" required>
            <input type="text" name="board_name[]" placeholder="Board Name" required>
            <input type="text" name="institute_name[]" placeholder="Institute Name" required>
            <input type="number" name="full_mark[]" placeholder="Full Marks" required oninput="calculatePercentage(this)">
            <input type="number" name="secure_mark[]" placeholder="Secured Marks" required oninput="calculatePercentage(this)">
            <input type="number" name="percentage[]" placeholder="Percentage" readonly>
            <button type="button" onclick="removeQualificationRow(this)">Remove</button>
        </div>`;
    container.insertAdjacentHTML('beforeend', newRow);
}


        function removeQualificationRow(button) {
            const row = button.parentElement.parentElement;
            row.parentElement.removeChild(row);
        }

        function calculatePercentage(input) {
            const row = input.closest('tr');
            const fullMarkInput = row.querySelector('input[name="full_mark[]"]');
            const secureMarkInput = row.querySelector('input[name="secure_mark[]"]');
            const percentageInput = row.querySelector('input[name="percentage[]"]');

            const fullMark = parseFloat(fullMarkInput.value) || 0;
            const secureMark = parseFloat(secureMarkInput.value) || 0;

            if (fullMark > 0) {
                const percentage = (secureMark / fullMark) * 100;
                percentageInput.value = percentage.toFixed(2);
            } else {
                percentageInput.value = 0;
            }
        }
    </script>
</head>
<body>

<div class="container">
    <h2>Edit Faculty Member</h2>

    <!-- Edit Faculty Form -->
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="edit">
        <input type="hidden" name="faculty_id" value="<?php echo $faculty['id']; ?>">

        <!-- Personal Information -->
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($faculty['name']); ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($faculty['email']); ?>" required>
        </div>

        <div class="form-group">
            <label for="mobile">Mobile</label>
            <input type="text" name="mobile" id="mobile" value="<?php echo htmlspecialchars($faculty['mobile']); ?>" required>
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <textarea name="address" id="address" required><?php echo htmlspecialchars($faculty['address']); ?></textarea>
        </div>

        <div class="form-group">
            <label for="photo">Photo</label>
            <input type="file" name="photo" id="photo">
            <?php if (!empty($faculty['photo'])): ?>
                <img src="uploads/<?php echo htmlspecialchars($faculty['photo']); ?>" alt="Faculty Photo" width="100">
            <?php endif; ?>
        </div>

        <!-- Stream and Subject Information -->
        <div class="form-group">
            <label for="stream_id">Stream</label>
            <select name="stream_id" id="stream_id">
                <?php
                $streams = $conn->query("SELECT * FROM streams");
                while ($row = $streams->fetch_assoc()) {
                    $selected = ($faculty['stream_id'] == $row['id']) ? 'selected' : '';
                    echo "<option value='{$row['id']}' $selected>{$row['stream_name']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="subject_id">Subject</label>
            <select name="subject_id" id="subject_id">
                <?php
                $subjects = $conn->query("SELECT * FROM subjects");
                while ($row = $subjects->fetch_assoc()) {
                    $selected = ($faculty['subject_id'] == $row['id']) ? 'selected' : '';
                    echo "<option value='{$row['id']}' $selected>{$row['subject_name']}</option>";
                }
                ?>
            </select>
        </div>

        <h3>Qualifications</h3>
        <div id="qualification-container">
            <table>
                <thead>
                    <tr>
                        <th>Class</th>
                        <th>Board Name</th>
                        <th>Institute Name</th>
                        <th>Full Marks</th>
                        <th>Secured Marks</th>
                        <th>Percentage</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($qualifications): ?>
                        <?php while ($qual = $qualifications->fetch_assoc()): ?>
                            <div class="qualification-row">
                                <tr>
                                    <td><input type="text" name="class[]" value="<?php echo htmlspecialchars($qual['class']); ?>" required></td>
                                    <td><input type="text" name="board_name[]" value="<?php echo htmlspecialchars($qual['board_name']); ?>" required></td>
                                    <td><input type="text" name="institute_name[]" value="<?php echo htmlspecialchars($qual['institute_name']); ?>" required></td>
                                    <td><input type="number" name="full_mark[]" value="<?php echo htmlspecialchars($qual['full_mark']); ?>" required oninput="calculatePercentage(this)"></td>
                                    <td><input type="number" name="secure_mark[]" value="<?php echo htmlspecialchars($qual['secure_mark']); ?>" required oninput="calculatePercentage(this)"></td>
                                    <td><input type="number" name="percentage[]" value="<?php echo htmlspecialchars($qual['percentage']); ?>" readonly></td>
                                    <td><input type="hidden" name="qualification_id[]" value="<?php echo $qual['id']; ?>"><button type="button" onclick="removeQualificationRow(this)">Remove</button></td>
                                </tr>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td><input type="text" name="class[]" placeholder="Class" required></td>
                            <td><input type="text" name="board_name[]" placeholder="Board Name" required></td>
                            <td><input type="text" name="institute_name[]" placeholder="Institute Name" required></td>
                            <td><input type="number" name="full_mark[]" placeholder="Full Marks" required oninput="calculatePercentage(this)"></td>
                            <td><input type="number" name="secure_mark[]" placeholder="Secured Marks" required oninput="calculatePercentage(this)"></td>
                            <td><input type="number" name="percentage[]" placeholder="Percentage" readonly></td>
                            <td><button type="button" onclick="removeQualificationRow(this)">Remove</button></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <button type="button" onclick="addQualificationRow()">Add Qualification</button>
        <button type="submit">Save Changes</button>
    </form>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
