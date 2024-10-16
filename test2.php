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
?>
<title>College Homepage</title>
<style>
        .slideshow-container {
            max-width: ;
			max-height:600px;
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
		/* Notice board container */
        .notice-board {
            position: ;
            top: 50px;
            right: 10px;
            width: 300px;
            background-color: #ffeb3b;
            border: 2px solid #333;
            padding: 10px;
            border-radius: 5px;
            overflow: hidden;
            height: 400px;
        }
        .notice-board h2 {
            font-size: 20px;
            text-align: center;
            background-color: #333;
            color: #fff;
            padding: 10px;
            margin: 0;
            border-radius: 5px 5px 0 0;
        }
        .notice-list {
            list-style-type: none;
            padding: 0;
            margin: 10px 0;
            overflow-y: hidden;
            height: 320px;
            position: relative;
        }
        .notice-list li {
            background-color: #fff;
            padding: 10px;
            margin: 5px 0;
            border-bottom: 1px solid #ddd;
        }
        /* Scrolling effect */
        .scroll {
            position: absolute;
            animation: scrollUp 15s linear infinite;
        }
        @keyframes scrollUp {
            0% {
                top: 100%;
            }
            100% {
                top: -100%;
            }
        }
    </style>
<!-- Main Content Section -->
	
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
<main>
        
			<div class="notice-board">
    <h2>Notice Board</h2>
    <ul class="notice-list">
        <?php
            $sql = "SELECT * FROM notice ORDER BY id DESC LIMIT 5"; // Fetch fewer notices
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($notice = $result->fetch_assoc()) {
                    echo '<li><strong>' . htmlspecialchars($notice['notice_name']) . ':</strong> ' . 
                         htmlspecialchars($notice['notice_date']) . '</li>';
                }
            }
            ?>
    </ul>
</div>
    </main>
	
	

	
	
	
	
	
<script>
    // JavaScript to handle scrolling
    let noticeList = document.querySelector('.notice-list');
    let scrollElement = document.createElement('div');
    scrollElement.classList.add('scroll');
    scrollElement.innerHTML = noticeList.innerHTML; // Duplicate the notice list content
    noticeList.appendChild(scrollElement); // Add it inside the list

    // Start animation when DOM is loaded
    window.onload = function () {
        scrollElement.style.top = '100%';
    };
</script>	
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