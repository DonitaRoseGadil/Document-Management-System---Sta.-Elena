<?php
include "connect.php";

header("Content-Type: application/json"); // Ensure JSON response
error_reporting(E_ALL);
ini_set("display_errors", 1);

$response = [];

    if (!isset($_GET["file_type"])) {
        echo json_encode(["error" => "Missing file type"]);
        exit;
    }

    $file_type = $_GET["file_type"];

    // If the file type is "Rules", no need for ID
    if ($file_type === "Rules") {
        $sql = "SELECT id, file_id, file_type, status, timestamp, title, action 
                FROM history_log 
                WHERE file_type = ? 
                ORDER BY timestamp DESC LIMIT 3";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $file_type);
    } 
    // For all other file types, require file_id
    else if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
        $file_id = intval($_GET["id"]);

        $sql = "SELECT id, file_id, file_type, status, timestamp, title, action 
                FROM history_log 
                WHERE file_type = ? AND file_id = ? 
                ORDER BY timestamp DESC";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $file_type, $file_id);
    } 
    else {
        echo json_encode(["error" => "Missing or invalid file ID"]);
        exit;
    }

    if ($stmt) {
        $stmt->execute();
        $result = $stmt->get_result();

        $response = [];
        while ($row = $result->fetch_assoc()) {
            $formattedDate = date("F j, Y \\a\\t g:i A", strtotime($row["timestamp"]));
            $response[] = [
                "id" => $row["id"],
                "file_id" => $row["file_id"],
                "file_type" => $row["file_type"],
                "status" => $row["status"],
                "title" => $row["title"],
                "action" => $row["action"],
                "timestamp" => $formattedDate
            ];
        }

        echo json_encode($response);
    } else {
        echo json_encode(["error" => "SQL Error: " . $conn->error]);
    }


?>
