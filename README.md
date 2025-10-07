# PHP Login System with Role Management

A simple PHP native login and user management system with role-based access control.

## ✨ Features
- Login, Logout, Register
- User roles: `User`, `Admin`, `Superadmin`
- Ban/Unban users
- Delete users (with privilege check)
- Role-based access restriction using `Session::authorize()`
- Centralized layout system (navbar + content include)
- PDO + Secure password hashing

## 🧩 Tech Stack
- PHP (Native)
- MySQL (PDO)
- Bootstrap 5
- Laragon (for local dev)

## ⚙️ Setup
1. Clone the repo:
   ```bash
   git clone https://github.com/<username>/php-slogin.git
2. Import the SQL file to your database.

3. Update config/database.php with your DB credentials.

4. Run locally using Laragon or XAMPP.
