<link rel="stylesheet" href="/Inventory/public/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="/Inventory/public/css/dataTables.dataTables.min.css">
<link rel="stylesheet" href="/Inventory/public/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">
    <h3 class="mb-0">Items</h3>
    <div class="d-flex flex-wrap align-items-center gap-2">
        <?php if (Auth::isAdmin()): ?>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addItemModal">
                <i class="bi bi-plus-circle"></i> Add Item
            </button>
        <?php endif; ?>
    </div>
</div>

<table id="itemsTable" class="table table-bordered table-hover table-striped align-middle">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Item Name</th>
            <th>Description</th>
            <th>Unit</th>
            <th>Status</th>
            <?php if (Auth::isAdmin()): ?>
                <th width="160">Actions</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <!-- Table body will be filled dynamically -->
    </tbody>
</table>

<div class="modal fade" id="addItemModal">
  <div class="modal-dialog">
    <form id="addItemForm" class="modal-content">
      <div class="modal-header">
        <h5>Add Item</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input name="item_name" class="form-control mb-2" placeholder="Item name" required>
        <textarea name="description" class="form-control mb-2" placeholder="Description"></textarea>
        <input name="unit" class="form-control mb-2" placeholder="Unit" required>

        <select name="status" class="form-control">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="editItemModal">
  <div class="modal-dialog">
    <form id="editItemForm" class="modal-content">
      <input type="hidden" name="id" id="edit_id">

      <div class="modal-header">
        <h5>Edit Item</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input name="item_name" id="edit_name" class="form-control mb-2" placeholder="Item name" required>
        <textarea name="description" id="edit_desc" class="form-control mb-2" placeholder="Description"></textarea>
        <input name="unit" id="edit_unit" class="form-control mb-2" placeholder="Unit" required>

        <select name="status" id="edit_status" class="form-control">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-warning">Update</button>
      </div>
    </form>
  </div>
</div>

<script>
    const USER_ID = <?= $_SESSION['user_id']?>;
</script>

<!-- jQuery first -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<!-- Your custom JS -->
<script src="<?= BASE_PATH ?>/public/js/item.js"></script>
