<?php

include "db.php";
header("Content-Type: application/json");

$result = $conn->query("
    SELECT users.username, MAX(scores.score) as best_score
    FROM scores
    JOIN users ON users.id = scores.user_id
    GROUP BY scores.user_id
    ORDER BY best_score DESC
    LIMIT 10
");

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);

?>