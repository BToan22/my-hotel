<?php
require __DIR__ . "/../DB/db_connect.php";
session_start();
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit;
}

if (!isset($_SESSION["user_id"])) {
    echo json_encode(["success" => false, "message" => "You must be logged in"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$name         = $data['name'] ?? null;
$mail         = filter_var($data['mail'], FILTER_VALIDATE_EMAIL);
$phone        = $data['phone'] ?? null;
$room_type    = $data['room_type'] ?? null;
$adult        = (int) ($data['adult'] ?? 0);
$children     = (int) ($data['children'] ?? 0);
$datein       = $data['datein'] ?? null;
$dateout      = $data['dateout'] ?? null;
$days_of_stay = (int) ($data['days_of_stay'] ?? 0);
$message      = $data['message'] ?? "";
$user_id      = $_SESSION["user_id"];

//  room_type
$room_types = ["Single Room" => 1, "Double Room" => 2, "Deluxe Room" => 3];
$room_type_id = $room_types[$room_type] ?? 3;

// Customer exists
$stmt = $pdo->prepare("SELECT COUNT(*) FROM customers WHERE user_id = ?");
$stmt->execute([$user_id]);
$exists = $stmt->fetchColumn();

if ($exists == 0) {
    $cus_id = rand(100000, 99999999);
    $stmt = $pdo->prepare("INSERT INTO customers (name, customer_id, user_id, mail, phone) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $cus_id, $user_id, $mail, $phone]);
}

// Create booking
$ref_no = rand(100000, 999999999);
$sql = "INSERT INTO booking (name, ref_no, mail, phone, room_type, room_types_id, adult, children, datein, dateout, days_of_stay, status, message) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 3, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$name, $ref_no, $mail, $phone, $room_type, $room_type_id, $adult, $children, $datein, $dateout, $days_of_stay, $message]);

echo json_encode(["success" => true, "message" => "Booking created successfully!"]);
exit;
