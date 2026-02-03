<?php 
class StockController extends Controller {
    private $stockModel;

    public function __construct() {
        $this->stockModel = new Stock();
    }

    public function view_stock() {
        $stock = $this->stockModel->getStockItem();
        $this->jsonResponse([
            'success' => true,
            'data' => $stock
        ]);
    }

    public function show($id) {
        $stock = $this->stockModel->getById($id);
        if ($stock) {
            $this->jsonResponse(['success' => true, 'data' => $stock]);
        } else {
            $this->jsonResponse(['success' => false, 'error' => 'Stock not found'], 404);
        }
    }

    public function store() {
        Auth::requireAdmin();
        $data = $this->getJsonInput();
        
        if (empty($data['quantity']) || empty($data['item_id'])) {
            $this->jsonResponse([
                'success' => false,
                'error' => 'All fields are required'
            ], 400);
        }
        
        $stockId = $this->stockModel->create($data);
        
        if ($stockId) {
            $stock = $this->stockModel->getById($stockId);
            $this->jsonResponse([
                'success' => true,
                'message' => 'Stock Added successfully',
                'data' => $stock
            ], 201);
        } else {
            $this->jsonResponse(['success' => false, 'error' => 'Failed to add stock'], 500);
        }
    }

    public function update($id) {
        $data = $this->getJsonInput();
        
        if (!$this->stockModel->getById($id)) {
            $this->jsonResponse(['success' => false, 'error' => 'item not found'], 404);
        }
        
        if (empty($data['quantity']) || empty($data['item_id'])) {
            $this->jsonResponse([
                'success' => false,
                'error' => 'All fields are required'
            ], 400);
        }
        
        $result = $this->stockModel->update($id, $data);
        
        if ($result) {
            $stock = $this->stockModel->getById($id);
            $this->jsonResponse([
                'success' => true,
                'message' => 'Stock updated successfully',
                'data' => $stock
            ]);
        } else {
            $this->jsonResponse(['success' => false, 'error' => 'Failed to update book'], 500);
        }
    }
    
    public function destroy($id) {
        if (!$this->stockModel->getById($id)) {
            $this->jsonResponse(['success' => false, 'error' => 'Book not found'], 404);
        }
        
        $result = $this->stockModel->delete($id);
        
        if ($result) {
            $this->jsonResponse(['success' => true, 'message' => 'Book deleted successfully']);
        } else {
            $this->jsonResponse(['success' => false, 'error' => 'Failed to delete book'], 500);
        }
    }
    
}
?>