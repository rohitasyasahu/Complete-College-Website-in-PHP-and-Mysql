<?php
include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    
</head>
<body>
    <header>
        <!-- Include your header.php here -->
    </header>
    <main class="container">

        <!-- Cards Section -->
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Manage Students</div>
                    <div class="card-body">
                        <p>Quick access to student information.</p>
                        <a href="subject.php" class="btn">Go to Subjects</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Manage Faculty</div>
                    <div class="card-body">
                        <p>Quick access to faculty information.</p>
                        <a href="faculty.php" class="btn">Go to Faculty</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Manage Courses</div>
                    <div class="card-body">
                        <p>Quick access to course information.</p>
                        <a href="menu.php" class="btn">Go to Courses</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerts Section -->
        <div class="alert alert-success" role="alert">
            Welcome to the Admin Dashboard! You can manage all aspects of the college here.
        </div>

        <div class="alert alert-danger" role="alert">
            Please ensure all data is entered correctly.
        </div>

        <!-- Footer Section -->
        
    </main>
	<?php include 'footer.php'; ?>
</body>
</html>
