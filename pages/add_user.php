<?php
Session::checkLogin();
$currentUserLevel = Session::get('level');

// Set default level berdasarkan privilege
$defaultLevel = 'user';
if ($currentUserLevel == 'superadmin') {
    $defaultLevel = isset($_GET['level']) ? $_GET['level'] : 'user';
}

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

if ($_POST) {
    $user->username = $_POST['username'];
    $user->password = $_POST['password'];
    $user->email = $_POST['email'];
    $user->level = $_POST['level'];

    // Check privilege untuk create user dengan level tertentu
    if ($user->checkPrivilege('create', $user->level)) {
        if ($user->create()) {
            header("Location: index.php?page=users&message=User created successfully");
            exit;
        } else {
            $error = "Failed to create user";
        }
    } else {
        $error = "You don't have permission to create this level of user";
    }
}
?>

<div class="container mt-4">
    <h2>Add New User</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <div class="mb-3">
            <label for="level" class="form-label">Level</label>
            <select class="form-control" id="level" name="level" required>
                <option value="user" <?php echo $defaultLevel == 'user' ? 'selected' : ''; ?>>User</option>
                <?php if ($currentUserLevel == 'superadmin'): ?>
                    <option value="admin" <?php echo $defaultLevel == 'admin' ? 'selected' : ''; ?>>Admin</option>
                    <option value="superadmin" <?php echo $defaultLevel == 'superadmin' ? 'selected' : ''; ?>>Superadmin</option>
                <?php endif; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Create User</button>
        <a href="index.php?page=users" class="btn btn-secondary">Cancel</a>
    </form>
</div>