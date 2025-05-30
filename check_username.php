<?php
// check_username.php
require 'connect.php'; // Adjust as necessary

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    
    $stmt = $conn->prepare("SELECT * FROM accounts WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    echo $stmt->num_rows > 0 ? 'exists' : 'not_exists';
    
    $stmt->close();
}
?>
