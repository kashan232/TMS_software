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
                            <h4 class="card-title">Asgin Order</h4>
                        </div>
                        <div class="card-body">
                            <div class="basic-form">
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label for="customer_number">Select Customer</label>
                                        <select name="customer_number" id="customer_number" class="form-control">
                                            <option selected disabled>Chose Customer</option>
                                            @foreach($customers as $customer)
                                            <option value="{{ $customer->customer_number }}">#{{ $customer->customer_number }}&nbsp;{{ $customer->full_name }} </option>
                                            @endforeach
                                        </select>
                                    </div>

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
                                        <label for="asign_date">Assign Date</label>
                                        <input type="date" name="asign_date" class="form-control" id="asign_date">
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <form id="assignOrderForm" method="POST">
                                            @csrf
                                            <input type="hidden" name="customer_number" id="selected_customer">
                                            <input type="hidden" name="hidden_asign_date" id="hidden_asign_date">
                                            <table class="table table-bordered" id="order_table">
                                                <thead>
                                                    <tr>
                                                        <th>Order ID</th>
                                                        <th>Cloth Type</th>
                                                        <th>Price</th>
                                                        <th>Quantity</th>
                                                        <th>Item Total</th>
                                                        <th>Status</th>
                                                        <th>Assign To</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                            <button type="submit" class="btn btn-primary">Assign Order</button>
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
        // Fetch orders on customer change
        function updateHiddenAssignDate() {
            var asignDate = $('#asign_date').val();
            $('#hidden_asign_date').val(asignDate);
        }

        // Jab bhi assign_date change ho to hidden field update ho
        $('#asign_date').on('change', function() {
            updateHiddenAssignDate();
        });

        $('#assignOrderForm').on('submit', function(e) {
            updateHiddenAssignDate();
        });

        $('#customer_number').on('change', function() {
            var customerNumber = $(this).val();
            var asignDate = $('#asign_date').val();
            $('#selected_customer').val(customerNumber);
            $('#hidden_asign_date').val(asignDate);
            if (customerNumber) {
                $.ajax({
                    url: "{{ route('fetch.orders') }}",
                    type: "GET",
                    data: {
                        customer_number: customerNumber,
                        asignDate: asignDate
                    },
                    success: function(response) {
                        $('#order_table tbody').empty();
                        if (response.orders.length > 0) {
                            $.each(response.orders, function(index, order) {
                                let clothTypes = JSON.parse(order.cloth_type);
                                let prices = JSON.parse(order.price);
                                let quantities = JSON.parse(order.quantity);
                                let itemTotals = JSON.parse(order.item_total);

                                for (let i = 0; i < clothTypes.length; i++) {
                                    let row = `<tr>
                                    <td><input type="hidden" name="order_id[]" value="${order.id}">${order.id}</td>
                                    <td><input type="hidden" name="cloth_type[]" value="${clothTypes[i]}">${clothTypes[i]}</td>
                                    <td>${prices[i]}</td>
                                    <td>${quantities[i]}</td>
                                    <td>${itemTotals[i]}</td>
                                    <td class="btn btn-primary bg-primary mt-2 text-white btn-sm">${order.status}</td>
                                    <td>
                                        <select name="staff_id[]" class="form-control staff_assignment">
                                            <option>Select Staff</option>
                                        </select>
                                    </td>

                                </tr>`;
                                    $('#order_table tbody').append(row);
                                }
                            });
                        } else {
                            $('#order_table tbody').append('<tr><td colspan="7">No orders found</td></tr>');
                        }
                    }
                });
            }
        });

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
                        $('.staff_assignment').empty().append('<option>Select Staff</option>');
                        $.each(response.staff, function(index, staff) {
                            $('.staff_assignment').append(`<option value="${staff.id}">${staff.full_name}</option>`);
                        });
                    }
                });
            }
        });

        $('#assignOrderForm').on('submit', function(e) {
            e.preventDefault();
            let formData = $(this).serialize();
            console.log(formData); // Debugging ke liye
            $.ajax({
                url: "{{ route('assign.orders') }}",
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