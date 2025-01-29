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
                                            <td>#{{ $order->customer_number }}</td>
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
                                            <td>
                                                @if($order->status === 'Order Received')
                                                <span class="btn btn-sm btn-success" style="font-size: 10px!important;">{{ $order->status }}</span>
                                                @else
                                                <span class="btn btn-sm btn-warning" style="font-size: 10px!important;">{{ $order->status }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div style="display: flex; gap: 5px;">
                                                    @if ($order->remaining > 0)
                                                    <a class="btn btn-sm btn-success open-payment-modal"
                                                        data-id="{{ $order->id }}"
                                                        data-customer="{{ $order->customer_number }}"
                                                        data-remaining="{{ $order->remaining }}">
                                                        Payment
                                                    </a>
                                                    @endif
                                                    <a href="{{ route('order.edit', $order->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                                    <a href="{{ route('order.receipt', $order->id) }}" class="btn btn-sm btn-dark">Receipt</a>
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

    @include('main_includes.copyright_include')
    @include('main_includes.footer_include')

</div>

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
    });
</script>