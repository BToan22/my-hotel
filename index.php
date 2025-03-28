<?php

include 'includes/Head.php';
include 'includes/Header.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

$file = "pages/" . $page . ".php";

echo '<div class="container-fluid mt-4">';
if (file_exists($file)) {
    include($file);
} else {
    echo "<h3 class='text-danger'>404 - Page not found!</h3>";
}
echo '</div>';

include 'includes/Footer.php';
