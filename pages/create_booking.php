<?php

include "../DB/db_connect.php";


$name         = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$mail         = filter_input(INPUT_POST, 'mail', FILTER_VALIDATE_EMAIL);
$phone        = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
$room_type    = filter_input(INPUT_POST, 'room_type', FILTER_SANITIZE_STRING);
$adult        = filter_input(INPUT_POST, 'adult', FILTER_VALIDATE_INT);
$children     = filter_input(INPUT_POST, 'children', FILTER_VALIDATE_INT);
$datein       = filter_input(INPUT_POST, 'datein', FILTER_SANITIZE_STRING);
$dateout      = filter_input(INPUT_POST, 'dateout', FILTER_SANITIZE_STRING);
$days_of_stay = filter_input(INPUT_POST, 'days_of_stay', FILTER_VALIDATE_INT);
$message      = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
$status       = 0;


$room_types = [
    "Single Room" => 1,
    "Double Room" => 2,
    "Family Room" => 3
];

$room_id = $room_types[$room_type] ?? 3;


function generateUniqueNumber($pdo, $table, $column, $max)
{
    do {
        $number = rand(100000, $max);
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM $table WHERE $column = ?");
        $stmt->execute([$number]);
        $exists = $stmt->fetchColumn();
    } while ($exists > 0);
    return $number;
}


$ref_no  = generateUniqueNumber($pdo, 'booking', 'ref_no', 999999999);
$cus_id  = generateUniqueNumber($pdo, 'customers', 'customer_id', 99999999);

try {

    $pdo->beginTransaction();


    $sql = "INSERT INTO booking (name, ref_no, mail, phone, room_type, room_id, adult, children, datein, dateout, days_of_stay, status, message) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $ref_no, $mail, $phone, $room_type, $room_id, $adult, $children, $datein, $dateout, $days_of_stay, $status, $message]);


    $stmt = $pdo->prepare("SELECT COUNT(*) FROM customers WHERE phone = ?");
    $stmt->execute([$phone]);
    $exists = $stmt->fetchColumn();


    if ($exists == 0) {
        $sql = "INSERT INTO customers (name, customer_id, mail, phone) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $cus_id, $mail, $phone]);
    }


    $pdo->commit();


    header("Location: book.php");
    exit;
} catch (Exception $e) {
    $pdo->rollBack();
    die("Error create booking: " . $e->getMessage());
}
