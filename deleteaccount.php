<?php
include "connect.php";

if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $id = intval($_GET["id"]); // Ensure it's an integer

    // Delete statement
    $sql = "DELETE FROM accounts WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            header("Location: manageAccounts.php?msg=Account deleted successfully");
            exit();
        } else {
            echo "Failed: Account not found.";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Failed: " . mysqli_error($conn);
    }
} else {
    echo "Invalid or missing account ID.";
}
?>