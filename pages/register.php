<?php
require __DIR__ . "/../DB/db_connect.php";

$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"] ?? "";
    $username = $_POST["username"] ?? "";
    $password = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";

    if (empty($name) || empty($username) || empty($password) || empty($confirm_password)) {
        $error_message = "Please fill in all fields.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } elseif (strlen($password) > 10) {
        $error_message = "Password must be at most 10 characters.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (id, name, username, password, type) VALUES (NULL, ?, ?, ?, 2)");

        if ($stmt->execute([$name, $username, $hashed_password])) {
            $success_message = "Registration successful! Redirecting to login...";
            header("refresh:2;url=login.php");
        } else {
            $error_message = "Error registering user.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex align-items-center justify-content-center" style="height: 100vh; background-color: #121212;">
    <div class="container" style="max-width: 400px; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(227, 224, 224, 0.6);">
        <h2 class="text-center">Register</h2>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>

        <p class="text-center mt-3">
            <a href="login.php">Already have an account? Login here</a>
        </p>
    </div>
</body>

</html>