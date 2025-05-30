<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'connect.php';
header('Content-Type: application/json');

$response = ['exists' => false];

$resoNoInput = $_POST['reso_no'] ?? '';

// Normalization function — keep alphanumerics and parentheses
function normalize($str) {
    $str = strtolower(trim($str));
    $str = str_replace(',', '', $str);
    $str = preg_replace('/\s+/', '', $str); // remove spaces
    return preg_replace('/[^a-z0-9\(\)]/', '', $str); // keep a-z, 0-9, and ()
}

// Match number, year, optional "(Book 1)" or "(Book 2)"
//preg_match('/(\d+[a-zA-Z\-]*)\s*(?:s\.?|[-])?\s*(\d{4})\s*(\(.*?\))?/i', $resoNoInput, $matches);
preg_match('/(\d+[a-zA-Z\-]*)\s*(?:[, ]*\s*s\.?|[-])?\s*(\d{4})\s*(\(.*?\))?/i', $resoNoInput, $matches);

if (isset($matches[1], $matches[2])) {
    $number = strtolower(trim($matches[1]));
    $year = $matches[2];
    $suffix = isset($matches[3]) ? strtolower(trim($matches[3])) : '';

    // Normalize input, including suffix
    $inputNormalized = normalize($number . $year . $suffix);

    $sql = "SELECT * FROM resolution";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $dbReso = $row['reso_no'];

        // preg_match('/(\d+[a-zA-Z\-]*)\s*(?:s\.?|[-])?\s*(\d{4})\s*(\(.*?\))?/i', $dbReso, $dbMatches);
        preg_match('/(\d+[a-zA-Z\-]*)\s*(?:[, ]*\s*s\.?|[-])?\s*(\d{4})\s*(\(.*?\))?/i', $dbReso, $dbMatches);

        if (isset($dbMatches[1], $dbMatches[2])) {
            $dbNumber = strtolower(trim($dbMatches[1]));
            $dbYear = $dbMatches[2];
            $dbSuffix = isset($dbMatches[3]) ? strtolower(trim($dbMatches[3])) : '';

            $dbNormalized = normalize($dbNumber . $dbYear . $dbSuffix);

            if ($inputNormalized === $dbNormalized) {
                $response['exists'] = true;
                $response['title'] = $row['title'];
                $response['dateAdopted'] = $row['d_adopted'];
                $response['authorSponsor'] = $row['author_sponsor'];
                $response['coAuthor'] = $row['co_author'];
                $response['remarks'] = $row['remarks'];
                $response['dateForwarded'] = $row['d_forward'];
                $response['dateSigned'] = $row['d_signed'];
                $response['dateApproved'] = $row['d_approved'];
                $response['attachment'] = $row['attachment'];
                $response['notes'] = $row['notes'];
                break;
            }
        }
    }
}

echo json_encode($response);
?>
