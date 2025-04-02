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

        <div id="message" class="alert" style="display: none;"></div>

        <form id="registerForm">
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

    <script>
        document.getElementById('registerForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const data = {
                name: document.getElementById('name').value,
                username: document.getElementById('username').value,
                password: document.getElementById('password').value,
                confirm_password: document.getElementById('confirm_password').value
            };
            fetch('handle_register.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    const messageDiv = document.getElementById('message');
                    messageDiv.style.display = 'block';
                    messageDiv.className = data.success ? 'alert alert-success' : 'alert alert-danger';
                    messageDiv.innerText = data.success;

                    if (data.success) {
                        setTimeout(() => {
                            window.location.href = 'login.php';
                        }, 2000);
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    </script>

</body>

</html>