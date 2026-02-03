<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inventory Management - Auth</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #4b79a1, #283e51);
            font-family: 'Segoe UI', sans-serif;
        }
        .card { width: 100%; max-width: 400px; padding: 2rem; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); }
        .btn-login { background-color: #4b79a1; color: #fff; font-weight: 500; }
        .btn-login:hover { background-color: #3a6186; }
    </style>
</head>
<body>
    <main>
        <?php
        $viewFile = __DIR__ . '/../' . $viewPath . '.php';
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            echo "<pre style='color:red'>VIEW NOT FOUND: $viewFile</pre>";
        }
        ?>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
