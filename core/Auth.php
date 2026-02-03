<?php 
class Auth {
    // Check if user is logged in
    public static function check() {
        return isset($_SESSION['user_id']);
    }
    
    // Check if user is admin
    public static function isAdmin() {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'Admin';
    }
    
    // Check if user is regular user
    public static function isUser() {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'Staff';
    }
    
    // Get current user ID
    public static function id() {
        return $_SESSION['user_id'] ?? null;
    }
    
    // Get current user role
    public static function role() {
        return $_SESSION['role'] ?? null;
    }
    
    // Get current username
    public static function username() {
        return $_SESSION['username'] ?? null;
    }
    
    // Login user
    public static function login($user) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
    }
    
    // Logout user
    public static function logout() {
        session_unset();
        session_destroy();
    }
    
    // Require login
    public static function requireLogin() {
        if (!self::check()) {
            header('Location: ' . BASE_PATH . '/login');
            exit;
        }
    }
    
    // Require admin
    public static function requireAdmin() {
        self::requireLogin();
        if (!self::isAdmin()) {
            header('Location: ' . BASE_PATH . '/login');
            exit;
        }
    }
}
?>