<?php
require 'connect.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $title = '';
    $getTitle = $conn->prepare("SELECT title FROM sections WHERE id = ?");
    $getTitle->bind_param("i", $id);
    $getTitle->execute();
    $getTitle->bind_result($title);
    $getTitle->fetch();
    $getTitle->close();

    if (empty($title)) {
        echo "Failed: Section not found.";
        exit;
    }

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

        $log_sql = "INSERT INTO history_log (action, file_type, file_id, title) 
                    VALUES ('Deleted', 'Rules', ?, ?)";
        $log_stmt = $conn->prepare($log_sql);
        $log_stmt->bind_param("is", $id, $title);
        $log_stmt->execute();
        $log_stmt->close();

        $conn->close();
        header("Location: files-rules.php?msg=Rule '$title' deleted successfully");
        exit;
    } else {
        echo "
        Failed to delete: " . $stmt3->error;
    }

    $stmt3->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
