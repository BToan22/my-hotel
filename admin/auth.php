<?php
require __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$secret_key = "7cc49e9057e5aef64c23596a12456c16fb6996127bc6bef13c970905df61239e";

function verifyAdmin() {
    global $secret_key;

    if (!isset($_COOKIE["token"])) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Unauthorized. No token found.']);
        exit;
    }

    try {
        $decoded = JWT::decode($_COOKIE["token"], new Key($secret_key, "HS256"));
        $user_type = $decoded->data->user_type ?? null;

        if ($user_type !== 1) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Access denied. Admin only.']);
            exit;
        }
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Invalid token.']);
        exit;
    }
}
