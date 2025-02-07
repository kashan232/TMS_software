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
                            <h4 class="card-title">Order Details</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Order ID:</th>
                                    <td>{{ $order->id }}</td>
                                </tr>
                                <tr>
                                    <th>Customer Number:</th>
                                    <td>#{{ $order->customer_number }}</td>
                                </tr>
                                <tr>
                                    <th>Customer Name:</th>
                                    <td>{{ $order->customer->full_name }}</td>
                                </tr>
                                <tr>
                                    <th>Order Description:</th>
                                    <td>{{ $order->order_description }}</td>
                                </tr>
                                <tr>
                                    <th>Collection Date:</th>
                                    <td>{{ $order->collection_date }}</td>
                                </tr>
                                <tr>
                                    <th>Total Amount:</th>
                                    <td>PKR {{ array_sum($order->item_totals) }}</td>
                                </tr>
                            </table>
                            <hr>
                            <h5>Order Items</h5>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Cloth Type</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->cloth_types as $key => $cloth)
                                    <tr>
                                        <td>{{ $cloth }}</td>
                                        <td>{{ $order->quantities[$key] }}</td>
                                        <td>{{ $order->prices[$key] }}</td>
                                        <td>{{ $order->item_totals[$key] }}</td>
                                        <td>
                                            @php
                                            $status = $trackingStatuses[$cloth] ?? 'Pending';
                                            @endphp
                                            <span class="badge {{ $status == 'Completed' ? 'bg-success' : 'bg-warning text-dark' }}">
                                                {{ $status }}
                                            </span>
                                        </td>
                                        <td>
                                            <button class="track-order-btn btn btn-primary"
                                                data-order-id="{{ $order->id }}"
                                                data-cloth-type="{{ $cloth }}"
                                                data-quantity="{{ $order->quantities[$key] }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#trackOrderModal">
                                                Track Order
                                            </button>
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


    <!-- Track Order Modal -->
    <!-- Track Order Modal -->
    <div class="modal fade" id="trackOrderModal" tabindex="-1" aria-labelledby="trackOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="trackOrderModalLabel">Update Order Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="trackOrderForm">
                        @csrf
                        <input type="hidden" name="order_id" id="order_id">
                        <input type="hidden" name="cloth_type" id="cloth_type">
                        <input type="hidden" name="quantity" id="quantity">

                        <label for="order_status" class="form-label">Order Tracker Status:</label>
                        <select name="order_status" id="order_status" class="form-control">
                            <option value="Cutting">Cutting</option>
                            <option value="Sewing">Sewing</option>
                            <option value="Tailor Processing">Tailor Processing</option>
                            <option value="Completed">Completed</option>
                        </select>

                        <div class="mt-3 text-end">
                            <button type="button" id="updateStatusBtn" class="btn btn-success">Update Status</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('main_includes.copyright_include')
    @include('main_includes.footer_include')

</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".track-order-btn").forEach(button => {
            button.addEventListener("click", function() {
                let orderId = this.getAttribute("data-order-id");
                let clothType = this.getAttribute("data-cloth-type");
                let quantity = this.getAttribute("data-quantity");

                document.getElementById("order_id").value = orderId;
                document.getElementById("cloth_type").value = clothType;
                document.getElementById("quantity").value = quantity;
            });
        });

        document.getElementById("updateStatusBtn").addEventListener("click", function() {
            let formData = new FormData(document.getElementById("trackOrderForm"));

            fetch("{{ route('update.order.status') }}", {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                    }
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Order status updated successfully!");
                        location.reload();
                    } else {
                        alert("Error: " + data.error);
                    }
                }).catch(error => console.error("Error:", error));
        });
    });
</script>