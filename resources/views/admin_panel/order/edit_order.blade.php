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
                            <h4 class="card-title">Edit Order</h4>
                        </div>
                        <div class="card-body">
                            <div class="basic-form">
                                @if (session()->has('success'))
                                <div class="alert alert-success">
                                    <strong>Success!</strong> {{ session('success') }}.
                                </div>
                                @endif
                                <form action="{{ route('orders.update', $order->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row mb-4">
                                        <div class="col-md-4">
                                            <label for="customerNumber" class="form-label">Select Customer ID</label>
                                            <input type="text" id="customer_number" class="form-control" value="{{ $order->customer->customer_number }}" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="customerName" class="form-label">Customer Name</label>
                                            <input type="text" id="customerName" class="form-control" value="{{ $order->customer->full_name }}" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="customerPhone" class="form-label">Customer Phone</label>
                                            <input type="text" id="customerPhone" class="form-control" value="{{ $order->customer->phone_number }}" readonly>
                                        </div>
                                    </div>

                                    <!-- Order Details -->
                                    <div class="row mb-4">
                                        <div class="col-md-4">
                                            <label for="orderReceivingDate" class="form-label">Order Receiving Date</label>
                                            <input type="date" name="order_receiving_date" id="orderReceivingDate" class="form-control" value="{{ $order->order_receiving_date }}" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="orderReceiver" class="form-label">Order Received By</label>
                                            <input type="text" name="order_received_by" id="orderReceiver" class="form-control" value="{{ Auth::user()->name }}" readonly>
                                        </div>
                                    </div>

                                    <!-- Order Description -->
                                    <div class="row mb-4">
                                        <div class="col-md-12">
                                            <label for="order_description" class="form-label">Order Description</label>
                                            <textarea name="order_description" id="order_description" class="form-control">{{ $order->order_description }}</textarea>
                                        </div>
                                    </div>

                                    <!-- Order Items -->
                                    <h4>Order Details</h4>
                                    <table class="table" id="orderTable">
                                        <thead>
                                            <tr>
                                                <th>Cloth Type</th>
                                                <th>Price</th>
                                                <th>Quantity</th>
                                                <th>Item Total</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($order->cloth_type as $key => $clothType)
                                            <tr>
                                                <td>
                                                    <select name="cloth_type[]" class="form-control cloth-type" onchange="updatePrice(this)" required>
                                                        <option value="">Select Cloth Type</option>
                                                        @foreach ($clothTypes as $type)
                                                        <option value="{{ $type->cloth_type_name }}" data-price="{{ $type->Price }}"
                                                            @if ($clothType==$type->cloth_type_name) selected @endif>
                                                            {{ $type->cloth_type_name }} - Rs. {{ $type->Price }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" name="price[]" class="form-control price" value="{{ $order->price[$key] }}" required oninput="calculateTotal(this)">
                                                </td>
                                                <td>
                                                    <input type="number" name="quantity[]" class="form-control quantity" value="{{ $order->quantity[$key] }}" required oninput="calculateTotal(this)">
                                                </td>
                                                <td>
                                                    <input type="number" name="item_total[]" class="form-control item-total" value="{{ $order->item_total[$key] }}" readonly>
                                                </td>
                                                <td><button type="button" class="btn btn-danger" onclick="removeRow(this)">Remove</button></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <button type="button" class="btn btn-success mb-3" onclick="addRow()">Add More</button>

                                    <!-- Order Summary -->
                                    <div class="row mb-4">
                                        <div class="col-md-3">
                                            <label for="subTotal" class="form-label">Sub Total</label>
                                            <input type="number" id="subTotal" name="sub_total" class="form-control" value="{{ $order->sub_total }}" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="discount" class="form-label">Discount</label>
                                            <input type="number" id="discount" name="discount" class="form-control" value="{{ $order->discount }}" oninput="calculateNetTotal()">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="netTotal" class="form-label">Net Total</label>
                                            <input type="number" id="netTotal" name="net_total" class="form-control" value="{{ $order->net_total }}" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="advancePaid" class="form-label">Advance Paid</label>
                                            <input type="number" id="advancePaid" name="advance_paid" class="form-control" value="{{ $order->advance_paid }}" oninput="calculateRemaining()">
                                        </div>
                                        <div class="col-md-3 mt-2">
                                            <label for="remaining" class="form-label">Remaining</label>
                                            <input type="number" id="remaining" name="remaining" class="form-control" value="{{ $order->remaining }}" readonly>
                                        </div>
                                        <div class="col-md-3 mt-2">
                                            <label for="collectionDate" class="form-label">Date of Collection</label>
                                            <input type="date" id="collectionDate" name="collection_date" class="form-control" value="{{ $order->collection_date }}" required>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Update Order</button>
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
        calculateSubTotal(); // Recalculate when page loads with existing data
    });

    function updatePrice(selectElement) {
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        const price = selectedOption.getAttribute('data-price');
        const row = selectElement.closest('tr');
        const priceInput = row.querySelector('.price');
        priceInput.value = price || '';
        calculateTotal(row); // Recalculate the total after selecting the price
    }

    function calculateTotal(inputElement) {
        const row = inputElement.closest('tr');
        const price = parseFloat(row.querySelector('.price').value) || 0;
        const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
        const itemTotal = Math.round(price * quantity); // Round to whole number
        row.querySelector('.item-total').value = itemTotal;
        calculateSubTotal(); // Recalculate the subtotal
    }

    function calculateSubTotal() {
        let subTotal = 0;
        document.querySelectorAll('.item-total').forEach(input => {
            subTotal += parseFloat(input.value) || 0;
        });
        document.getElementById('subTotal').value = Math.round(subTotal); // Round to whole number
        calculateNetTotal();
    }

    function calculateNetTotal() {
        const subTotal = parseFloat(document.getElementById('subTotal').value) || 0;
        const discount = parseFloat(document.getElementById('discount').value) || 0;
        const netTotal = Math.round(subTotal - discount); // Round to whole number
        document.getElementById('netTotal').value = netTotal;
        calculateRemaining();
    }

    function calculateRemaining() {
        const netTotal = parseFloat(document.getElementById('netTotal').value) || 0;
        const advancePaid = parseFloat(document.getElementById('advancePaid').value) || 0;
        const remaining = Math.round(netTotal - advancePaid); // Round to whole number
        document.getElementById('remaining').value = remaining;
    }

    function addRow() {
        const tableBody = document.querySelector('#orderTable tbody');
        const firstRow = tableBody.rows[0];
        const newRow = firstRow.cloneNode(true);

        newRow.querySelectorAll('input').forEach(input => (input.value = ''));
        newRow.querySelector('select').value = '';

        const oldSelect2Wrapper = newRow.querySelector('.select2');
        if (oldSelect2Wrapper) oldSelect2Wrapper.remove();

        tableBody.appendChild(newRow);
        $(newRow.querySelector('.cloth-type')).select2();
    }

    function removeRow(button) {
        const row = button.closest('tr');
        row.remove();
        calculateSubTotal();
    }
</script>