<?php
session_start();
// print_r($_SESSION);
if (!isset($_SESSION["user_type"]) || $_SESSION["user_type"] !== "admin") {
    header("Location: /index.php");
    exit;
}

$page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING) ?? 'home';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        #sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            background: #343a40;
            color: white;
            padding-top: 1rem;
        }

        #sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px 20px;
        }

        #sidebar a:hover {
            background: #495057;
        }

        main {
            margin-left: 250px;
            padding: 20px;
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>
    <?php include 'sidebar.php'; ?>

    <?php
    $p = filter_input(INPUT_GET, 'page');
    $page = isset($p) ? $p : 'home';

    $file = __DIR__ . "/" . $page . ".php";
    ?>
    <main>
        <div class="container">
            <?php
            if (file_exists($file)) {
                include $file;
            } else {
                echo "<div class='alert alert-danger'>Page not found.</div>";
            }
            ?>
        </div>
    </main>

    <!-- Toast Notification -->
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="adminToast" class="toast align-items-center text-white bg-success" role="alert">
            <div class="d-flex">
                <div class="toast-body">Action successful!</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="confirmText">Are you sure?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="confirmBtn">Confirm</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showToast(message, type = 'success') {
            let toast = document.getElementById('adminToast');
            toast.classList.remove('bg-success', 'bg-danger', 'bg-warning');
            toast.classList.add(`bg-${type}`);
            toast.querySelector('.toast-body').innerText = message;
            let bsToast = new bootstrap.Toast(toast);
            bsToast.show();
        }

        function confirmAction(message, callback) {
            document.getElementById('confirmText').innerText = message;
            let confirmBtn = document.getElementById('confirmBtn');
            confirmBtn.onclick = function() {
                callback();
                let confirmModal = bootstrap.Modal.getInstance(document.getElementById('confirmModal'));
                confirmModal.hide();
            };
            let modal = new bootstrap.Modal(document.getElementById('confirmModal'));
            modal.show();
        }
    </script>
</body>

</html>