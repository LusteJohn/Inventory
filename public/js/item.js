$(document).ready(function () {
    // Initialize DataTable
    const table = $('#itemsTable').DataTable({
        ajax: {
            url: '/Inventory/api/item', // fetch JSON from API controller
            dataSrc: 'data'
        },
        columns: [
            { data: 'item_id', visible: false }, // hide ID column
            { data: 'item_name' },
            { data: 'description' },
            { data: 'unit' },
            {
                data: 'status',
                render: function (data) {
                    return data === 'active' || data == 1
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                }
            },
            {
                data: null,
                orderable: false,
                render: function (row) {
                    return `
                        <button 
                            class="btn btn-sm btn-warning editBtn"
                            data-bs-toggle="modal"
                            data-bs-target="#editItemModal"
                            data-id="${row.item_id}"
                            data-name="${row.item_name}"
                            data-desc="${row.description}"
                            data-unit="${row.unit}"
                            data-status="${row.status}">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <a href="/Inventory/item/delete/${row.item_id}" 
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Delete this item?')">
                           <i class="bi bi-trash"></i>
                        </a>
                    `;
                }
            }
        ],
        pageLength: 10,
        responsive: true,
        order: [[0, 'desc']],
        language: {
            search: "🔍 Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ items",
            paginate: { previous: "«", next: "»" }
        }
    });

    // Open modal and fill data
    $('#itemsTable tbody').on('click', '.editBtn', function () {
        const btn = $(this);
        $('#edit_id').val(btn.data('id'));
        $('#edit_name').val(btn.data('name'));
        $('#edit_desc').val(btn.data('desc'));
        $('#edit_unit').val(btn.data('unit'));
        $('#edit_status').val(btn.data('status'));
    });

    // Submit edit form via AJAX
    $('#editItemForm').on('submit', function (e) {
        e.preventDefault();

        const id = $('#edit_id').val();
        const data = {
            item_name: $('#edit_name').val(),
            description: $('#edit_desc').val(),
            unit: $('#edit_unit').val(),
            status: $('#edit_status').val(),
            user_id: USER_ID
        };

        $.ajax({
            url: `/Inventory/api/item/${id}`,
            type: 'PUT',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function (res) {
                if (res.success) {
                    const editItemModalEl = document.getElementById('editItemModal');
                    const editItemModal = bootstrap.Modal.getInstance(editItemModalEl) || new bootstrap.Modal(editItemModalEl);
                    editItemModal.hide();
                    
                    $('.modal-backdrop').remove();
                    // Reload DataTable
                    table.ajax.reload();
                    // SweetAlert success
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: res.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: res.error
                    });
                }
            },
            error: function (xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: 'Failed to update item.'
                });
            }
        });
    });

    // Handle Add Item via AJAX
    $('#addItemForm').on('submit', function(e) {
        e.preventDefault();

        const data = {
            item_name: $(this).find('[name="item_name"]').val(),
            description: $(this).find('[name="description"]').val(),
            unit: $(this).find('[name="unit"]').val(),
            status: $(this).find('[name="status"]').val(),
            user_id: USER_ID
        };

        $.ajax({
            url: '/Inventory/api/item',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function(res) {
                if (res.success) {
                    // Bootstrap 5 modal hide
                    const addItemModalEl = document.getElementById('addItemModal');
                    const addItemModal = bootstrap.Modal.getInstance(addItemModalEl) || new bootstrap.Modal(addItemModalEl);
                    addItemModal.hide();

                    // Remove leftover backdrop if any
                    $('.modal-backdrop').remove();

                    // Reset form
                    $('#addItemForm')[0].reset();

                    // Refresh DataTable
                    $('#itemsTable').DataTable().ajax.reload();

                    // SweetAlert
                    Swal.fire({
                        icon: 'success',
                        title: 'Item Added!',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: res.error
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: 'Failed to add item.'
                });
            }
        });
    });
});
