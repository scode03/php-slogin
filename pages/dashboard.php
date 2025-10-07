<div class="container mt-4">
    <h2>Dashboard</h2>

    <div class="container mt-4">
        <p>Your role: <strong><?php echo Session::get('level'); ?></strong></p>
    </div>

    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">User Management</h5>
                    <p class="card-text">Kelola pengguna sistem</p>
                    <a href="index.php?page=users" class="btn btn-primary">Manage Users</a>
                </div>
            </div>
        </div>

        <?php if ($currentUserLevel == 'superadmin'): ?>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Add New User</h5>
                        <p class="card-text">Tambah pengguna baru</p>
                        <a href="add_user.php" class="btn btn-success">Add User</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($currentUserLevel == 'admin' || $currentUserLevel == 'superadmin'): ?>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Add Regular User</h5>
                        <p class="card-text">Tambah user biasa</p>
                        <a href="add_user.php?level=user" class="btn btn-info">Add Regular User</a>
                    </div>
                </div>
            </div>

        <?php endif; ?>
    </div>
</div>