<?php
Session::checkLogin();

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$currentUserLevel = Session::get('level');
$currentUsername = Session::get('username');
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">Login System</a>
        <div class="navbar-nav ms-auto">
            <span class="navbar-text me-3">
                Welcome, <?php echo $currentUsername; ?> (<?php echo $currentUserLevel; ?>)
            </span>
            <a class="nav-link" href="logout.php">Logout</a>
        </div>
    </div>
</nav>