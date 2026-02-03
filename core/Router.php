<?php 
class Router {
    private $routes = [];
    
    public function addRoute($method, $path, $controller, $action) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
            'action' => $action
        ];
    }
    
    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // 🔥 REMOVE BASE FOLDER FROM URI
        $base = '/Inventory';
        if (strpos($uri, $base) === 0) {
            $uri = substr($uri, strlen($base));
        }

        // Normalize empty URI
        if ($uri === '') {
            $uri = '/';
        }

        foreach ($this->routes as $route) {
            $pattern = preg_replace('/\{[a-zA-Z]+\}/', '([^/]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';

            if ($route['method'] === $method && preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                $controller = new $route['controller']();
                return call_user_func_array([$controller, $route['action']], $matches);
            }
        }

        http_response_code(404);
        echo "404 - Route not found";
    }
}
?>