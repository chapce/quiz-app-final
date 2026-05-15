<?php

include "db.php";
header("Content-Type: application/json");


$data = json_decode(file_get_contents("php://input"), true);


if (!$data || !isset($data["username"]) || !isset($data["password"])) {
    echo json_encode([
        "success" => false,
        "message" => "Missing username or password"
    ]);
    exit();
}

$username = $data["username"];
$password = $data["password"];


$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([
        "success" => false,
        "message" => "SQL prepare failed"
    ]);
    exit();
}

$stmt->bind_param("s", $username);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();


if (!$user) {
    echo json_encode([
        "success" => false,
        "message" => "User not found"
    ]);
    exit();
}


if (password_verify($password, $user["password"])) {

    echo json_encode([
        "success" => true,
        "user_id" => $user["id"],
        "username" => $user["username"]
    ]);

} else {

    echo json_encode([
        "success" => false,
        "message" => "Wrong password"
    ]);
}
?>