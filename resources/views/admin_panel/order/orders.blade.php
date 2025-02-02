@include('main_includes.header_include')
<div id="main-wrapper">
    @include('main_includes.navbar_include')
    @include('main_includes.admin_sidebar_include')

    <div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Make Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="paymentForm">
                        <input type="hidden" id="order_id">

                        <div class="mb-3">
                            <label>Customer Number:</label>
                            <input type="text" id="customer_number" class="form-control" readonly>
                        </div>

                        <div class="mb-3">
                            <label>Remaining Amount:</label>
                            <input type="text" id="remaining_amount" class="form-control" readonly>
                        </div>

                        <div class="mb-3">
                            <label>Amount to Pay:</label>
                            <input type="number" id="pay_amount" class="form-control" min="1" required>
                        </div>

                        <div class="mb-3">
                            <label>New Remaining Amount:</label>
                            <input type="text" id="new_remaining" class="form-control" readonly>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit Payment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="content-body rightside-event">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Orders</h4>
                            <a href="{{ route('add-Order') }}" class="btn btn-primary">Add Orders</a>
                        </div>
                        <div class="card-body">
                            @if (session()->has('success'))
                            <div class="alert alert-success">
                                <strong>Success!</strong> {{ session('success') }}.
                            </div>
                            @endif
                            <div class="table-responsive ticket-table">
                                <table id="example" class="display dataTablesCard table-responsive-xl dataTable no-footer" style="min-width: 845px">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Customer Id</th>
                                            <th>Description</th>
                                            <th>Cloth Type</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Sub Total</th>
                                            <th>Discount</th>
                                            <th>Net Total</th>
                                            <th>Advance Paid</th>
                                            <th>Remaining</th>
                                            <th>Collection Date</th>
                                            <th>Status</th>
                                            <th>Action</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($Orders as $order)
                                        <tr>
                                            <td>{{ $order->id }}</td>
                                            <td>#{{ $order->customer_number }} <br> {{ $order->customer ? $order->customer->email : 'No Email Found' }}</td>
                                            <td>{{ $order->order_description }}</td>
                                            <td>
                                                @php
                                                $clothTypes = json_decode($order->cloth_type, true);
                                                @endphp
                                                @if ($clothTypes)
                                                {{ implode(', ', $clothTypes) }}
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                $prices = json_decode($order->price, true);
                                                @endphp
                                                @if ($prices)
                                                {{ implode(', ', $prices) }}
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                $quantities = json_decode($order->quantity, true);
                                                @endphp
                                                @if ($quantities)
                                                {{ implode(', ', $quantities) }}
                                                @endif
                                            </td>
                                            <td>{{ $order->sub_total }}</td>
                                            <td>{{ $order->discount }}</td>
                                            <td>{{ $order->net_total }}</td>
                                            <td>{{ $order->advance_paid }}</td>
                                            <td>{{ $order->remaining }}</td>
                                            <td>{{ $order->collection_date }}</td>
                                            <td class="text-center">
                                                @php
                                                $statusColors = [
                                                'Order Received' => 'primary', // Blue
                                                'Order Updated' => 'danger', // Red
                                                'Ready' => 'warning', // Yellow
                                                'Delivered' => 'success', // Green
                                                ];
                                                @endphp

                                                <span class="btn btn-{{ $statusColors[$order->status] ?? 'secondary' }} btn-sm w-100 text-nowrap">
                                                    {{ $order->status }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-2">
                                                    @if ($order->remaining > 0)
                                                    <!-- Payment Button -->
                                                    <a class="btn btn-success btn-xs d-flex align-items-center gap-1 open-payment-modal"
                                                        data-id="{{ $order->id }}"
                                                        
                                                        data-customer="{{ $order->customer_number }}"
                                                        data-remaining="{{ $order->remaining }}"
                                                        data-bs-toggle="tooltip"
                                                        title="Make Payment">
                                                        <i class="fas fa-money-bill-wave"></i>
                                                    </a>
                                                    @else
                                                    <!-- Hidden Placeholder for Alignment -->
                                                    <span class="btn btn-success btn-xs invisible" style="width: 32px;"></span>
                                                    @endif

                                                    <!-- Edit Button -->
                                                    <a href="{{ route('order.edit', $order->id) }}"
                                                        class="btn btn-primary btn-xs d-flex align-items-center gap-1"
                                                        data-bs-toggle="tooltip"
                                                        title="Edit Order">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                    <!-- Receipt Button -->
                                                    <a href="{{ route('order.receipt', $order->id) }}"
                                                        class="btn btn-dark btn-xs d-flex align-items-center gap-1"
                                                        data-bs-toggle="tooltip"
                                                        title="View Receipt">
                                                        <i class="fas fa-file-invoice"></i>
                                                    </a>

                                                    <!-- Order Status Button -->
                                                    <button class="btn btn-warning btn-xs d-flex align-items-center gap-1 open-status-modal"
                                                        data-id="{{ $order->id }}"
                                                        data-email="{{ $order->customer->email }}"
                                                        data-status="{{ $order->status }}"
                                                        data-description="{{ $order->delivery_description }}"
                                                        data-bs-toggle="tooltip"
                                                        title="Order Status">
                                                        <i class="fas fa-tasks"></i>
                                                    </button>
                                                </div>
                                            </td>



                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Status Modal -->
    <div class="modal fade" id="orderStatusModal" tabindex="-1" aria-labelledby="orderStatusLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Order Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="orderId">
                    <input type="text" id="customerEmail">
                    <label for="orderStatus" class="form-label">Select Status</label>
                    <select id="orderStatus" class="form-select">
                        <option value="Ready">Ready</option>
                        <option value="Delivered">Delivered</option>
                    </select>

                    <label for="deliveryDescription" class="form-label mt-3">Enter Delivery Details</label>
                    <textarea id="deliveryDescription" class="form-control" rows="3"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success save-order-status">Save</button>
                </div>
            </div>
        </div>
    </div>


    @include('main_includes.copyright_include')
    @include('main_includes.footer_include')

</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function(tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('.open-payment-modal').click(function() {
            let orderId = $(this).data('id');
            let customerNumber = $(this).data('customer');
            let remaining = $(this).data('remaining');
            
            $('#order_id').val(orderId);
            $('#customer_number').val(customerNumber);
            $('#remaining_amount').val(remaining);
            $('#pay_amount').val('');
            $('#new_remaining').val(remaining); // New Remaining Field Updated
            $('#paymentModal').modal('show');
        });

      

        // Calculate Remaining Amount Live
        $('#pay_amount').on('input', function() {
            let remaining = parseFloat($('#remaining_amount').val());
            let payAmount = parseFloat($(this).val()) || 0;
            let newRemaining = remaining - payAmount;

            if (newRemaining < 0) {
                $('#new_remaining').val("Invalid Amount!");
            } else {
                $('#new_remaining').val(newRemaining);
            }
        });

        $('#paymentForm').submit(function(e) {
            e.preventDefault();

            let orderId = $('#order_id').val();
            let payAmount = parseFloat($('#pay_amount').val());
            let remainingAmount = parseFloat($('#remaining_amount').val());

            if (payAmount > remainingAmount) {
                alert('Paid amount cannot be more than remaining amount.');
                return;
            }

            $.ajax({
                url: "{{ route('payment.update') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    order_id: orderId,
                    pay_amount: payAmount
                },
                success: function(response) {
                    alert('Payment Successful!');
                    location.reload();
                },
                error: function() {
                    alert('Something went wrong!');
                }
            });
        });

        $(document).ready(function() {
            // Open Order Status Modal
            $(".open-status-modal").click(function() {
                let orderId = $(this).data("id");
                let currentStatus = $(this).data("status");
                let description = $(this).data("description");
                let customerEmail = $(this).data('email');

                $("#orderId").val(orderId);
                $("#orderStatus").val(currentStatus);
                $("#deliveryDescription").val(description);
                $('#customerEmail').val(customerEmail);

                $("#orderStatusModal").modal("show");
            });

            // Save Order Status and Delivery Description
            $(".save-order-status").click(function() {
                let orderId = $("#orderId").val();
                let status = $("#orderStatus").val();
                let description = $("#deliveryDescription").val();

                $.ajax({
                    url: "{{ route('order.updateStatus') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: orderId,
                        status: status,
                        description: description
                    },
                    success: function(response) {
                        $("#orderStatusModal").modal("hide");
                        location.reload();
                    },
                    error: function() {
                        alert("Error updating order!");
                    }
                });
            });
        });

    });
</script>