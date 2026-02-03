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
    $('#addStockOutForm').on('submit', function(e) {
        e.preventDefault();

        const data = {
            item_id: parseInt($(this).find('[name="item_id"]').val()),
            quantity: parseInt($(this).find('[name="quantity"]').val()),
            requested_by: $(this).find('[name="requested_by"]').val(),
            purpose: $(this).find('[name="purpose"]').val(),
            released_by: $(this).find('[name="released_by"]').val()
        };
        console.log(data.item_id, data.quantity, data.requested_by, data.purpose, data.released_by);

        $.ajax({
            url: '/Inventory/api/stockOut',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function(res) {
                if (res.success) {
                    // Bootstrap 5 modal hide
                    const addStockOutModalEl = document.getElementById('addStockOutModal');
                    const addStockOutModal = bootstrap.Modal.getInstance(addStockOutModalEl)
                        || new bootstrap.Modal(addStockOutModalEl);
                    addStockOutModal.hide();

                    // Remove leftover backdrop if any
                    $('.modal-backdrop').remove();

                    // Reset form
                    $('#addStockOutForm')[0].reset();

                    // Optional: Reload a stock table if you have one
                    if ($.fn.DataTable.isDataTable('#stocksOutTable')) {
                        $('#stocksOutTable').DataTable().ajax.reload();
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
    const table = $('#stocksOutTable').DataTable({
        ajax: {
            url: '/Inventory/api/stockOut', // fetch JSON from API controller
            dataSrc: 'data'
        },
        columns: [
            { data: 'stock_out_id', visible: false }, // hide ID column
            { data: 'item_name' },
            { data: 'quantity' },
            { data: 'purpose' },
            { data: 'date_released' },
            { data: 'released_by' },
            {
                data: null,
                orderable: false,
                render: function (row) {
                    return `
                        <button 
                            class="btn btn-sm btn-warning editBtn"
                            data-bs-toggle="modal"
                            data-bs-target="#editStockInModal"
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
})