<?php
// Include the database connection
include 'db.php';
include 'header.php';
// Get the faculty ID from the URL
if (isset($_GET['id'])) {
    $faculty_id = intval($_GET['id']); // Ensure the ID is an integer

    // Fetch faculty details along with stream and subject names
    $faculty_sql = "
        SELECT f.*, s.stream_name, sub.subject_name 
        FROM faculty f 
        LEFT JOIN streams s ON f.stream_id = s.id 
        LEFT JOIN subjects sub ON f.subject_id = sub.id 
        WHERE f.id = ?";
    $stmt = $conn->prepare($faculty_sql);
    $stmt->bind_param("i", $faculty_id);
    $stmt->execute();
    $faculty_result = $stmt->get_result();

    // Fetch qualifications
    $qualification_sql = "SELECT * FROM faculty_qualifications WHERE faculty_id = ?";
    $stmt_qual = $conn->prepare($qualification_sql);
    $stmt_qual->bind_param("i", $faculty_id);
    $stmt_qual->execute();
    $qualifications_result = $stmt_qual->get_result();
    
    // Check if faculty exists
    if ($faculty_result->num_rows > 0) {
        $faculty = $faculty_result->fetch_assoc();
    } else {
        echo "Faculty not found.";
        exit;
    }
} else {
    echo "Invalid faculty ID.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Details</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="container">
        <h1>Faculty Profile</h1>
        <table>
            <tr>
                <th>Name</th>
                <td><?php echo htmlspecialchars($faculty['name']); ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo htmlspecialchars($faculty['email']); ?></td>
            </tr>
            <tr>
                <th>Mobile</th>
                <td><?php echo htmlspecialchars($faculty['mobile']); ?></td>
            </tr>
            <tr>
                <th>Address</th>
                <td><?php echo htmlspecialchars($faculty['address']); ?></td>
            </tr>
            <tr>
                <th>Photo</th>
                <td>
                    <?php if (!empty($faculty['photo'])): ?>
                        <img src="uploads/<?php echo htmlspecialchars($faculty['photo']); ?>" alt="Faculty Photo" style="width: 100px;">
                    <?php else: ?>
                        No Photo Available
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th>Stream</th>
                <td><?php echo htmlspecialchars($faculty['stream_name']); ?></td> <!-- Display stream name -->
            </tr>
            <tr>
                <th>Subject</th>
                <td><?php echo htmlspecialchars($faculty['subject_name']); ?></td> <!-- Display subject name -->
            </tr>
        </table>

        <h2>Qualifications</h2>
        <table>
            <thead>
                <tr>
                    <th>Class</th>
                    <th>Board Name</th>
                    <th>Institute Name</th>
                    <th>Full Marks</th>
                    <th>Secured Marks</th>
                    <th>Percentage</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($qualifications_result->num_rows > 0): ?>
                    <?php while ($qualification = $qualifications_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($qualification['class']); ?></td>
                            <td><?php echo htmlspecialchars($qualification['board_name']); ?></td>
                            <td><?php echo htmlspecialchars($qualification['institute_name']); ?></td>
                            <td><?php echo htmlspecialchars($qualification['full_mark']); ?></td>
                            <td><?php echo htmlspecialchars($qualification['secure_mark']); ?></td>
                            <td><?php echo htmlspecialchars($qualification['percentage']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No qualifications found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <a href="faculty.php">Back to Faculty List</a> <!-- Link back to the faculty list -->
    </div>
</body>
</html>

<?php
// Close the prepared statements and the connection
$stmt->close();
$stmt_qual->close();
$conn->close();
?>
