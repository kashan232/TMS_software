@include('main_includes.header_include')
<div id="main-wrapper">
    @include('main_includes.navbar_include')
    @include('main_includes.admin_sidebar_include')

    <div class="content-body rightside-event">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Staff Order Receiving</h4>
                        </div>
                        <div class="card-body">
                            <div class="basic-form">
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label for="designation">Select Designation</label>
                                        <select name="designation" id="designation" class="form-control">
                                            <option selected disabled>Select Designation</option>
                                            @foreach($StaffDesignations as $Designation)
                                            <option value="{{ $Designation->designations }}">{{ $Designation->designations }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3 col-md-6">
                                        <label for="staff_id">Select Staff</label>
                                        <select name="staff_id" id="staff_id" class="form-control staff_selection">
                                            <option selected disabled>Select Staff</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <form id="receiveOrderForm" method="POST">
                                            @csrf
                                            <table class="table table-bordered" id="order_table">
                                                <thead>
                                                    <tr>
                                                        <th>Select</th>
                                                        <th>Customer Number</th>
                                                        <th>Order ID</th>
                                                        <th>Cloth Type</th>
                                                        <th>Price</th>
                                                        <th>Quantity</th>
                                                        <th>Item Total</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                            <button type="submit" class="btn btn-success">Receive Orders</button>
                                        </form>
                                    </div>
                                </div>
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
    $(document).ready(function() {

        // Fetch staff on designation change
        $('#designation').on('change', function() {
            var designation = $(this).val();
            if (designation) {
                $.ajax({
                    url: "{{ route('fetch.staff') }}",
                    type: "GET",
                    data: {
                        designation: designation
                    },
                    success: function(response) {
                        $('#staff_id').empty().append('<option>Select Staff</option>');
                        $.each(response.staff, function(index, staff) {
                            $('#staff_id').append(`<option value="${staff.id}">${staff.full_name}</option>`);
                        });
                    }
                });
            }
        });

        $('#staff_id').on('change', function() {
            var staffId = $(this).val();
            if (staffId) {
                $.ajax({
                    url: "{{ route('fetch.assigned.orders') }}",
                    type: "GET",
                    data: {
                        staff_id: staffId
                    },
                    success: function(response) {
                        $('#order_table tbody').empty();
                        if (response.orders.length > 0) {
                            $.each(response.orders, function(index, assignment) {
                                let order = assignment.order;

                                // Decode JSON-encoded values if necessary
                                let clothType = JSON.parse(order.cloth_type || '""');
                                let price = JSON.parse(order.price || '""');
                                let quantity = JSON.parse(order.quantity || '""');
                                let itemTotal = JSON.parse(order.item_total || '""');

                                let row = `<tr>
                            <td><input type="checkbox" name="order_id[]" value="${order.id}"></td>
                            <td>#${order.customer_number}</td>
                            <td>${order.id}</td>
                            <td>${clothType}</td>
                            <td>${price}</td>
                            <td>${quantity}</td>
                            <td>${itemTotal}</td>
                            <td><span class="badge bg-dark">${order.status}</span></td>
                        </tr>`;
                                $('#order_table tbody').append(row);
                            });
                        } else {
                            $('#order_table tbody').append('<tr><td colspan="7">No assigned orders found</td></tr>');
                        }
                    }
                });
            }
        });

        // Submit form to receive orders
        $('#receiveOrderForm').on('submit', function(e) {
            e.preventDefault();
            let formData = $(this).serialize();
            $.ajax({
                url: "{{ route('receive.orders') }}",
                type: "POST",
                data: formData,
                success: function(response) {
                    alert(response.message);
                    location.reload();
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseJSON.message);
                }
            });
        });
    });
</script>