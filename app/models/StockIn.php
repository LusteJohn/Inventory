<?php 
class StockIn {
    private $conn;
    private $table = 'tbl_stock_in';

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
        $query = "SELECT * FROM " . $this->table . " WHERE stock_in_id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getStockInItem() {
        $query = "SELECT 
                        si.stock_in_id,
                        i.item_id,
                        i.item_name,
                        si.quantity,
                        si.reference,
                        si.date_received,
                        si.received_by
                FROM " . $this->table . " si
                INNER JOIN tbl_item i ON si.item_id = i.item_id
                ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $quantity    = (int) strip_tags(trim($data['quantity']));
        $reference   = strip_tags(trim($data['reference']));
        $received_by = strip_tags(trim($data['received_by']));
        $item_id     = (int) $data['item_id'];

        $date_received = date('Y-m-d H:i:s'); // current timestamp

        try {
            $this->conn->beginTransaction();

            // 1️⃣ Insert into stock_in table
            $query = "INSERT INTO {$this->table} 
                    (quantity, reference, date_received, received_by, item_id) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$quantity, $reference, $date_received, $received_by, $item_id]);
            $stockInId = $this->conn->lastInsertId();

            // 2️⃣ Update tbl_stock quantity
            $checkStock = $this->conn->prepare("SELECT stock_id, quantity FROM tbl_stock WHERE item_id = ?");
            $checkStock->execute([$item_id]);
            $stock = $checkStock->fetch(PDO::FETCH_ASSOC);

            if ($stock) {
                // Stock exists, increment quantity
                $newQty = $stock['quantity'] + $quantity;
                $update = $this->conn->prepare("UPDATE tbl_stock SET quantity = ?, last_updated = NOW() WHERE stock_id = ?");
                $update->execute([$newQty, $stock['stock_id']]);
            } else {
                // Stock doesn't exist, create new stock row
                $insertStock = $this->conn->prepare("INSERT INTO tbl_stock (item_id, quantity, last_updated) VALUES (?, ?, NOW())");
                $insertStock->execute([$item_id, $quantity]);
            }

            $this->conn->commit();
            return $stockInId;

        } catch (PDOException $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function update($id, $data) {
        // Sanitize input
        $quantity    = (int) strip_tags(trim($data['quantity']));
        $reference   = strip_tags(trim($data['reference']));
        $received_by = strip_tags(trim($data['received_by']));
        //$item_id   = strip_tags(trim($data['item_id']));

        $id = (int)$id; // ensure ID is integer
        $date_updated = date('Y-m-d H:i:s');

        $query = "UPDATE " . $this->table . " 
                  SET quantity = ?, 
                    reference = ?,
                    received_by = ?,
                    date_updated = ?
                  WHERE stock_in_id = ?";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$quantity, $reference, $received_by, $date_updated, $id]);
    }
    
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE stock_in_id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }

}

?>