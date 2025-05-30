<?php
    include "session.php";
    header('Content-Type: application/json');


    if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
        $id = intval($_GET["id"]);

        $sql = "SELECT * FROM `accounts` WHERE id = $id LIMIT 1";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            echo json_encode([
                'success' => true,
                'id' => $row['id'],
                'email' => $row['email'],
                'account_status' => $row['account_status'],
                'role' => $row['role']
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Record not found.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid or missing ID.']);
    }
?>
