<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'connect.php';
header('Content-Type: application/json');

$response = ['exists' => false];

// Input value from the frontend
$moNoInput = $_POST['mo_no'] ?? '';

// Normalize format: lowercase, trim, remove unnecessary characters (except number/year pattern)
function normalize($str) {
    return preg_replace('/[^0-9\-]/', '', strtolower(trim($str)));
}

// Extract number-year pairs from input
// Matches formats like: No. 001-2025, No. 43 s.2024, AO No. 5-2024, Resolution No.043.2023
preg_match_all('/(?:reso(?:lution)?|mo|ao|municipal ordinance)?\s*no\.?\s*(\d{1,4})[\s\-\/\.s]*([12][0-9]{3})/i', $moNoInput, $inputMatches, PREG_SET_ORDER);

// Normalize extracted patterns
$normalizedInput = [];
foreach ($inputMatches as $match) {
    // Remove leading zeros in number part
    $normalizedInput[] = normalize(ltrim($match[1], '0') . '-' . $match[2]);
}

if (!empty($normalizedInput)) {
    $sql = "SELECT * FROM ordinance";
    $result = $conn->query($sql);

    if (!$result) {
        echo json_encode([
            'error' => true,
            'message' => 'Database query failed: ' . $conn->error
        ]);
        exit;
    }

    while ($row = $result->fetch_assoc()) {
        $dbMoNo = $row['mo_no'] ?? '';

        // Extract number-year patterns from DB `mo_no` field
        preg_match_all('/(?:reso(?:lution)?|mo|ao|municipal ordinance)?\s*no\.?\s*(\d{1,4})[\s\-\/\.s]*([12][0-9]{3})/i', $dbMoNo, $dbMatches, PREG_SET_ORDER);
        
        $normalizedDB = [];
        foreach ($dbMatches as $match) {
            $normalizedDB[] = normalize(ltrim($match[1], '0') . '-' . $match[2]);
        }

        // Match if all normalized input patterns exist in the DB entry
        $allMatch = !array_diff($normalizedInput, $normalizedDB);

        if ($allMatch) {
            $response['exists'] = true;
            $response['title'] = trim($row['title']);
            $response['dateAdopted'] = $row['date_adopted'];
            $response['authorSponsor'] = $row['author_sponsor'];
            $response['remarks'] = $row['remarks'];
            $response['dateForwarded'] = $row['date_fwd'];
            $response['dateSigned'] = $row['date_signed'];
            $response['dateApproved'] = $row['sp_approval'];
            $response['spResoNo'] = $row['sp_resoNo'];
            $response['attachment'] = $row['attachment'];
            $response['notes'] = $row['notes'];
            break;
        }
    }
}

echo json_encode($response);
?>
