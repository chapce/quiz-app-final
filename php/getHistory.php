<?php

header("Content-Type: application/json");

$conn = new mysqli("127.0.0.1", "root", "root", "quiz_app", 8889);

if ($conn->connect_error) {
    echo json_encode([
        "error" => "DB connection failed",
        "details" => $conn->connect_error
    ]);
    exit();
}

$user_id = null;

// GET method
if (isset($_GET["user_id"])) {
    $user_id = $_GET["user_id"];
}

// fallback: JSON body (optional safety)
if (!$user_id) {
    $raw = file_get_contents("php://input");
    $data = json_decode($raw, true);
    if (isset($data["user_id"])) {
        $user_id = $data["user_id"];
    }
}

if (!$user_id) {
    echo json_encode([
        "error" => "Missing user_id"
    ]);
    exit();
}

$stmt = $conn->prepare("
    SELECT score, total, played_at
    FROM scores
    WHERE user_id = ?
    ORDER BY played_at DESC
");

if (!$stmt) {
    echo json_encode([
        "error" => "Prepare failed",
        "details" => $conn->error
    ]);
    exit();
}

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

$history = [];

while ($row = $result->fetch_assoc()) {
    $history[] = $row;
}

echo json_encode($history);

?>