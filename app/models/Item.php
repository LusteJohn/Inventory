<?php
class Item {
    private $conn;
    private $table = 'tbl_item';
    
    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }
    
    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getItem() {
        $query = "SELECT item_id, item_name FROM " . $this->table . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE item_id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function create($data) {
        $item_name   = strip_tags(trim($data['item_name']));
        $description = strip_tags(trim($data['description']));
        $unit        = strip_tags(trim($data['unit']));
        
        // Validate status
        $status = strtolower(trim($data['status']));
        if ($status !== 'active' && $status !== 'inactive') {
            $status = 'inactive';
        }

        $user_id = (int)$data['user_id'];

        $created_at = date('Y-m-d H:i:s'); // current timestamp

        $query = "INSERT INTO {$this->table} 
                (item_name, description, unit, status, user_id, created_at) 
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);
        $result = $stmt->execute([$item_name, $description, $unit, $status, $user_id, $created_at]);

        return $result ? $this->conn->lastInsertId() : false;
    }
    
    public function update($id, $data) {
        // Sanitize input
        $item_name   = strip_tags(trim($data['item_name']));
        $description = strip_tags(trim($data['description']));
        $unit        = strip_tags(trim($data['unit']));

        // Validate status
        $status = strtolower(trim($data['status']));
        if ($status !== 'active' && $status !== 'inactive') {
            $status = 'inactive'; // default if invalid
        }

        $id = (int)$id; // ensure ID is integer

        $updated_at = date('Y-m-d H:i:s'); // current timestamp

        // Prepare and execute
        $query = "UPDATE " . $this->table . " 
                SET item_name = ?, description = ?, unit = ?, status = ?, updated_at = ? 
                WHERE item_id = ?";

        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$item_name, $description, $unit, $status, $updated_at, $id]);
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE item_id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }
}