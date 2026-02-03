$(document).ready(function () {
    let itemsData = []; // store items globally
    $.ajax({
        url: '/Inventory/api/item',
        type: 'GET',
        dataType: 'json',
        success: function(res) {
            if (res.success) {
                itemsData = res.data; // save all items

                // Populate Add Stock dropdown
                const addDropdown = $('#itemDropdown'); // Add modal dropdown
                addDropdown.empty().append('<option value="">-- Select Item --</option>');
                itemsData.forEach(item => {
                    addDropdown.append(`<option value="${item.item_id}">${item.item_name}</option>`);
                });

                // Populate Edit Stock dropdown
                const editDropdown = $('#edit_itemDropdown'); // Edit modal dropdown
                editDropdown.empty().append('<option value="">-- Select Item --</option>');
                itemsData.forEach(item => {
                    editDropdown.append(`<option value="${item.item_id}">${item.item_name}</option>`);
                });
            } else {
                console.error(res.error);
            }
        },
        error: function() {
            console.error('Failed to fetch items for dropdown');
        }
    });

    // Initialize DataTable
    const table = $('#stocksTable').DataTable({
        ajax: {
            url: '/Inventory/api/stock', // fetch JSON from API controller
            dataSrc: 'data'
        },
        columns: [
            { data: 'stock_id', visible: false }, // hide ID column
            { data: 'item_name' },
            { data: 'quantity' },
            { data: 'last_updated' },
            {
                data: null,
                orderable: false,
                render: function (row) {
                    return `
                        <button 
                            class="btn btn-sm btn-warning editBtn"
                            data-bs-toggle="modal"
                            data-bs-target="#editStockModal"
                            data-id="${row.stock_id}"
                            data-item-id="${row.item_id}"
                            data-quantity="${row.quantity}">
                            <i class="bi bi-pencil"></i>
                        </button>
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

    // When Edit button clicked
    $('#stocksTable tbody').on('click', '.editBtn', function () {
        const btn = $(this);

        // Fill hidden ID and quantity
        $('#edit_id').val(btn.data('id'));
        $('#edit_quantity').val(btn.data('quantity'));

        // Select the correct item in the dropdown
        $('#edit_itemDropdown').val(btn.data('item-id'));
    });

    // Submit Edit Stock form
    $('#editStockForm').on('submit', function(e) {
        e.preventDefault();

        const id = $('#edit_id').val();
        const data = {
            quantity: $('#edit_quantity').val(),
            item_id: $('#edit_itemDropdown').val()
        };

        $.ajax({
            url: `/Inventory/api/stock/${id}`,
            type: 'PUT',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function(res) {
                if (res.success) {
                    // Hide modal
                    const editModalEl = document.getElementById('editStockModal');
                    const editModal = bootstrap.Modal.getInstance(editModalEl)
                        || new bootstrap.Modal(editModalEl);
                    editModal.hide();

                    $('.modal-backdrop').remove();

                    // Reload DataTable
                    table.ajax.reload(null, false);

                    Swal.fire({
                        icon: 'success',
                        title: 'Stock Updated!',
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
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: 'Failed to update stock.'
                });
                console.error(xhr.responseText);
            }
        });
    });

    // Handle Add Item via AJAX
    $('#addStockForm').on('submit', function(e) {
        e.preventDefault();

        const data = {
            item_id: $(this).find('[name="item_id"]').val(),
            quantity: $(this).find('[name="quantity"]').val()
        };
        console.log(data.item_id, data.quantity);

        $.ajax({
            url: '/Inventory/api/stock',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function(res) {
                if (res.success) {
                    // Bootstrap 5 modal hide
                    const addStockModalEl = document.getElementById('addStockModal');
                    const addStockModal = bootstrap.Modal.getInstance(addStockModalEl)
                        || new bootstrap.Modal(addStockModalEl);
                    addStockModal.hide();

                    // Remove leftover backdrop if any
                    $('.modal-backdrop').remove();

                    // Reset form
                    $('#addStockForm')[0].reset();

                    // Optional: Reload a stock table if you have one
                    if ($.fn.DataTable.isDataTable('#stockTable')) {
                        $('#stockTable').DataTable().ajax.reload();
                    }

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
                    text: 'Failed to add stock.'
                });
            }
        });
    });
});
