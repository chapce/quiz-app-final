<?php

include "db.php";
header("Content-Type: application/json");

$raw = file_get_contents("php://input");
$data = json_decode($raw, true);

if (!$data) {
    echo json_encode([
        "success" => false,
        "message" => "No JSON received"
    ]);
    exit;
}

if (!isset($data["user_id"]) || !isset($data["score"]) || !isset($data["total"])) {
    echo json_encode([
        "success" => false,
        "message" => "Missing fields",
        "data" => $data
    ]);
    exit;
}

$user_id = (int)$data["user_id"];
$score = (int)$data["score"];
$total = (int)$data["total"];

$stmt = $conn->prepare("
    INSERT INTO scores (user_id, score, total)
    VALUES (?, ?, ?)
");

if (!$stmt) {
    echo json_encode([
        "success" => false,
        "message" => "Prepare failed",
        "error" => $conn->error
    ]);
    exit;
}

$stmt->bind_param("iii", $user_id, $score, $total);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Score saved"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Insert failed",
        "error" => $stmt->error
    ]);
}

?>