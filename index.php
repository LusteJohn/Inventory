<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

define('BASE_PATH', '/Inventory');
define('BASE_URL', BASE_PATH);

// Load core classes
require_once 'core/Database.php';
require_once 'core/Router.php';
require_once 'core/Controller.php';
require_once 'core/Auth.php';

// Load config
require_once 'config/database.php';

// Load models
require_once 'app/models/Item.php';
require_once 'app/models/User.php';
require_once 'app/models/Stock.php';
require_once 'app/models/StockIn.php';
require_once 'app/models/StockOut.php';

// Load controllers
require_once 'app/controllers/ItemController.php';
require_once 'app/controllers/StockController.php';
require_once 'app/controllers/StockInController.php';
require_once 'app/controllers/StockOutController.php';
require_once 'app/controllers/PageController.php';
require_once 'app/controllers/AuthController.php';

// Set headers for API routes
if (strpos($_SERVER['REQUEST_URI'], '/api/') !== false) {
    header('Content-Type: application/json');
}

// Initialize router
$router = new Router();

// Auth Routes
$router->addRoute('GET', '/login', 'AuthController', 'showLogin');
$router->addRoute('POST', '/login', 'AuthController', 'login');
$router->addRoute('GET', '/register', 'AuthController', 'showRegister');
$router->addRoute('POST', '/register', 'AuthController', 'register');
$router->addRoute('GET', '/logout', 'AuthController', 'logout');

// API Routes for Items
$router->addRoute('GET', '/api/item', 'ItemController', 'index');
$router->addRoute('GET', '/api/item/{id}', 'ItemController', 'show');
$router->addRoute('GET', '/api/item', 'ItemController', 'list');
$router->addRoute('POST', '/api/item', 'ItemController', 'store');
$router->addRoute('PUT', '/api/item/{id}', 'ItemController', 'update');
$router->addRoute('DELETE', '/api/item/{id}', 'ItemController', 'destroy');

// API Routes for Stock
$router->addRoute('GET', '/api/stock', 'StockController', 'view_stock');
$router->addRoute('GET', '/api/stock/{id}', 'StockController', 'show');
$router->addRoute('POST', '/api/stock', 'StockController', 'store');
$router->addRoute('PUT', '/api/stock/{id}', 'StockController', 'update');
$router->addRoute('DELETE', '/api/stock/{id}', 'StockController', 'destroy');

// API Routes for Stock in
$router->addRoute('GET', '/api/stockIn', 'StockInController', 'view_stock_in');
$router->addRoute('GET', '/api/stockIn/{id}', 'StockInController', 'show');
$router->addRoute('POST', '/api/stockIn', 'StockInController', 'store');
$router->addRoute('PUT', '/api/stockIn/{id}', 'StockInController', 'update');
$router->addRoute('DELETE', '/api/stockIn/{id}', 'StockInController', 'destroy');

// API Routes for Stock out
$router->addRoute('GET', '/api/stockOut', 'StockOutController', 'view_stock_out');
$router->addRoute('GET', '/api/stockOut/{id}', 'StockOutController', 'show');
$router->addRoute('POST', '/api/stockOut', 'StockOutController', 'store');
$router->addRoute('PUT', '/api/stockOut/{id}', 'StockOutController', 'update');
$router->addRoute('DELETE', '/api/stockOut/{id}', 'StockOutController', 'destroy');

// Page Routes
$router->addRoute('GET', '/dashboard', 'PageController', 'dashboard');
$router->addRoute('GET', '/', 'PageController', 'index');
$router->addRoute('GET', '/item', 'PageController', 'index');
$router->addRoute('GET', '/stock', 'PageController', 'view_stock');
$router->addRoute('GET', '/stock-in', 'PageController', 'view_stock_in');
$router->addRoute('GET', '/stock-out', 'PageController', 'view_stock_out');
$router->addRoute('GET', '/item/view/{id}', 'PageController', 'show');
$router->addRoute('POST', '/item/add', 'PageController', 'store');
$router->addRoute('POST', '/item/edit', 'PageController', 'edit');
$router->addRoute('DELETE', '/item/delete/{id}', 'PageController', 'delete');



// Dispatch request
$router->dispatch();
?>