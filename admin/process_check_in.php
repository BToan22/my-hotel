<?php
require __DIR__ . "/../DB/db_connect.php";
require __DIR__ . '/auth.php';

header('Content-Type: application/json');
verifyAdmin();

// Read JSON data from the request body
$data = json_decode(file_get_contents("php://input"), true);

$room_id = $data['room_id'] ?? null;
$ref_no = $data['ref_no'] ?? null;

if (!$room_id || !$ref_no) {
    echo json_encode(["success" => false, "message" => "Missing required fields."]);
    exit;
}

try {
    $pdo->beginTransaction();
    // Retrieve room information from the 'rooms' table
    $stmt = $pdo->prepare("SELECT category_id FROM rooms WHERE id = :room_id LIMIT 1");
    $stmt->execute(['room_id' => $room_id]);
    $room = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$room) {
        echo json_encode(["success" => false, "message" => "Invalid room selection."]);
        exit;
    }

    // Find a booking that matches the reference number, has not been assigned a room,
    // and belongs to the same category as the selected room.
    $stmt = $pdo->prepare("
    SELECT id
    FROM booking
    WHERE ref_no = :ref_no
     AND assigned_room_id = 0
     AND status = 0
     AND room_type_id = :room_type_id
     AND DATE(datein) = CURDATE()
    LIMIT 1
");

    $stmt->execute([
        'ref_no' => $ref_no,
        'room_type_id' => $room['category_id']
    ]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);


    if (!$booking) {
        echo json_encode(["success" => false, "message" => "No matching booking found for this room category."]);
        exit;
    }

    // Assign the selected room to the booking and update the status to 'checked-in'
    $stmt = $pdo->prepare("UPDATE booking SET assigned_room_id = :room_id, status = 1 WHERE id = :booking_id");
    $stmt->execute(['room_id' => $room_id, 'booking_id' => $booking['id']]);

    // Update the room status to occupied (1)
    $stmt = $pdo->prepare("UPDATE rooms SET status = 1 WHERE id = :room_id");
    $stmt->execute(['room_id' => $room_id]);
    $pdo->commit();
    echo json_encode(["success" => true]);
} catch (PDOException $e) {

    $pdo->rollBack();
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
