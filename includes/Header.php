<?php
require __DIR__ . "/../vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$secret_key = "7cc49e9057e5aef64c23596a12456c16fb6996127bc6bef13c970905df61239e";
$username = null;
$user_type = null;

if (isset($_COOKIE["token"])) {
    try {
        $decoded = JWT::decode($_COOKIE["token"], new Key($secret_key, "HS256"));
        $username = $decoded->data->user_name;
        $user_type = $decoded->data->user_type ?? null;
    } catch (Exception $e) {
        setcookie("token", "", time() - 3600, "/");
    }
}
?>

<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-white" href="/index.php">HBOOK</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link text-white" href="/index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="../pages/room.php">Rooms</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="../pages/service.php">Services</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="../pages/food.php">Foods</a></li>
                    <li class="nav-item"><a class="nav-link text-warning fw-bold" href="../pages/book.php">Book Now</a></li>

                    <?php if ($username): ?>
                        <?php if ($user_type === 1): ?>
                            <li class="nav-item">
                                <a class="nav-link text-info fw-bold" href="../admin/index.php">Admin Panel</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link text-success fw-bold" href="../pages/profile.php">👤 <?= htmlspecialchars($username) ?></a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-danger" href="../pages/logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link text-white" href="/pages/login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>