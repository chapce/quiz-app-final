<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: application/json");

include "db.php";

$data = json_decode(file_get_contents("php://input"));

$username = $data->username;
$email = $data->email;
$password = password_hash($data->password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username, email, password)
VALUES (?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $username, $email, $password);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false]);
}

?>