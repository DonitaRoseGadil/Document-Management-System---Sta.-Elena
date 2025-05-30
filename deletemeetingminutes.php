<?php
    include "connect.php";
    

    if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
        $id = intval($_GET["id"]); // Ensure it's an integer

        // Fetch the title before deleting
        $fetch_sql = "SELECT title FROM minutes WHERE id = ?";
        $fetch_stmt = mysqli_prepare($conn, $fetch_sql);

        if ($fetch_stmt) {
            mysqli_stmt_bind_param($fetch_stmt, "i", $id);
            mysqli_stmt_execute($fetch_stmt);
            mysqli_stmt_bind_result($fetch_stmt, $title);
            mysqli_stmt_fetch($fetch_stmt);
            mysqli_stmt_close($fetch_stmt);
        }

        if (!empty($title)) {
            // Delete statement
            $sql = "DELETE FROM minutes WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "i", $id);
                mysqli_stmt_execute($stmt);

                if (mysqli_stmt_affected_rows($stmt) > 0) {
                    // Log the deletion
                    $log_sql = "INSERT INTO history_log (action, file_type, file_id, title) 
                                VALUES ('Deleted', 'Minutes', ?, ?)";
                    $log_stmt = mysqli_prepare($conn, $log_sql);
                    mysqli_stmt_bind_param($log_stmt, "is", $id, $title);
                    mysqli_stmt_execute($log_stmt);
                    mysqli_stmt_close($log_stmt);

                    header("Location: files-meetingminutes.php?msg=Minute '$title' deleted successfully");
                    exit();
                } else {
                    echo "Failed: Minute not found.";
                }

                mysqli_stmt_close($stmt);
            } else {
                echo "Failed: " . mysqli_error($conn);
            }
        } else {
            echo "Failed: Minute title not found.";
        }
    } else {
        echo "Invalid or missing minute ID.";
    }
?>
