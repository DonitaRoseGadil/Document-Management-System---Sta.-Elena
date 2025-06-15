<?php
require 'connect.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // First delete subcontents
    $stmt1 = $conn->prepare("DELETE FROM subcontents WHERE content_id IN (SELECT id FROM contents WHERE section_id = ?)");
    $stmt1->bind_param("i", $id);
    $stmt1->execute();
    $stmt1->close();

    // Then delete contents
    $stmt2 = $conn->prepare("DELETE FROM contents WHERE section_id = ?");
    $stmt2->bind_param("i", $id);
    $stmt2->execute();
    $stmt2->close();

    // Then delete section
    $stmt3 = $conn->prepare("DELETE FROM sections WHERE id = ?");
    $stmt3->bind_param("i", $id);

    if ($stmt3->execute()) {
        $stmt3->close();
        $conn->close();
        header("Location: files-rules.php?deleted=true");
        exit;
    } else {
        echo "Failed to delete: " . $stmt3->error;
    }

    $stmt3->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
