<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'connect.php';
header('Content-Type: application/json');

$response = ['exists' => false];

$titleInput = $_POST['title'] ?? '';

if (!empty($titleInput)) {
    $titleNormalized = strtolower(trim($titleInput));

    // Use COLLATE for case-insensitive comparison
    $stmt = $conn->prepare("SELECT * FROM committee_reports WHERE LOWER(TRIM(title)) = ? LIMIT 1");
    $stmt->bind_param("s", $titleNormalized);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $response['exists'] = true;
        $response['title'] = $row['title'];
        $response['committee_category'] = $row['committee_category'];
        $response['committee_section'] = $row['committee_section'];
        $response['councilor'] = $row['councilor'];
        $response['date_report'] = $row['date_report'];
        $response['attachment_path'] = $row['attachment_path'];
    }

    $stmt->close();
}

echo json_encode($response);
