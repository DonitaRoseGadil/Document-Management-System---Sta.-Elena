<?php
    include "session.php";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $entered_password = $_POST["password"] ?? '';

        // Secure query using prepared statement to get the password for the "master" role
        $sql = "SELECT password FROM accounts WHERE role = 'master' LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if a master account exists
        if ($row = $result->fetch_assoc()) {
            $hashed_password = $row["password"];

            // Verify password using password_verify()
            if (password_verify($entered_password, $hashed_password)) {
                echo json_encode(["success" => true]);
            } else {
                echo json_encode(["success" => false, "message" => "Incorrect password. Try again."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "No master account found."]);
        }

        $stmt->close();
        $conn->close();
    }
?>
