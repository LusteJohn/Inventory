<?php 
class User {
    private $conn;
    private $table = 'tbl_user';
    
    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }
    
    // Register new user
    public function register($data) {
        $query = "INSERT INTO " . $this->table . " (username, email, password, role) 
                  VALUES (?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        
        try {
            $result = $stmt->execute([
                $data['username'],
                $data['email'],
                $hashedPassword,
                $data['role'] ?? 'user'
            ]);
            return $result ? $this->conn->lastInsertId() : false;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    // Login user
    public function login($username, $password) {
        $query = "SELECT * FROM " . $this->table . " WHERE username = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$username]);
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return false;
    }
    
    // Get user by ID
    public function getById($id) {
        $query = "SELECT user_id, username, role, created_at FROM " . $this->table . " WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Check if username exists
    public function usernameExists($username) {
        $query = "SELECT id FROM " . $this->table . " WHERE username = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$username]);
        return $stmt->fetch() !== false;
    }
    
    // Check if email exists
    public function emailExists($username) {
        $query = "SELECT id FROM " . $this->table . " WHERE username = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$username]);
        return $stmt->fetch() !== false;
    }
}
?>