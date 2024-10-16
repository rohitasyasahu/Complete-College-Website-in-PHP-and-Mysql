<?php
if ($_FILES['file']['name']) {
    $filename = $_FILES['file']['name'];
    $location = "uploads/" . $filename;
    if (move_uploaded_file($_FILES['file']['tmp_name'], $location)) {
        echo json_encode(['location' => $location]);
    } else {
        echo json_encode(['error' => 'File upload failed']);
    }
} else {
    echo json_encode(['error' => 'No file uploaded']);
}
?>
