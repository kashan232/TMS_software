@include('main_includes.header_include')
<style>
    select {
        -webkit-appearance: auto;
        -moz-appearance: auto;
        appearance: auto;
    }

    .form-label {
        margin-bottom: 0.5rem;
    }

    .form-control {
        margin-bottom: 1rem;
    }

    .card-body {
        padding: 2rem;
    }

    .row {
        margin-bottom: 1.5rem;
    }

    /* Add spacing for table rows */
    table#orderTable td {
        padding: 0.75rem;
    }

    .mb-4 {
        margin-bottom: 1.5rem;
    }
</style>
<div id="main-wrapper">
    @include('main_includes.navbar_include')
    @include('main_includes.admin_sidebar_include')

    <div class="content-body rightside-event">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Add Order</h4>
                        </div>
                        <div class="card-body">
                            <div class="basic-form">
                                @if (session()->has('success'))
                                <div class="alert alert-success">
                                    <strong>Success!</strong> {{ session('success') }}.
                                </div>
                                @endif
                                <form action="{{ route('Order.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <!-- Customer Section -->
                                    <div class="row mb-4">
                                        <div class="col-md-4">
                                            <label for="customerNumber" class="form-label">Select Customer ID</label>
                                            <select name="customer_number" id="customerNumber" class="form-control" onchange="updateCustomerDetails(this)">
                                                <option selected disabled>Please select</option>
                                                @foreach ($customers as $customer)
                                                <option value="{{ $customer->customer_number }}"
                                                    data-name="{{ $customer->full_name }}"
                                                    data-phone="{{ $customer->phone_number }}">
                                                    #{{ $customer->customer_number }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="customerName" class="form-label">Customer Name</label>
                                            <input type="text" id="customerName" class="form-control" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="customerPhone" class="form-label">Customer Phone</label>
                                            <input type="text" id="customerPhone" class="form-control" readonly>
                                        </div>
                                    </div>

                                    <!-- Order Details -->
                                    <div class="row mb-4">
                                        <div class="col-md-4">
                                            <label for="orderReceivingDate" class="form-label">Order Receiving Date</label>
                                            <input type="date" name="order_receiving_date" id="orderReceivingDate" class="form-control" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="orderReceiver" class="form-label">Order Received By</label>
                                            <input type="text" name="order_received_by" id="orderReceiver" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-md-12">
                                            <label for="order_description" class="form-label">Order description</label>
                                            <textarea name="order_description" id="order_description" class="form-control"></textarea>
                                        </div>
                                    </div>

                                    <!-- Order Items -->
                                    <h4>Order Details</h4>
                                    <table id="orderTable" class="table">
                                        <thead>
                                            <tr>
                                                <th>Cloth Type</th>
                                                <th>Price (per item)</th>
                                                <th>Quantity</th>
                                                <th>Total</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <select name="cloth_type[]" class="form-control cloth-type" onchange="updatePrice(this)" required>
                                                        <option value="">Select Cloth Type</option>
                                                        @if (isset($clothTypes) && $clothTypes->count())
                                                        @foreach ($clothTypes as $type)
                                                        <option value="{{ $type->cloth_type_name }}" data-price="{{ $type->Price }}">
                                                            {{ $type->cloth_type_name }} - Rs. {{ $type->Price }}
                                                        </option>
                                                        @endforeach
                                                        @else
                                                        <option value="">No Cloth Types Available</option>
                                                        @endif
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" name="price[]" class="form-control price" readonly>
                                                </td>
                                                <td><input type="number" name="quantity[]" class="form-control quantity" oninput="calculateTotal(this)"></td>
                                                <td><input type="number" name="item_total[]" class="form-control item-total" readonly></td>
                                                <td><button type="button" class="btn btn-danger" onclick="removeRow(this)">Remove</button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <button type="button" class="btn btn-success mb-3" onclick="addRow()">Add More</button>

                                    <!-- Order Summary -->
                                    <div class="row mb-4">
                                        <div class="col-md-3">
                                            <label for="subTotal" class="form-label">Sub Total</label>
                                            <input type="number" id="subTotal" name="sub_total" class="form-control" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="discount" class="form-label">Discount</label>
                                            <input type="number" id="discount" name="discount" class="form-control" oninput="calculateNetTotal()">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="netTotal" class="form-label">Net Total</label>
                                            <input type="number" id="netTotal" name="net_total" class="form-control" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="advancePaid" class="form-label">Advance Paid</label>
                                            <input type="number" id="advancePaid" name="advance_paid" class="form-control" oninput="calculateRemaining()">
                                        </div>
                                        <div class="col-md-3 mt-2">
                                            <label for="remaining" class="form-label">Remaining</label>
                                            <input type="number" id="remaining" name="remaining" class="form-control" readonly>
                                        </div>
                                        <div class="col-md-3 mt-2">
                                            <label for="collectionDate" class="form-label">Date of Collection</label>
                                            <input type="date" id="collectionDate" name="collection_date" class="form-control" required>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Save Order</button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('main_includes.copyright_include')
    @include('main_includes.footer_include')
</div>

<script>
    jQuery(document).ready(function($) {
        // Initialize Select2 for existing elements
        $('.cloth-type').select2();
    });

    // Update customer details based on selected number
    function updateCustomerDetails(select) {
        const selectedOption = select.options[select.selectedIndex];
        document.getElementById('customerName').value = selectedOption.getAttribute('data-name');
        document.getElementById('customerPhone').value = selectedOption.getAttribute('data-phone');
    }

    function updatePrice(selectElement) {
        // Get the selected option
        const selectedOption = selectElement.options[selectElement.selectedIndex];

        // Get the price from the data attribute
        const price = selectedOption.getAttribute('data-price');

        // Find the corresponding price input field in the same row
        const row = selectElement.closest('tr');
        const priceInput = row.querySelector('.price');

        // Set the price value
        priceInput.value = price || '';
    }

    // Calculate total for a row
    function calculateTotal(input) {
        const row = input.closest('tr');
        const price = row.querySelector('.price').value || 0;
        const quantity = input.value || 0;
        row.querySelector('.item-total').value = price * quantity;
        calculateSubTotal();
    }

    // Calculate subtotal, net total, and remaining amount
    function calculateSubTotal() {
        let subTotal = 0;
        document.querySelectorAll('.item-total').forEach(input => {
            subTotal += parseFloat(input.value) || 0;
        });
        document.getElementById('subTotal').value = subTotal;
        calculateNetTotal();
    }

    function calculateNetTotal() {
        const subTotal = parseFloat(document.getElementById('subTotal').value) || 0;
        const discount = parseFloat(document.getElementById('discount').value) || 0;
        const netTotal = subTotal - discount;
        document.getElementById('netTotal').value = netTotal;
        calculateRemaining();
    }

    function calculateRemaining() {
        const netTotal = parseFloat(document.getElementById('netTotal').value) || 0;
        const advancePaid = parseFloat(document.getElementById('advancePaid').value) || 0;
        document.getElementById('remaining').value = netTotal - advancePaid;
    }

    // Add new row for order items
    function addRow() {
        const tableBody = document.querySelector('#orderTable tbody');
        const firstRow = tableBody.rows[0];

        // Clone the first row
        const newRow = firstRow.cloneNode(true);

        // Clear input values in the cloned row
        newRow.querySelectorAll('input').forEach(input => (input.value = ''));
        newRow.querySelector('select').value = '';

        // Remove the old Select2 wrapper
        const oldSelect2Wrapper = newRow.querySelector('.select2');
        if (oldSelect2Wrapper) oldSelect2Wrapper.remove();

        // Append the new row to the table
        tableBody.appendChild(newRow);

        // Reinitialize Select2 for the new row's select element
        $(newRow.querySelector('.cloth-type')).select2();
    }


    // Remove a row
    function removeRow(button) {
        const tableBody = document.querySelector('#orderTable tbody');

        // Check if there's more than one row
        if (tableBody.rows.length > 1) {
            const row = button.closest('tr');
            row.parentNode.removeChild(row);
        } else {
            // Clear the values of the only row if it's the last one
            const firstRow = tableBody.rows[0];
            firstRow.querySelectorAll('input').forEach(input => (input.value = ''));
            firstRow.querySelector('select').value = '';
        }

        calculateSubTotal();
    }
</script>