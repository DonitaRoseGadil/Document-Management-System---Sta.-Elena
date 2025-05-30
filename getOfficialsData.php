<?php
    // get_org_chart_data.php

    header('Content-Type: application/json');
    include 'connect.php'; // Make sure $conn is defined inside this file

    // Get all officials
    $sql = "SELECT * FROM officials";
    $result = $conn->query($sql);

    // Check for query errors
    if (!$result) {
        echo json_encode(["error" => "Query failed: " . $conn->error]);
        exit;
    }

    $data = [];

    while($row = $result->fetch_assoc()) {
        $data[] = [
            "id" => $row["id"],
            "parentId" => $row["parentId"] !== null ? $row["parentId"] : null,
            "name" => $row["firstname"] . " " . $row["middlename"] . " " . $row["surname"],
            "position" => $row["position"],
            "photo" => $row["photo_path"]
        ];
    }

    echo json_encode($data);

    $conn->close();
?>
