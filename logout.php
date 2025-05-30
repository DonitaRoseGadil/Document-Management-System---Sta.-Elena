<?php
    session_start();
    require 'connect.php';

    if (isset($_SESSION['user_id'])) {
        $stmt = $conn->prepare("UPDATE accounts SET token = NULL WHERE id = ?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
    }

    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
?>