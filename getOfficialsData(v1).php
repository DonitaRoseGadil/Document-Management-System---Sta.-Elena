<?php


if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // sanitize

    $stmt = $conn->prepare("SELECT * FROM officials WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $official = $result->fetch_assoc();

    // Send JSON response
    header('Content-Type: application/json');
    echo json_encode($official);
}
?>
