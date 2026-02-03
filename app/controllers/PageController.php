<?php
class PageController extends Controller {
    private $itemModel;
    private $stockModel;
    private $stockInModel;
    private $stockOutModel;
    
    public function __construct() {
        $this->itemModel = new Item();
        $this->stockModel = new Stock();
        $this->stockInModel = new StockIn();
        $this->stockOutModel = new StockOut();
    }
    
    // Display list of items
    public function index() {
        Auth::requireLogin(); // Require login to view items
        
        $items = $this->itemModel->getAll();
        $this->view('items/index', ['item' => $items]);
    }

    // Display list of stocks
    public function view_stock() {
        Auth::requireLogin(); // Require login to view items
        
        $stocks = $this->stockModel->getStockItem();
        $this->view('stock/view-stock', ['stock' => $stocks]);
    }

    // Display list of stock in
    public function view_stock_in() {
        Auth::requireLogin(); // Require login to view items
        
        $stockIns = $this->stockInModel->getStockInItem();
        $this->view('stockIn/view-stock-in', ['stockIn' => $stockIns]);
    }

    // Display list of stock out
    public function view_stock_out() {
        Auth::requireLogin(); // Require login to view items
        
        $stockOut = $this->stockOutModel->getStockOutItem();
        $this->view('stockOut/view-stock-out', ['stockOut' => $stockOut]);
    }
    
    // Display single book
    public function show($id) {
        Auth::requireLogin();
        
        $item = $this->itemModel->getById($id);
        if (!$item) {
            http_response_code(404);
            echo "<h1>404 - Item Not Found</h1>";
            exit;
        }
        
        $this->view('items/view', ['item' => $item]);
    }
    
    // Display add items form - ADMIN ONLY
    public function store() {
        Auth::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_PATH . '/item');
            return;
        }

        $data = [
            'item_name' => $_POST['item_name'],
            'description' => $_POST['description'],
            'unit' => $_POST['unit'],
            'status' => $_POST['status'],
            'user_id' => $_SESSION['user_id'],
        ];

        $this->itemModel->create($data);

        $_SESSION['success'] = "Item added successfully!";
        $this->redirect(BASE_PATH . '/item');
    }

    // Display edit items form - ADMIN ONLY
    public function edit($id = null) {
        Auth::requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'item_name' => $_POST['item_name'],
                'description' => $_POST['description'],
                'unit' => $_POST['unit'],
                'status' => $_POST['status']
            ];
            $this->itemModel->update($_POST['id'], $data);
            $_SESSION['success'] = 'Item updated successfully!';
            $this->redirect(BASE_PATH . '/item');
        }
    }

    public function dashboard() {
        Auth::requireLogin(); // ensure only logged-in users can see this
        $this->view('dashboard'); // this will load app/views/dashboard.php
    }

    public function delete($id) {
        Auth::requireAdmin();
        $this->itemModel->delete($id);
        $_SESSION['success'] = "Item deleted!";
        $this->redirect(BASE_PATH . '/item');
    }
}
?>