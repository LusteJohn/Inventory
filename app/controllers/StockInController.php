<?php 
class StockInController extends Controller {
    private $stockInModel;

    public function __construct() {
        $this->stockInModel = new StockIn();
    }

    public function view_stock_in() {
        $stockIn = $this->stockInModel->getStockInItem();
        $this->jsonResponse([
            'success' => true,
            'data' => $stockIn
        ]);
    }

    public function show($id) {
        $stockIn = $this->stockInModel->getById($id);
        if ($stockIn) {
            $this->jsonResponse(['success' => true, 'data' => $stockIn]);
        } else {
            $this->jsonResponse(['success' => false, 'error' => 'Stock In not found'], 404);
        }
    }

    public function store() {
        Auth::requireAdmin();
        $data = $this->getJsonInput();
        
        if (empty($data['quantity']) || empty($data['reference']) || empty($data['received_by']) || empty($data['item_id'])) {
            $this->jsonResponse([
                'success' => false,
                'error' => 'All fields are required'
            ], 400);
        }
        
        $stockInId = $this->stockInModel->create($data);
        
        if ($stockInId) {
            $stockIn = $this->stockInModel->getById($stockInId);
            $this->jsonResponse([
                'success' => true,
                'message' => 'Stock In Added successfully',
                'data' => $stockIn
            ], 201);
        } else {
            $this->jsonResponse(['success' => false, 'error' => 'Failed to add stock in'], 500);
        }
    }
}
?>