<?php
include_once 'config/database.php';
include_once 'config/session.php';
include_once 'models/User.php';
Session::checkLogin();
?>
<!-- Tag HTML&Head -->
<?php include 'layouts/header.php'; ?>

<!-- Tag Nav -->
<?php include 'layouts/navbar.php'; ?>

<?php
// ambil parameter ?page=...
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$file = "pages/" . $page . ".php";
?>

<?php if (file_exists($file)) : ?>
    <?php
    // Atur otorisasi halaman di sini
    if ($page == 'users') {
        Session::authorize(['admin', 'superadmin']);
    }
    ?>

    <?php include $file; ?>

<?php else : ?>
    <div class='container mt-5'>
        <h3>404 - Page not found</h3>
    </div>
<?php endif; ?>

<!-- Tag /Body&/HTML -->
<?php include 'layouts/footer.php'; ?>