<?php
include('db.php');

// Fetch notices from the database
$notices = [];
$sql = "SELECT * FROM notice ORDER BY id DESC LIMIT 10";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $notices[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Homepage</title>
    <style>
		.notice-board {
    width: 250px; /* Adjusted width */
    height: 300px; /* Adjusted height */
    background-color: #ffeb3b;
    border: 2px solid #333;
    padding: 10px;
    border-radius: 5px;
    overflow: hidden; /* Hides scrollbar */
    position: absolute; /* Changed to absolute positioning */
    top: 50px; /* Adjust as needed */
    right: 10px; /* Shifted to the right */
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
    </style>
</head>
<body>
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
</body>
</html>
