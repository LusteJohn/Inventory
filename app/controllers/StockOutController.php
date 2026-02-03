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

        $rawInput = file_get_contents('php://input');
        error_log("StockOut raw input: " . $rawInput);
        $data = $this->getJsonInput();
        error_log("StockOut decoded data: " . print_r($data, true));
        Auth::requireAdmin();

        $data = $this->getJsonInput();

        if (!$data || !is_array($data)) {
            $this->jsonResponse([
                'success' => false,
                'error' => 'Invalid request data'
            ], 400);
        }

        if (
            empty($data['quantity']) || 
            empty($data['requested_by']) || 
            empty($data['purpose']) || 
            empty($data['released_by']) || 
            empty($data['item_id'])
        ) {
            $this->jsonResponse([
                'success' => false,
                'error' => 'All fields are required'
            ], 400);
        }

        $stockOutId = $this->stockOutModel->create($data);

        if ($stockOutId) {
            $stockOutId = $this->stockOutModel->getById($stockOutId);
            $this->jsonResponse([
                'success' => true,
                'message' => 'Stock Out Added successfully',
                'data' => $stockOutId
            ], 201);
        } else {
            $this->jsonResponse([
                'success' => false,
                'error' => 'Failed to add stock out. Check quantity in inventory.'
            ], 400);
        }
    }
}

?>