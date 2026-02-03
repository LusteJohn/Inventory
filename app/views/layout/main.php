<?php
$currentPath = $_SERVER['REQUEST_URI'];
if (!function_exists('url')) {
    function url($path) {
        return BASE_PATH . $path;
    }
}
if (!function_exists('asset')) {
    function asset($path) {
        return BASE_PATH . $path;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management System</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        body {
            min-height: 100vh;
        }
        .sidebar {
            width: 220px;
            background-color: #343a40;
            color: #fff;
            display: flex;
            flex-direction: column;
            height: 100vh; /* full viewport height */
            position: sticky; /* stays visible when scrolling */
            top: 0;
        }
        .sidebar .nav-link {
            color: #fff;
        }
        .sidebar .nav-link.active {
            background-color: #495057;
            font-weight: 500;
        }
        .sidebar .nav-link:hover {
            background-color: #495057;
        }
        .sidebar .brand {
            text-align: center;
            padding: 1rem 0;
            border-bottom: 1px solid #495057;
        }
        .sidebar .brand img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-bottom: 0.5rem;
        }
        .user-info {
            margin-top: auto;
            padding: 1rem;
            border-top: 1px solid #495057;
        }
    </style>
</head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <nav class="sidebar d-flex flex-column p-3">
        <div class="brand">
            <img src="<?php echo asset('/public/images/logo.png'); ?>" alt="Logo">
            <h5>Inventory Management</h5>
        </div>
        
        <ul class="nav nav-pills flex-column mt-3">
            <li class="nav-item mb-1">
                <a class="nav-link <?php echo (strpos($currentPath, url('/dashboard')) === 0 ? 'active' : ''); ?>" href="<?php echo url('/dashboard'); ?>">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item mb-1">
                <a class="nav-link <?php echo (strpos($currentPath, url('/item')) === 0 ? 'active' : ''); ?>" href="<?php echo url('/item'); ?>">
                    <i class="bi bi-box-seam me-2"></i> Items
                </a>
            </li>
            <li class="nav-item mb-1">
                <a class="nav-link <?php echo (strpos($currentPath, url('/stock')) === 0 ? 'active' : ''); ?>" href="<?php echo url('/stock'); ?>">
                    <i class="bi bi-stack me-2"></i> Stock
                </a>
            </li>
            <li class="nav-item mb-1">
                <a class="nav-link <?php echo (strpos($currentPath, url('/stock-in')) === 0 ? 'active' : ''); ?>" href="<?php echo url('/stock-in'); ?>">
                    <i class="bi bi-arrow-down-circle me-2"></i> Stock In
                </a>
            </li>
            <li class="nav-item mb-1">
                <a class="nav-link <?php echo (strpos($currentPath, url('/stock-out')) === 0 ? 'active' : ''); ?>" href="<?php echo url('/stock-out'); ?>">
                    <i class="bi bi-arrow-up-circle me-2"></i> Stock Out
                </a>
            </li>
        </ul>
        
        <div class="user-info">
            <?php if (Auth::check()): ?>
                <div>👤 <?php echo Auth::username(); ?>
                    <span class="badge bg-secondary ms-1"><?php echo ucfirst(Auth::role()); ?></span>
                </div>
                <div class="mt-2"><a href="<?php echo url('/logout'); ?>" class="text-danger text-decoration-none">Logout</a></div>
            <?php else: ?>
                <div><a href="<?php echo url('/login'); ?>" class="text-white text-decoration-none">Login</a></div>
                <div><a href="<?php echo url('/register'); ?>" class="text-white text-decoration-none">Register</a></div>
            <?php endif; ?>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="flex-grow-1 p-4" style="overflow-y: auto; min-height: 100vh;">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        
        <?php
        if (isset($viewPath) && !empty($viewPath)) {
            $viewFile = __DIR__ . '/../' . $viewPath . '.php';
            if (file_exists($viewFile)) {
                require $viewFile;
            } else {
                echo "<pre style='color:red'>VIEW NOT FOUND: $viewFile</pre>";
            }
        }
        ?>
    </main>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>