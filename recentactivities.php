<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "lgu_dms");

if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed"]));
}

// Fetch recent activities from resolution, ordinance, and minutes
$sql = "SELECT * FROM history_log ORDER BY timestamp DESC LIMIT 5";
$result = $conn->query($sql);

$activities = [];
while ($row = $result->fetch_assoc()) {
    $activities[] = [
        "action" => $row["action"],
        "file_type" => $row["file_type"],
        "title" => $row["title"],
        "timestamp" => $row["timestamp"]
    ];
}

echo json_encode(["activities" => $activities]);

$conn->close();
?>
