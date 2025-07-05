<?php
include "connect.php";

if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $id = intval($_GET["id"]); // Ensure it's an integer

    // Fetch the committee report title and attachment before deleting
    $fetch_sql = "SELECT title, attachment_path FROM committee_reports WHERE id = ?";
    $fetch_stmt = mysqli_prepare($conn, $fetch_sql);
    
    if ($fetch_stmt) {
        mysqli_stmt_bind_param($fetch_stmt, "i", $id);
        mysqli_stmt_execute($fetch_stmt);
        mysqli_stmt_bind_result($fetch_stmt, $title, $attachment_path);
        mysqli_stmt_fetch($fetch_stmt);
        mysqli_stmt_close($fetch_stmt);
    }

    if (!empty($title)) {
        // Delete file if it exists
        if (!empty($attachment_path) && file_exists($attachment_path)) {
            unlink($attachment_path); // Delete the uploaded file
        }

        // Proceed with deletion from database
        $sql = "DELETE FROM committee_reports WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);

            if (mysqli_stmt_affected_rows($stmt) > 0) {
                // Log the deletion
                $log_sql = "INSERT INTO history_log (action, file_type, file_id, title) 
                            VALUES ('Deleted', 'Committee Report', ?, ?)";
                $log_stmt = mysqli_prepare($conn, $log_sql);
                mysqli_stmt_bind_param($log_stmt, "is", $id, $title);
                mysqli_stmt_execute($log_stmt);
                mysqli_stmt_close($log_stmt);

                header("Location: files-committee-report.php?msg=Committee Report '$title' deleted successfully");
                exit();
            } else {
                echo "Failed: Report not found.";
            }

            mysqli_stmt_close($stmt);
        } else {
            echo "Failed: " . mysqli_error($conn);
        }
    } else {
        echo "Failed: Report title not found.";
    }
} else {
    echo "Invalid or missing Report ID.";
}
?>
