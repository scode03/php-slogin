<?php
// include_once '../config/database.php';
// include_once '../config/session.php';
// include_once '../models/User.php';

Session::checkLogin();
// Hanya admin dan superadmin yang boleh buka halaman ini
Session::authorize(['admin', 'superadmin']);

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$stmt = $user->getAllUsers();
$currentUserLevel = Session::get('level');

// Handle ban user
if (isset($_GET['ban_id'])) {
    $userToBan = new User($db);
    $userToBan->id = $_GET['ban_id'];

    $stmtUser = $userToBan->getUserById($userToBan->id);
    if ($stmtUser->rowCount() > 0) {
        $userData = $stmtUser->fetch(PDO::FETCH_ASSOC);
        $targetLevel = $userData['level'];

        if ($user->checkPrivilege('edit', $targetLevel)) {
            if ($userToBan->banUser($userToBan->id)) {
                header("Location: index.php?page=users&message=User banned successfully");
                exit;
            }
        } else {
            header("Location: index.php?page=users&error=You don't have permission to ban this user");
            exit;
        }
    }
}

// Handle unban user
if (isset($_GET['unban_id'])) {
    $userToUnban = new User($db);
    $userToUnban->id = $_GET['unban_id'];

    $stmtUser = $userToUnban->getUserById($userToUnban->id);
    if ($stmtUser->rowCount() > 0) {
        $userData = $stmtUser->fetch(PDO::FETCH_ASSOC);
        $targetLevel = $userData['level'];

        if ($user->checkPrivilege('edit', $targetLevel)) {
            if ($userToUnban->unbanUser($userToUnban->id)) {
                header("Location: index.php?page=users&message=User unbanned successfully");
                exit;
            }
        } else {
            header("Location: index.php?page=users&error=You don't have permission to unban this user");
            exit;
        }
    }
}


// Handle delete user
if (isset($_GET['delete_id'])) {
    $userToDelete = new User($db);
    $userToDelete->id = $_GET['delete_id'];

    // Get user level untuk privilege check
    $stmtUser = $userToDelete->getUserById($userToDelete->id);
    if ($stmtUser->rowCount() > 0) {
        $userData = $stmtUser->fetch(PDO::FETCH_ASSOC);
        $targetLevel = $userData['level'];

        if ($user->checkPrivilege('delete', $targetLevel)) {
            if ($userToDelete->delete()) {
                header("Location: index.php?page=users&message=User deleted successfully");
                exit;
            }
        } else {
            header("Location: index.php?page=users&error=You don't have permission to delete this user");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-4">
        <h2>User Management</h2>

        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-success"><?php echo $_GET['message']; ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger"><?php echo $_GET['error']; ?></div>
        <?php endif; ?>

        <div class="mb-3">
            <a href="?page=add_user" class="btn btn-primary">Add New User</a>
        </div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Level</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td>
                            <span class="badge 
                            <?php echo $row['level'] == 'superadmin' ? 'bg-danger' : ($row['level'] == 'admin' ? 'bg-warning' : 'bg-info'); ?>">
                                <?php echo $row['level']; ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge <?php echo $row['status'] == 'active' ? 'bg-success' : 'bg-secondary'; ?>">
                                <?php echo $row['status']; ?>
                            </span>
                        </td>
                        <td><?php echo $row['created_at']; ?></td>
                        <td>
                            <?php if ($user->checkPrivilege('edit', $row['level'])): ?>
                                <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                            <?php endif; ?>

                            <?php if ($user->checkPrivilege('delete', $row['level']) && $row['id'] != Session::get('userid')): ?>
                                <a href="users.php?delete_id=<?php echo $row['id']; ?>"
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Are you sure?')">Delete</a>
                            <?php endif; ?>



                            <?php if ($user->checkPrivilege('edit', $row['level']) && $row['id'] != Session::get('userid')): ?>
                                <?php if ($row['status'] == 'active'): ?>
                                    <a href="index.php?page=users&ban_id=<?php echo $row['id']; ?>"
                                        class="btn btn-sm btn-secondary"
                                        onclick="return confirm('Ban this user?')">Ban</a>
                                <?php else: ?>

                                    <a href="index.php?page=users&unban_id=<?php echo $row['id']; ?>"
                                        class="btn btn-sm btn-success"
                                        onclick="return confirm('Unban this user?')">Unban</a>
                                <?php endif; ?>
                            <?php endif; ?>

                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>

</html>