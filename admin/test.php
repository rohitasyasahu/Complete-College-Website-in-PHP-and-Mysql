<?php
include('db.php'); // Database connection

// Admin credentials
$username = 'Deepak';
$password = 'Deepak@123';

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// SQL to insert admin credentials
$sql = "INSERT INTO admins (username, password) VALUES ('$username', '$hashed_password')";

if (mysqli_query($conn, $sql)) {
    echo "Admin user created successfully.";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn); // Close the database connection
?>
