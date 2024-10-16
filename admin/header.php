<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="styles.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg custom-navbar">
            <div class="container-fluid">
                <a class="navbar-brand custom-brand" href="index.php">Admin Panel</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link custom-link" href="index.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link custom-link" href="menu.php">Menus</a></li>
						<li class="nav-item"><a class="nav-link custom-link" href="notice.php">Notice</a></li>
                        <li class="nav-item"><a class="nav-link custom-link" href="slideshow.php">Slideshow</a></li>
                        <li class="nav-item"><a class="nav-link custom-link" href="stream.php">Streams</a></li>
                        <li class="nav-item"><a class="nav-link custom-link" href="subject.php">Subjects</a></li>
                        <li class="nav-item"><a class="nav-link custom-link" href="faculty.php">Faculty</a></li>
                        <li class="nav-item"><a class="nav-link custom-link" href="student.php">Students</a></li>
                        <li class="nav-item"><a class="nav-link custom-link" href="website_admin.php">Website Settings</a></li>
                        <li class="nav-item"><a class="nav-link custom-link custom-link-logout" href="logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main class="container mt-4">
        <!-- Page content starts here -->
