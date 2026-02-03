<!-- Dashboard View -->
<div class="container-fluid p-4">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Dashboard</h2>
        <small class="text-muted">Welcome, <?= htmlspecialchars(Auth::username()) ?></small>
    </div>

    <!-- Row of Summary Cards -->
    <div class="row g-4">
        <!-- Total Items -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title">Total Items</h5>
                    <h3 class="card-text"><?= $totalItems ?? '0' ?></h3>
                </div>
            </div>
        </div>

        <!-- Items In Stock -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title">Items In Stock</h5>
                    <h3 class="card-text"><?= $inStock ?? '0' ?></h3>
                </div>
            </div>
        </div>

        <!-- Items Out -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title">Items Out</h5>
                    <h3 class="card-text"><?= $outStock ?? '0' ?></h3>
                </div>
            </div>
        </div>

        <!-- Users Logged In -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title">Your Role</h5>
                    <h3 class="card-text"><?= htmlspecialchars(Auth::role()) ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Optional Chart Section -->
    <div class="mt-5">
        <h4 class="mb-3">Inventory Analytics</h4>
        <div class="card p-4 shadow-sm">
            <canvas id="inventoryChart"></canvas>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('inventoryChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Stock In', 'Stock Out', 'Available'],
                datasets: [{
                    label: 'Inventory Stats',
                    data: [<?= $chartStockIn ?? 0 ?>, <?= $chartStockOut ?? 0 ?>, <?= $chartAvailable ?? 0 ?>],
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc']
                }]
            }
        });
    }
</script>
