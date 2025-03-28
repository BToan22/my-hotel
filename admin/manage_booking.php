<?php
require __DIR__ . '/../DB/db_connect.php';
require __DIR__ . '/auth.php';

header('Content-Type: application/json');
verifyAdmin();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Confirm booking
    $data = json_decode(file_get_contents('php://input'), true);
    $id = isset($data['id']) ? intval($data['id']) : 0;

    if ($id > 0) {
        try {
            $stmt = $pdo->prepare("UPDATE booking SET status = 0 WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true, 'message' => 'Booking confirmed.']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid booking ID.']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Delete booking (set status = -1)
    $data = json_decode(file_get_contents('php://input'), true);
    $id = isset($data['id']) ? intval($data['id']) : 0;

    if ($id > 0) {
        try {
            $stmt = $pdo->prepare("UPDATE booking SET status = -1 WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true, 'message' => 'Booking cancelled.']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid booking ID.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
