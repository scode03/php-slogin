<?php
class User
{
    private $conn;
    private $table_name = "users";

    public $id;
    public $username;
    public $password;
    public $email;
    public $level;
    public $status;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function login()
    {
        $query = "SELECT id, username, password, level, status FROM " . $this->table_name . " WHERE username = :username AND status = 'active'";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $this->username);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($this->password, $row['password'])) {
                $this->id = $row['id'];
                $this->level = $row['level'];
                return true;
            }
        }
        return false;
    }

    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . " SET username=:username, password=:password, email=:email, level=:level";

        $stmt = $this->conn->prepare($query);

        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->level = htmlspecialchars(strip_tags($this->level));

        $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);

        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':level', $this->level);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getAllUsers()
    {
        $currentLevel = Session::get('level');

        // Jika yang login adalah admin, jangan tampilkan superadmin
        if ($currentLevel == 'admin') {
            $query = "SELECT id, username, email, level, status, created_at 
                  FROM " . $this->table_name . " 
                  WHERE level != 'superadmin'
                  ORDER BY created_at DESC";
        } else {
            // Superadmin bisa lihat semuanya
            $query = "SELECT id, username, email, level, status, created_at 
                  FROM " . $this->table_name . " 
                  ORDER BY created_at DESC";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getUserById($id)
    {
        $query = "SELECT id, username, email, level, status FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt;
    }

    public function update()
    {
        $query = "UPDATE " . $this->table_name . " SET username=:username, email=:email, level=:level, status=:status WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':level', $this->level);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function checkPrivilege($action, $targetLevel)
    {
        $currentLevel = Session::get('level');
        $levels = ['user' => 1, 'admin' => 2, 'superadmin' => 3];

        // Superadmin bisa melakukan apapun
        if ($currentLevel == 'superadmin') {
            return true;
        }

        // Admin tidak bisa mengakses superadmin
        if ($currentLevel == 'admin' && $targetLevel == 'superadmin') {
            return false;
        }

        // Admin hanya bisa create/edit/delete user
        if ($currentLevel == 'admin') {
            if ($action == 'create' && $targetLevel == 'user') return true;
            if ($action == 'edit' && $targetLevel == 'user') return true;
            if ($action == 'delete' && $targetLevel == 'user') return true;
            return false;
        }

        return false;
    }

    // Ban User
    public function banUser($id)
    {
        $query = "UPDATE " . $this->table_name . " SET status = 'inactive' WHERE id = :id"; // Ubah Status active menjadi inactive
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    // Unban User
    public function unbanUser($id)
    {
        $query = "UPDATE " . $this->table_name . " SET status = 'active' WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
