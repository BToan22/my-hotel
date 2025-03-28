<?php
require __DIR__ . "/../DB/db_connect.php";
require __DIR__ . "/../vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$secret_key = "7cc49e9057e5aef64c23596a12456c16fb6996127bc6bef13c970905df61239e";

session_start();

if (!isset($_COOKIE["token"])) {
    header("Location: login.php");
    exit;
}


try {
    $decoded = JWT::decode($_COOKIE["token"], new Key($secret_key, "HS256"));
    $user_id = $decoded->data->user_id;
    $user_type = $decoded->data->user_type;
    $role = ($decoded->data->user_type ?? 0) == 1 ? "Admin" : "Customer";

    $stmt = $pdo->prepare("SELECT name, mail, phone FROM customers WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    setcookie("token", "", time() - 3600, "/");
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM booking WHERE user_id = ?");
$stmt->execute([$user_id]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);


include '../includes/Head.php';
include '../includes/Header.php';
?>

<div class="container mt-5">
    <h2 class="text-center fw-bold">👤 Profile Information</h2>
    <div class="card shadow-lg border-0 rounded-3 p-4">
        <p><strong>Name:</strong> <?= htmlspecialchars($user['name'] ?? 'N/A') ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['mail']) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone'] ?? 'N/A') ?></p>
        <p><strong>Role:</strong> <?= htmlspecialchars($role) ?></p>
        <!-- <a href="edit_profile.php" class="btn btn-primary">Edit Profile</a> -->
    </div>
</div>
<div class="container mt-5">
    <h2 class="text-center fw-bold">📖 Booking History</h2>
    <div class="card shadow-lg border-0 rounded-3 p-4">
        <?php if (count($bookings) > 0): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Reference No</th>
                        <th>Room Type</th>
                        <th>Room ID</th>
                        <th>Adults</th>
                        <th>Children</th>
                        <th>Check-in Date</th>
                        <th>Check-out Date</th>
                        <th>Days of Stay</th>
                        <th>Price ($)</th>
                        <th>Status</th>
                        <th>Message</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td><?= htmlspecialchars($booking['id']) ?></td>
                            <td><?= htmlspecialchars($booking['ref_no']) ?></td>
                            <td><?= htmlspecialchars($booking['room_type']) ?></td>
                            <td><?= htmlspecialchars($booking['assigned_room_id']) ?></td>
                            <td><?= htmlspecialchars($booking['adult']) ?></td>
                            <td><?= htmlspecialchars($booking['children']) ?></td>
                            <td><?= htmlspecialchars($booking['datein']) ?></td>
                            <td><?= htmlspecialchars($booking['dateout']) ?></td>
                            <td><?= htmlspecialchars($booking['days_of_stay']) ?></td>
                            <td><?= htmlspecialchars($booking['price']) ?></td>
                            <td>
                                <?php
                                switch ($booking['status']) {
                                    case 0:
                                        echo '<span class="badge bg-success">Booked</span>';
                                        break;
                                    case 1:
                                        echo '<span class="badge bg-primary">Checked-in</span>';
                                        break;
                                    case 2:
                                        echo '<span class="badge bg-secondary">Checked-out</span>';
                                        break;

                                    case -1:
                                        echo '<span class="badge bg-danger">Canceled</span>';
                                        break;
                                    case 3:
                                        echo '<span class="badge bg-warning">Pending</span>';
                                        break;
                                    default:
                                        echo '<span class="badge bg-black">Unknown</span>';
                                }
                                ?>
                            </td>
                            <td><?= htmlspecialchars($booking['message']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-center text-muted">No booking history found.</p>
        <?php endif; ?>
    </div>
</div>