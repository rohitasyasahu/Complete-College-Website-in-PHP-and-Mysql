<?php
include('db.php');
include 'header.php';
// Fetch slideshow images from the database
$images = [];
$sql = "SELECT * FROM slideshow ORDER BY id DESC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $images[] = $row;
    }
}

$notices = [];
$sql = "SELECT * FROM notice ORDER BY id DESC LIMIT 10";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $notices[] = $row;
    }
}
?>
<title>College Homepage</title>
<style>
    /* Slideshow styles */
    .slideshow-container {
        max-width: 100%;
        max-height: 600px;
        position: relative;
        margin: auto;
        overflow: hidden;
    }

    .slides {
        display: none;
        width: 100%;
    }

    .prev, .next {
        cursor: pointer;
        position: absolute;
        top: 50%;
        width: auto;
        padding: 16px;
        margin-top: -22px;
        color: white;
        font-weight: bold;
        font-size: 18px;
        transition: 0.6s ease;
        border-radius: 0 3px 3px 0;
        user-select: none;
    }

    .next {
        right: 0;
        border-radius: 3px 0 0 3px;
    }

    .prev {
        left: 0;
    }

    .slideshow-container img {
        width: 100%;
    }

    .slideshow-title {
        text-align: center;
        font-size: 22px;
        font-weight: bold;
        margin-top: 10px;
    }

    .slideshow-description {
        text-align: center;
        margin-bottom: 20px;
    }

    /* Main content */
    .main-content {
        width: 75%;
        padding: 20px;
    }

    .notice-board {
		width: 200px; /* Adjusted width */
		height: 300px; /* Adjusted height */
		background-color: #ffeb3b;
		border: 2px solid #333;
		padding: 10px;
		border-radius: 8px;
		overflow: hidden; /* Hides scrollbar */
		position: relative;
		}

        .notice-board h3 {
        font-size: 20px;
        text-align: center;
        background-color: #333;
        color: #fff;
        padding: 10px;
        margin: 0;
        border-radius: 5px 5px 0 0;
        position: relative;
        z-index: 1;
        }
        .notice-list-container {
            position: absolute;
            top: 60px; /* Adjust according to the height of h3 */
            left: 0;
            right: 0;
            bottom: 0;
            overflow: hidden; /* Ensure it hides overflow */
        }
        .notice-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
            position: absolute;
            animation: scroll 20s linear infinite; /* Animation for scrolling */
        }
        .notice-item {
            background-color: #fff;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .notice-item a {
            text-decoration: none;
            color: #5c67f5;
            font-weight: bold;
        }
        @keyframes scroll {
            0% { top: 100%; }
            100% { top: -100%; }
        }
		.container {
    display:flex ;
    flex-direction: row;
    justify-content: space-between;
    margin: 20px;
}
</style>

<!-- Slideshow Section -->
<div class="slideshow-container">
    <?php foreach ($images as $index => $image): ?>
        <div class="slides">
            <img src="admin/<?php echo $image['image_path']; ?>" alt="Slideshow Image">
            <?php if (!empty($image['title'])): ?>
                <div class="slideshow-title"><?php echo $image['title']; ?></div>
            <?php endif; ?>
            <?php if (!empty($image['description'])): ?>
                <div class="slideshow-description"><?php echo $image['description']; ?></div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <!-- Navigation arrows -->
    <a class="prev" onclick="changeSlide(-1)">&#10094;</a>
    <a class="next" onclick="changeSlide(1)">&#10095;</a>
</div>

<!-- Notice Board Section -->

    <div class="container">
        <div class="left-sidebar">
            <!-- Left Sidebar Content -->
            <h2>Left Sidebar</h2>
            <p>Some sidebar content.</p>
        </div>
        <div class="main-content">
            <!-- Main Content -->
            <h2>Main Content</h2>
            <p>Here’s where the main content goes.</p>
        </div>
        <div class="right-sidebar">
            <!-- Right Sidebar Content -->
            <div class="notice-board">
                <h3>Notice Board</h3>
                <div class="notice-list-container">
                    <div class="notice-list">
                        <?php if (count($notices) > 0): ?>
                            <?php foreach ($notices as $notice): ?>
                                <div class="notice-item">
                                    <a href="admin/<?php echo $notice['notice_file']; ?>" target="_blank">
                                        <?php echo $notice['notice_name']; ?>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No notices available</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    
<!-- Main Content Section -->
<main>
       
    </main>
	

<script>
    let slideIndex = 0;
    showSlides();

    function showSlides() {
        let slides = document.getElementsByClassName("slides");
        for (let i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        slideIndex++;
        if (slideIndex > slides.length) {slideIndex = 1}
        slides[slideIndex-1].style.display = "block";
        setTimeout(showSlides, 4000); // Change image every 4 seconds
    }

    function changeSlide(n) {
        let slides = document.getElementsByClassName("slides");
        slideIndex += n;
        if (slideIndex > slides.length) {slideIndex = 1}
        if (slideIndex < 1) {slideIndex = slides.length}
        for (let i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        slides[slideIndex-1].style.display = "block";
    }
</script>