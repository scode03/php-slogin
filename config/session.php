<?php
session_start();

class Session
{
    public static function init()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function get($key)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public static function destroy()
    {
        session_destroy();
        $_SESSION = array();
    }

    public static function checkLogin()
    {
        self::init();
        if (self::get('loggedin') !== true) {
            header("Location: login.php");
            exit;
        }
    }

    public static function checkLevel($requiredLevel)
    {
        self::init();
        $userLevel = self::get('level');

        $levels = ['user' => 1, 'admin' => 2, 'superadmin' => 3];

        if (!isset($levels[$userLevel]) || $levels[$userLevel] < $levels[$requiredLevel]) {
            header("Location: index.php?page=unauthorized");
            exit;
        }
    }

    public static function authorize($allowedLevels = [])
    {
        self::init();
        $userLevel = self::get('level');

        // Jika level user tidak ada dalam daftar yang diizinkan
        if (!in_array($userLevel, $allowedLevels)) {
            header("Location: index.php?page=unauthorized");
            exit;
        }
    }
}
