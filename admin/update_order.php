<?php
include '../db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_order = $_POST['order'] ?? [];

    // Log the received data to ensure it's being received properly
    error_log(print_r($new_order, true));

    // Now process the data
    function updateMenuOrder($items, $parent_id = NULL) {
        global $conn;
        foreach ($items as $index => $item) {
            $menu_id = $item['id'];
            $order = $index + 1; // Start from 1
            $stmt = $conn->prepare("UPDATE menus SET menu_order = ?, parent_id = ? WHERE id = ?");
            $stmt->bind_param("iii", $order, $parent_id, $menu_id);
            $stmt->execute();
            $stmt->close();

            if (!empty($item['children'])) {
                updateMenuOrder($item['children'], $menu_id); // Recursively update submenus
            }
        }
    }

    updateMenuOrder($new_order);
    echo json_encode(['status' => 'success']);
}
?>