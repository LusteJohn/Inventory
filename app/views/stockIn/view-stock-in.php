<link rel="stylesheet" href="/Inventory/public/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="/Inventory/public/css/dataTables.dataTables.min.css">
<link rel="stylesheet" href="/Inventory/public/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">
    <h3 class="mb-0">Stock In Availability Page</h3>
    <div class="d-flex flex-wrap align-items-center gap-2">
        <?php if (Auth::isAdmin()): ?>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addStockInModal">
                <i class="bi bi-plus-circle"></i> Add Stock In
            </button>
        <?php endif; ?>
    </div>
</div>

<table id="stocksInTable" class="table table-bordered table-hover table-striped align-middle">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Item Name</th>
            <th>Quantity</th>
            <th>Reference</th>
            <th>Received By</th>
            <?php if (Auth::isAdmin()): ?>
                <th width="160">Actions</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <!-- Table body will be filled dynamically -->
    </tbody>
</table>

<div class="modal fade" id="addStockInModal">
  <div class="modal-dialog">
    <form id="addStockInForm" class="modal-content">
      <div class="modal-header">
        <h5>Add Stock-In</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <!-- Dynamic Item Dropdown -->
        <div class="mb-3">
          <label for="itemDropdown" class="form-label">Select Item</label>
          <select id="itemDropdown" name="item_id" class="form-select" required>
            <option value="">-- Select Item --</option>
          </select>
        </div>

        <!-- Quantity -->
        <input name="quantity" type="number" class="form-control mb-2" placeholder="Quantity" required>
        <input name="reference" class="form-control mb-2" placeholder="Reference (e.g., ABC123)" required>
        <input name="received_by" class="form-control mb-2" placeholder="Received By" required>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Stock Modal -->
<div class="modal fade" id="editStockInModal">
  <div class="modal-dialog">
    <form id="editStockInForm" class="modal-content">
      <input type="hidden" name="id" id="edit_id">

      <div class="modal-header">
        <h5>Edit Stock In</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input name="quantity" id="edit_quantity" type="number" class="form-control mb-2" placeholder="Quantity" required>
        <input name="reference" id="edit_reference" type="text" class="form-control mb-2" placeholder="Reference" required>
        <input name="received_by" id="edit_received_by" type="text" class="form-control mb-2" placeholder="Received By" required>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-warning">Update</button>
      </div>
    </form>
  </div>
</div>

<!-- jQuery first -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<!-- Your custom JS -->
<script src="<?= BASE_PATH ?>/public/js/stock-in.js"></script>
