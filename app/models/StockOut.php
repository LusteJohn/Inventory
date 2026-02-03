<?php
class StockOut extends Controller {
    private $conn;
    private $table = 'tbl_stock_out';

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE stock_out_id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getStockOutItem() {
        $query = "SELECT 
                        so.stock_out_id,
                        i.item_id,
                        i.item_name,
                        so.quantity,
                        so.requested_by,
                        so.purpose,
                        so.date_released,
                        so.released_by
                FROM " . $this->table . " so
                INNER JOIN tbl_item i ON so.item_id = i.item_id
                ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $quantity     = isset($data['quantity']) ? (int) $data['quantity'] : 0;
        $requested_by = isset($data['requested_by']) ? trim(strip_tags($data['requested_by'])) : '';
        $purpose      = isset($data['purpose']) ? trim(strip_tags($data['purpose'])) : '';
        $released_by  = isset($data['released_by']) ? trim(strip_tags($data['released_by'])) : '';
        $item_id      = isset($data['item_id']) ? (int) $data['item_id'] : 0;

        $date_released = date('Y-m-d H:i:s'); 

        try {
            $this->conn->beginTransaction();

            //Check stock
            $checkStock = $this->conn->prepare("SELECT quantity FROM tbl_stock WHERE item_id = ? FOR UPDATE");
            $checkStock->execute([$item_id]);
            $stock = $checkStock->fetch(PDO::FETCH_ASSOC);
            if (!$stock || $stock['quantity'] < $quantity) {
                throw new Exception('❌ Insufficient stock in main inventory.');
            }

            $checkStockIn = $this->conn->prepare("SELECT quantity FROM tbl_stock_in WHERE item_id = ? FOR UPDATE");
            $checkStockIn->execute([$item_id]);
            $stockIn = $checkStockIn->fetch(PDO::FETCH_ASSOC);
            if (!$stockIn || $stockIn['quantity'] < $quantity) {
                throw new Exception('❌ Insufficient stock in stock-in records.');
            }

            //Insert
            $stmt = $this->conn->prepare("INSERT INTO {$this->table} 
                (quantity, requested_by, purpose, date_released, released_by, item_id)
                VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$quantity, $requested_by, $purpose, $date_released, $released_by, $item_id]);
            $stockOutId = $this->conn->lastInsertId();

            //Update main stock
            $stmt = $this->conn->prepare("UPDATE tbl_stock SET quantity = quantity - ? WHERE item_id = ?");
            $stmt->execute([$quantity, $item_id]);
            if ($stmt->rowCount() === 0) throw new Exception('❌ Failed to update main stock.');

            $this->conn->commit();
            return $stockOutId;

        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("StockOut Error: " . $e->getMessage());
            return false; // <- RETURN FALSE so controller knows it failed
        }
    }
}

?>