<?php
class ItemController extends Controller {
    private $itemModel;
    
    public function __construct() {
        $this->itemModel = new Item();
    }
    
    public function index() {
        $items = $this->itemModel->getAll();
        $this->jsonResponse([
            'success' => true,
            'data' => $items
        ]);
    }
    
    public function show($id) {
        $item = $this->itemModel->getById($id);
        if ($item) {
            $this->jsonResponse(['success' => true, 'data' => $book]);
        } else {
            $this->jsonResponse(['success' => false, 'error' => 'Item not found'], 404);
        }
    }

    public function list() {
        $item = $this->itemModel->getItem();
        if ($item) {
            $this->jsonResponse(['success' => true, 'data' => $item]);
        } else {
            $this->jsonResponse(['success' => false, 'error' => 'Item not found'], 404);
        }
    }
    
    public function store() {
        Auth::requireAdmin();
        $data = $this->getJsonInput();

        if (empty($data['item_name']) || empty($data['description']) || empty($data['unit']) || empty($data['status']) || empty($data['user_id'])) {
            $this->jsonResponse([
                'success' => false,
                'error' => 'All fields are required'
            ], 400);
        }

        $itemId = $this->itemModel->create($data);

        if ($itemId) { // ✅ use $itemId
            $item = $this->itemModel->getById($itemId);
            $this->jsonResponse([
                'success' => true,
                'message' => 'Item created successfully',
                'data' => $item
            ], 201);
        } else {
            $this->jsonResponse(['success' => false, 'error' => 'Failed to create item'], 500);
        }
    }
    
    public function update($id) {
        Auth::requireAdmin();
        $data = $this->getJsonInput();
        
        if (!$this->itemModel->getById($id)) {
            $this->jsonResponse(['success' => false, 'error' => 'item not found'], 404);
        }
        
        if (empty($data['item_name']) || empty($data['description']) || empty($data['unit']) || empty($data['status'])) {
            $this->jsonResponse([
                'success' => false,
                'error' => 'Item Name, Description, Unit, and Status are required'
            ], 400);
        }
        
        $result = $this->itemModel->update($id, $data);
        
        if ($result) {
            $book = $this->itemModel->getById($id);
            $this->jsonResponse([
                'success' => true,
                'message' => 'Item updated successfully',
                'data' => $book
            ]);
        } else {
            $this->jsonResponse(['success' => false, 'error' => 'Failed to update book'], 500);
        }
    }
    
    public function destroy($id) {
        if (!$this->itemModel->getById($id)) {
            $this->jsonResponse(['success' => false, 'error' => 'Book not found'], 404);
        }
        
        $result = $this->itemModel->delete($id);
        
        if ($result) {
            $this->jsonResponse(['success' => true, 'message' => 'Book deleted successfully']);
        } else {
            $this->jsonResponse(['success' => false, 'error' => 'Failed to delete book'], 500);
        }
    }
}