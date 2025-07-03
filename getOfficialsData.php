<?php
    include

    $result = $conn->query("SELECT id, parentId, name, positionName, imageUrl FROM my_org_chart");
    $data = [];

    while ($row = $result->fetch_assoc()) {
    $data[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($data);
?>