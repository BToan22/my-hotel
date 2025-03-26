<?php
// session_start();
include 'header.php';
if (isset($_SESSION['user_id']) && isset($_SESSION['user_name'])):
?>
    <nav class="d-flex flex-column flex-shrink-0 p-3 bg-dark text-white vh-100 position-fixed" style="width: 200px;">
        <h5 class="text-center mb-3"><i class="fa fa-building"></i> HBOOK</h5>

        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="index.php?page=home" class="nav-link text-white">
                    <i class="fa fa-home me-2"></i> Home
                </a>
            </li>
            <li>
                <a href="index.php?page=booked" class="nav-link text-white">
                    <i class="fa fa-book me-2"></i> Booked
                </a>
            </li>
            <li>
                <a href="index.php?page=check_in" class="nav-link text-white">
                    <i class="fa fa-sign-in-alt me-2"></i> Check In
                </a>
            </li>
            <li>
                <a href="index.php?page=check_out" class="nav-link text-white">
                    <i class="fa fa-sign-out-alt me-2"></i> Check Out
                </a>
            </li>
            <li>
                <a href="index.php?page=customers" class="nav-link text-white">
                    <i class="fa fa-users me-2"></i> Customers
                </a>
            </li>
            <li>
                <a href="index.php?page=rooms" class="nav-link text-white">
                    <i class="fa fa-bed me-2"></i> Rooms
                </a>
            </li>
            <!-- <?php if ($_SESSION['login_type'] == 1): ?>
                <li>
                    <a href="index.php?page=users" class="nav-link text-white">
                        <i class="fa fa-user me-2"></i> Users
                    </a>
                </li>
            <?php endif; ?> -->
        </ul>

        <div class="mt-auto">
            <a href="logout.php" class="nav-link text-white d-flex align-items-center">
                <i class="fa fa-power-off me-2"></i> <?php echo $_SESSION['user_name'] ?>
            </a>
        </div>
    </nav>

    <script>
        $(document).ready(function() {
            var page = "<?php echo filter_input(INPUT_GET, 'page') ?? 'home'; ?>";
            $('a[href="index.php?page=' + page + '"]').addClass('active bg-primary');
        });
    </script>

<?php else:
    header("Location: login.php");
    exit();
endif;
?>