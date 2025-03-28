<?php
require __DIR__ . "/../DB/db_connect.php";
require __DIR__ . "/auth.php";

header('Content-Type: application/json');
verifyAdmin();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'] ?? null;
    $action = $data['action'] ?? null;
    $days_of_stay = isset($data['days_of_stay']) ? $data['days_of_stay'] : null;
    $total_price = isset($data['total_price']) ? $data['total_price'] : null;

    if (!$id || !$action) {
        echo json_encode(["success" => false, "message" => "Invalid request"]);
        exit;
    }

    try {
        if ($action == 'get_booking_details_checkout') {
            // Bookings
            $stmt = $pdo->prepare("SELECT * FROM booking WHERE id = :id LIMIT 1");
            $stmt->execute(['id' => $id]);
            $booking = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$booking) {
                echo json_encode(["success" => false, "message" => "Booking not found"]);
                exit;
            }

            // price room
            $stmt = $pdo->prepare("SELECT price FROM room_categoricals WHERE id = :room_type_id LIMIT 1");
            $stmt->execute(['room_type_id' => $booking['room_type_id']]);
            $roomCategory = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$roomCategory) {
                echo json_encode(["success" => false, "message" => "Room category not found"]);
                exit;
            }

            // day_of_stay
            $checkInDate = new DateTime($booking['datein']);
            $checkOutDate = new DateTime();
            $daysOfStay = $checkInDate->diff($checkOutDate)->days;

            // Total
            $roomPrice = $roomCategory['price'];
            $totalPrice = $roomPrice * $daysOfStay;

            // return Bookings
            echo json_encode([
                "success" => true,
                "booking" => [
                    "id" => $booking['id'],
                    "ref_no" => $booking['ref_no'],
                    "name" => $booking['name'],
                    "room_type" => $booking['room_type'],
                    "datein" => $booking['datein'],
                    "dateout" => date('Y-m-d'),
                    "days_of_stay" => $daysOfStay,
                    "room_price" => $roomPrice,
                    "total_price" => $totalPrice,
                    "mail" => $booking['mail'],
                    "phone" => $booking['phone'],
                ]
            ]);
        }

        if ($action == 'check_out') {

            if ($days_of_stay === null || $total_price === null) {
                echo json_encode(["success" => false, "message" => "Missing days_of_stay or total_price"]);
                exit;
            }

            $stmt = $pdo->prepare("
                UPDATE booking 
                SET 
                    status = 2,  -- checked-out
                    dateout = CURDATE(),
                    days_of_stay = :days_of_stay,
                    price = :total_price
                WHERE id = :id
            ");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':days_of_stay', $days_of_stay, PDO::PARAM_INT);
            $stmt->bindParam(':total_price', $total_price, PDO::PARAM_STR);
            $stmt->execute();

            echo json_encode(["success" => true, "message" => "Check-out successful"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}else if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $id = $_GET['id'] ?? null;

    if (!$id) {
        echo json_encode(["success" => false, "message" => "Invalid booking ID"]);
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM booking WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$booking) {
            echo json_encode(["success" => false, "message" => "Booking not found"]);
            exit;
        }

        echo json_encode([
            "success" => true,
            "booking" => [
                "id" => $booking['id'],
                "ref_no" => $booking['ref_no'],
                "name" => $booking['name'],
                "room_type" => $booking['room_type'],
                "datein" => $booking['datein'],
                "dateout" => $booking['dateout'],
                "days_of_stay" => $booking['days_of_stay'],
                "total_price" => $booking['price'],
                "mail" => $booking['mail'],
                "phone" => $booking['phone'],
            ]
        ]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}
?>
