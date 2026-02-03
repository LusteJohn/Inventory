<?php
abstract class Controller {
    // For API responses
    protected function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function getJsonInput() {
        return json_decode(file_get_contents('php://input'), true);
    }
    
    // For rendering views
    protected function view($view, $data = [], $layout = 'main') {
        extract($data);

        // Make variable available to layout
        $viewPath = $view;

        if ($layout === 'auth') {
            require __DIR__ . '/../app/views/layout/auth.php';
        } else {
            require __DIR__ . '/../app/views/layout/main.php';
        }
    }
    
    protected function redirect($url) {
        header("Location: $url");
        exit;
    }
}
?>