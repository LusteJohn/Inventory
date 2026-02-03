<?php 
class StockOutController extends Controller {
    private $stockOutModel;

    public function __construct() {
        $this->stockOutModel = new StockOut();
    }

    public function view_stock_out() {
        $stockOut = $this->stockOutModel->getStockOutItem();
        $this->jsonResponse([
            'success' => true,
            'data' => $stockOut
        ]);
    }

    public function show($id) {
        $stockOut = $this->stockOutModel->getById($id);
        if ($stockOut) {
            $this->jsonResponse(['success' => true, 'data' => $stockOut]);
        } else {
            $this->jsonResponse(['success' => false, 'error' => 'Stock Out not found'], 404);
        }
    }

    public function store() {
        Auth::requireAdmin();
        $data = $this->getJsonInput();
        
        if (empty($data['quantity']) || empty($data['requested_by']) || empty($data['purpose']) || empty($data['date_released']) || empty($data['released_by']) || empty($data['item_id'])) {
            $this->jsonResponse([
                'success' => false,
                'error' => 'All fields are required'
            ], 400);
        }
        
        $stockOutId = $this->stockOutModel->create($data);
        
        if ($stockOutId) {
            $stockIn = $this->stockOutModel->getById($stockOutId);
            $this->jsonResponse([
                'success' => true,
                'message' => 'Stock Out Added successfully',
                'data' => $stockIn
            ], 201);
        } else {
            $this->jsonResponse(['success' => false, 'error' => 'Failed to add stock in'], 500);
        }
    }
}

?>