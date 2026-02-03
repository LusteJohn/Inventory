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
        $quantity     = (int) strip_tags(trim($data['quantity']));
        $requested_by = strip_tags(trim($data['requested_by']));
        $purpose      = strip_tags(trim($data['purpose']));
        $released_by  = strip_tags(trim($data['released_by']));
        $item_id      = (int) $data['item_id'];

        $date_released = date('Y-m-d H:i:s'); // current timestamp

        try {
            $this->conn->beginTransaction();

            //Check available stock in tbl_stock
            $checkStock = $this->conn->prepare(
                "SELECT quantity FROM tbl_stock WHERE item_id = ? FOR UPDATE"
            );
            $checkStock->execute([$item_id]);
            $stock = $checkStock->fetch(PDO::FETCH_ASSOC);

            if (!$stock || $stock['quantity'] < $quantity) {
                throw new Exception('❌ Insufficient stock in main inventory.');
            }

            //Check available stock in tbl_stock_in
            $checkStockIn = $this->conn->prepare(
                "SELECT quantity FROM tbl_stock_in WHERE item_id = ? FOR UPDATE"
            );
            $checkStockIn->execute([$item_id]);
            $stockIn = $checkStockIn->fetch(PDO::FETCH_ASSOC);

            if (!$stockIn || $stockIn['quantity'] < $quantity) {
                throw new Exception('❌ Insufficient stock from stock-in records.');
            }

            //Insert into stock_out table
            $query = "INSERT INTO {$this->table} 
                    (quantity, requested_by, purpose, date_released, released_by, item_id) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$quantity, $requested_by, $purpose, $date_released, $released_by, $item_id]);
            $stockOutId = $this->conn->lastInsertId();

            //Update tbl_stock quantity
            $updateQuery = "UPDATE tbl_stock 
                            SET quantity = quantity - ? 
                            WHERE item_id = ?";
            $updateStmt = $this->conn->prepare($updateQuery);
            $updateStmt->execute([$quantity, $item_id]);

            //Update tbl_stock_in quantity
            $updateOutQuery = "UPDATE tbl_stock_in
                            SET quantity = quantity - ? 
                            WHERE item_id = ?";
            $updateOutStmt = $this->conn->prepare($updateOutQuery);
            $updateOutStmt->execute([$quantity, $item_id]);

            $this->conn->commit();
            return $stockOutId;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
}

?>