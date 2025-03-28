<?php
require __DIR__ . "/../DB/db_connect.php";

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["error" => "Invalid request method."]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$name = trim($data["name"] ?? "");
$username = trim($data["username"] ?? "");
$password = $data["password"] ?? "";
$confirm_password = $data["confirm_password"] ?? "";

if (empty($name) || empty($username) || empty($password) || empty($confirm_password)) {
    echo json_encode(["error" => "Please fill in all fields."]);
    exit;
}

if ($password !== $confirm_password) {
    echo json_encode(["error" => "Passwords do not match."]);
    exit;
}

if (strlen($password) > 10) {
    echo json_encode(["error" => "Password must be at most 10 characters."]);
    exit;
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO users (id, name, username, password, type) VALUES (NULL, ?, ?, ?, 2)");

if ($stmt->execute([$name, $username, $hashed_password])) {
    echo json_encode(["success" => "Registration successful! Redirecting to login..."]);
} else {
    echo json_encode(["error" => "Error registering user."]);
}
