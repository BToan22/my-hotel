<?php
require __DIR__ . "/../DB/db_connect.php";
require __DIR__ . '/../vendor/autoload.php';

session_start();

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$secret_key = "7cc49e9057e5aef64c23596a12456c16fb6996127bc6bef13c970905df61239e";
$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if (empty($username) || empty($password)) {
        $error_message = "Please enter both username and password.";
    } else {
        $stmt = $pdo->prepare("SELECT id, name, username, password, type FROM users WHERE username = ?");
        if ($stmt->execute([$username])) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            // var_dump($user);
        } else {
            $error_message = "Database error. Please try again later.";
            exit;
        }

        if ($user && password_verify($password, $user["password"])) {
            var_dump($user);
            $payload = [
                "iat" => time(),
                "nbf" => time(),
                "exp" => time() + 3600,
                "data" => [
                    "user_id" => $user["id"],
                    "user_name" => $user["name"],
                    "user_type" => $user["type"]
                ]
            ];
           

            $token = JWT::encode($payload, $secret_key, 'HS256');
            setcookie("token", $token, time() + 3600, "/", "", false, true);

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["user_name"] = $user["name"];
            $_SESSION["user_type"] = ($user["type"] == 1) ? "admin" : "user";

            $success_message = "Login successful! Redirecting...";
            // var_dump($user);
            // exit;

            if ($user["type"] == 1) {
                header("Location: /admin/index.php");
            } else {
                header("Location: /index.php");
            }

            exit;
        } else {
            $error_message = "Invalid username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #121212;
            height: 100vh;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(227, 224, 224, 0.6);
            color: black;
        }

        .login-title {
            font-size: 24px;
            font-weight: bold;
            color: #00bcd4;
        }

        .form-control {
            border-radius: 8px;
            background: white;
            color: black;
            border: 1px solid #ccc;
        }

        .btn-login {
            background-color: #00bcd4;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            color: black;
        }

        .btn-login:hover {
            background-color: #0097a7;
        }

        .register-link {
            text-decoration: none;
            color: #00bcd4;
        }

        .register-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body class="d-flex align-items-center justify-content-center">
    <div class="login-container">
        <h2 class="text-center login-title">Login</h2>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <button type="submit" class="btn btn-login w-100 py-2">Login</button>
        </form>

        <p class="text-center mt-3">
            <a href="register.php" class="register-link">Don't have an account? Sign up now</a>
        </p>
    </div>
</body>

</html>