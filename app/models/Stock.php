<?php
class Stock {
    private $conn;
    private $table = 'tbl_stock';
    
    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }
    
    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStockItem() {
        $query = "SELECT 
                        s.stock_id,
                        i.item_id,
                        i.item_name,
                        s.quantity, 
                        s.last_updated
                FROM " . $this->table . " s
                INNER JOIN tbl_item i ON s.item_id = i.item_id
                ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE stock_id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function create($data) {
        $quantity   = strip_tags(trim($data['quantity']));
        
        $item_id = (int)$data['item_id'];

        $last_updated = date('Y-m-d H:i:s'); // current timestamp

        $query = "INSERT INTO {$this->table} 
                (quantity, last_updated, item_id) 
                VALUES (?, ?, ?)";

        $stmt = $this->conn->prepare($query);
        $result = $stmt->execute([$quantity, $last_updated, $item_id]);

        return $result ? $this->conn->lastInsertId() : false;
    }
    
    public function update($id, $data) {
        // Sanitize input
        $quantity   = strip_tags(trim($data['quantity']));
        //$item_id   = strip_tags(trim($data['item_id']));

        $id = (int)$id; // ensure ID is integer
        $last_updated = date('Y-m-d H:i:s');

        $query = "UPDATE " . $this->table . " 
                  SET quantity = ?, last_updated = ?
                  WHERE stock_id = ?";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$quantity, $last_updated, $id]);
    }
    
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE stock_in_id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }
}