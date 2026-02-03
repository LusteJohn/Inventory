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

    // Handle Add Item via AJAX
    $('#addStockInForm').on('submit', function(e) {
        e.preventDefault();

        const data = {
            item_id: $(this).find('[name="item_id"]').val(),
            quantity: $(this).find('[name="quantity"]').val(),
            reference: $(this).find('[name="reference"]').val(),
            received_by: $(this).find('[name="received_by"]').val()
        };
        console.log(data.item_id, data.quantity, data.reference, data.received_by);

        $.ajax({
            url: '/Inventory/api/stockIn',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function(res) {
                if (res.success) {
                    // Bootstrap 5 modal hide
                    const addStockModalInEl = document.getElementById('addStockInModal');
                    const addStockInModal = bootstrap.Modal.getInstance(addStockModalInEl)
                        || new bootstrap.Modal(addStockModalInEl);
                    addStockInModal.hide();

                    // Remove leftover backdrop if any
                    $('.modal-backdrop').remove();

                    // Reset form
                    $('#addStockInForm')[0].reset();

                    // Optional: Reload a stock table if you have one
                    if ($.fn.DataTable.isDataTable('#stocksInTable')) {
                        $('#stocksInTable').DataTable().ajax.reload();
                    }

                    // SweetAlert
                    Swal.fire({
                        icon: 'success',
                        title: 'Stock-In Added!',
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

    // Initialize DataTable
    const table = $('#stocksInTable').DataTable({
        ajax: {
            url: '/Inventory/api/stockIn', // fetch JSON from API controller
            dataSrc: 'data'
        },
        columns: [
            { data: 'stock_in_id', visible: false }, // hide ID column
            { data: 'item_name' },
            { data: 'quantity' },
            { data: 'reference' },
            { data: 'received_by' },
            {
                data: null,
                orderable: false,
                render: function (row) {
                    return `
                        <button 
                            class="btn btn-sm btn-warning editBtn"
                            data-bs-toggle="modal"
                            data-bs-target="#editStockInModal"
                            data-id="${row.stock_in_id}"
                            data-quantity="${row.quantity}"
                            data-reference="${row.reference}"
                            data-received_by="${row.received_by}">
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

    // Open modal and fill data
    $('#stocksInTable tbody').on('click', '.editBtn', function () {
        const btn = $(this);
        $('#edit_id').val(btn.data('id'));
        $('#edit_quantity').val(btn.data('quantity'));
        $('#edit_reference').val(btn.data('reference'));
        $('#edit_received_by').val(btn.data('received_by'));
    });

    // Submit edit form via AJAX
    $('#editStockInForm').on('submit', function (e) {
        e.preventDefault();

        const id = $('#edit_id').val();
        const data = {
            quantity: $('#edit_quantity').val(),
            reference: $('#edit_reference').val(),
            received_by: $('#edit_received_by').val()
        };

        $.ajax({
            url: `/Inventory/api/stockIn/${id}`,
            type: 'PUT',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function (res) {
                if (res.success) {
                    const editStockInModalEl = document.getElementById('editStockInModal');
                    const editStockInModal = bootstrap.Modal.getInstance(editStockInModalEl) || new bootstrap.Modal(editStockInModalEl);
                    editStockInModal.hide();
                    
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
})