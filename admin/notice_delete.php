<?php
include('../db.php');
include 'header.php';

// Check if the notice ID is provided
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
        $notice_file = $notice['notice_file']; // Store the file path to delete later
    } else {
        echo "Notice not found.";
        exit;
    }

    // Attempt to delete the file if it exists
    if (file_exists($notice_file)) {
        if (unlink($notice_file)) {
            echo "File deleted successfully.<br>";
        } else {
            echo "Failed to delete the file.<br>";
        }
    } else {
        echo "File not found.<br>";
    }

    // Delete the notice record from the database
    $delete_sql = "DELETE FROM notice WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $notice_id);

    if ($delete_stmt->execute()) {
        echo "Notice deleted successfully.";
    } else {
        echo "Error deleting notice: " . $delete_stmt->error;
    }
} else {
    echo "No notice ID provided.";
    exit;
}
?>

