<?php
require __DIR__ . '/../DB/db_connect.php';
require __DIR__ . '/auth.php';
header('Content-Type: application/json');
verifyAdmin();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    $id = isset($data['id']) ? intval($data['id']) : 0;
    $category_id = isset($data['category_id']) ? intval($data['category_id']) : 0;
    $room = isset($data['room']) ? trim($data['room']) : '';
    $status = isset($data['status']) ? intval($data['status']) : 0;

    if ($category_id == 0 || empty($room)) {
        echo json_encode(['success' => false, 'message' => 'Invalid input data.']);
        exit;
    }

    try {
        if ($id > 0) {
            $stmt = $pdo->prepare("UPDATE rooms SET category_id = ?, room = ?, status = ? WHERE id = ?");
            $stmt->execute([$category_id, $room, $status, $id]);
            echo json_encode(['success' => true, 'message' => 'Room updated successfully.']);
        } else {
            $stmt = $pdo->prepare("INSERT INTO rooms (category_id, room, status) VALUES (?, ?, ?)");
            $stmt->execute([$category_id, $room, $status]);
            echo json_encode(['success' => true, 'message' => 'Room created successfully.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = isset($data['id']) ? intval($data['id']) : 0;
    if ($id > 0) {
        try {
            $stmt = $pdo->prepare("DELETE FROM rooms WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true, 'message' => 'Room deleted successfully.']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid room ID.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
