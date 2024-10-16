<?php
include('db.php');
include 'header.php';

// Number of notices to show per page
$limit = 10;

// Get the current page or set a default page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Fetch the total number of notices
$sql_total = "SELECT COUNT(id) AS total FROM notice";
$result_total = $conn->query($sql_total);
$total = $result_total->fetch_assoc()['total'];

// Calculate total pages
$total_pages = ceil($total / $limit);

// Fetch notices for display with pagination
$notices = [];
$sql = "SELECT * FROM notice ORDER BY id DESC LIMIT $start, $limit";
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
    <title>Notice Board</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: brown;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f4f4f9;
            border-radius: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border-radius: 6px;
        }
        table, th, td {
            border: 2px solid blue;
        }
        th, td {
            text-align: center;
            padding: 5px 4px 6px 4px;
            text-align: left;
            vertical-align: top;
            border-left: 1px solid #ddd;
        }
        th {
            color: red;
            background-color: yellow;
        }
        .pagination {
            display: flex;
            justify-content: center;
            list-style-type: none;
            padding: 0;
        }
        .pagination li {
            margin: 0 5px;
        }
        .pagination a {
            text-decoration: none;
            padding: 10px 15px;
            background-color: #5c67f5;
            color: white;
            border-radius: 5px;
        }
        .pagination a:hover {
            background-color: #4b56e2;
        }
        .pagination .disabled {
            background-color: #ddd;
            color: #666;
            cursor: not-allowed;
        }
        .download-icon {
            display: flex;
            align-items: center;
        }
        .download-icon i {
            margin-right: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Notice Board</h2>

    <table>
        <tr>
            <th>Notice Number</th>
            <th>Notice Name</th>
            <th>Notice Date</th>
            <th>Download</th>
        </tr>
        <?php foreach ($notices as $notice): ?>
            <tr>
                <td><?php echo $notice['notice_number']; ?></td>
                <td><?php echo $notice['notice_name']; ?></td>
                <td><?php echo $notice['notice_date']; ?></td>
                <td>
                    <?php
                    // Get file extension
                    $file_extension = pathinfo($notice['notice_file'], PATHINFO_EXTENSION);
                    $file_path = "admin/" . $notice['notice_file']; // Assuming admin directory holds files

                    // Determine the icon to show based on the file type
                    if ($file_extension == 'pdf') {
                        echo '<div class="download-icon"><i class="fas fa-file-pdf" style="color:red;"></i> <a href="' . $file_path . '" target="_blank">Download PDF</a></div>';
                    } elseif (in_array($file_extension, ['jpg', 'jpeg', 'png'])) {
                        echo '<div class="download-icon"><i class="fas fa-image" style="color:green;"></i> <a href="' . $file_path . '" target="_blank">Download Image</a></div>';
                    } elseif (in_array($file_extension, ['doc', 'docx'])) {
                        echo '<div class="download-icon"><i class="fas fa-file-word" style="color:blue;"></i> <a href="' . $file_path . '" target="_blank">Download Word Document</a></div>';
                    } elseif ($file_extension == 'xlsx') {
                        echo '<div class="download-icon"><i class="fas fa-file-excel" style="color:green;"></i> <a href="' . $file_path . '" target="_blank">Download Excel</a></div>';
                    } elseif ($file_extension == 'pptx') {
                        echo '<div class="download-icon"><i class="fas fa-file-powerpoint" style="color:orange;"></i> <a href="' . $file_path . '" target="_blank">Download PowerPoint</a></div>';
                    } else {
                        echo '<div class="download-icon"><i class="fas fa-file" style="color:gray;"></i> <a href="' . $file_path . '" target="_blank">Download File</a></div>';
                    }
                    ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <!-- Pagination Links -->
    <ul class="pagination">
        <?php if ($page > 1): ?>
            <li><a href="?page=<?php echo $page - 1; ?>">Prev</a></li>
        <?php else: ?>
            <li><a href="#" class="disabled">Prev</a></li>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li><a href="?page=<?php echo $i; ?>" <?php if ($i == $page) echo 'style="background-color: #4b56e2;"'; ?>><?php echo $i; ?></a></li>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
            <li><a href="?page=<?php echo $page + 1; ?>">Next</a></li>
        <?php else: ?>
            <li><a href="#" class="disabled">Next</a></li>
        <?php endif; ?>
    </ul>
</div>

</body>
</html>
