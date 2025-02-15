@include('main_includes.header_include')

<div id="main-wrapper">
    @include('main_includes.navbar_include')
    @include('main_includes.admin_sidebar_include')

    <div class="content-body rightside-event">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Customer Report</h4>
                        </div>
                        <div class="card-body">
                            <div class="basic-form">
                                <form id="searchForm">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="customer">Select Customer</label>
                                                <select name="customer" id="customer" class="form-control">
                                                    <option value="">Select Customer</option>
                                                    @foreach($Customers as $Customer)
                                                    <option value="{{ $Customer->customer_number }}">#{{ $Customer->customer_number }}&nbsp;{{ $Customer->full_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="start_date">Start Date</label>
                                                <input type="date" class="form-control" id="start_date" name="start_date">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="end_date">End Date</label>
                                                <input type="date" class="form-control" id="end_date" name="end_date">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-primary" id="searchButton">Search</button>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h5>Customer Report</h5>
                                <button class="btn btn-danger" id="exportPdf">Export PDF</button>
                            </div>
                            <!-- <div class="text-center">
                                <img src="/TMS_software/public/images/TMS_black_logo.png" alt="image" style="width: 150px;">
                                <h2 class="fw-bold">Fancy Dress Tailor</h2>
                                <hr style="width: 50%; margin: auto; border-top: 2px solid black;">
                            </div> -->

                            
                            <div id="customerDetails" class="mt-4 p-4 border rounded shadow-sm bg-light d-none">
                                <h4 class="text-center text-uppercase fw-bold fw-bold text-primary mb-3">Customer Report</h4>

                                <div class="px-3">
                                    <p class="mb-2"><strong>Customer Number:</strong> <span id="customerNumber"></span></p>
                                    <p class="mb-2"><strong>Full Name:</strong> <span id="customerName"></span></p>
                                    <p class="mb-2"><strong>Phone:</strong> <span id="customerPhone"></span></p>
                                    <p class="mb-0"><strong>Address:</strong> <span id="customerAddress"></span></p>
                                </div>
                            </div>


                            <table class="table mt-4" id="reportTableContainer">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Customer Name</th>
                                        <th>Receiving Date</th>
                                        <th>Cloth Type</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Item Total</th>
                                        <th>Subtotal</th>
                                        <th>Discount</th>
                                        <th>Net Total</th>
                                        <th>Advance Paid</th>
                                        <th>Remaining</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="reportTable">
                                    {{-- Data AJAX se load hoga --}}
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="6"><strong>Total Orders:</strong></td>
                                        <td id="totalOrders">0</td>
                                    </tr>
                                    <tr>
                                        <td colspan="6"><strong>Completed Orders:</strong></td>
                                        <td id="completedOrders">0</td>
                                    </tr>
                                    <tr>
                                        <td colspan="6"><strong>Ready Orders:</strong></td>
                                        <td id="readyOrders">0</td>
                                    </tr>
                                    <tr>
                                        <td colspan="6"><strong>Assigned Orders:</strong></td>
                                        <td id="assignedOrders">0</td>
                                    </tr>
                                    <tr>
                                        <td colspan="6"><strong>Total Price:</strong></td>
                                        <td id="totalPrice">0</td>
                                    </tr>
                                    <tr>
                                        <td colspan="6"><strong>Total Paid:</strong></td>
                                        <td id="totalPaid">0</td>
                                    </tr>
                                    <tr>
                                        <td colspan="6"><strong>Remaining Balance:</strong></td>
                                        <td id="remainingBalance">0</td>
                                    </tr>
                                </tfoot>
                            </table>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('main_includes.copyright_include')
    @include('main_includes.footer_include')
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
    $(document).ready(function() {

        $('#customer').on('change', function() {
            let customerId = $(this).val();

            if (customerId) {
                $.ajax({
                    url: "{{ route('get-customer-details') }}", // Laravel Route jo customer ki details fetch kare
                    type: "GET",
                    data: {
                        customer_number: customerId
                    },
                    success: function(data) {
                        if (data) {
                            $('#customerNumber').text(data.customer_number);
                            $('#customerName').text(data.full_name);
                            $('#customerPhone').text(data.phone_number);
                            $('#customerAddress').text(data.address);
                            $('#customerDetails').removeClass('d-none'); // Show details div
                            // Correct Image Path
                            if (data.image) {
                                let imagePath = "/TMS_software/public/customer_images/" + data.image;
                                $('#customerImage').attr('src', imagePath);
                            } else {
                                $('#customerImage').attr('src', '/TMS_software/public/customer_images/default-avatar.jpg');
                            }

                        }
                    }
                });
            } else {
                $('#customerDetails').addClass('d-none');
            }
        });

        $('#searchButton').on('click', function() {
            let customerId = $('#customer').val();
            let startDate = $('#start_date').val();
            let endDate = $('#end_date').val();

            $.ajax({
                url: "{{ route('fetch-customer-report') }}",
                type: "GET",
                data: {
                    customer_number: customerId,
                    week_start_date: startDate,
                    week_end_date: endDate
                },
                success: function(data) {
                    let rows = "";
                    if (data.orders.length === 0) {
                        rows = `<tr><td colspan="12" class="text-center">No data found</td></tr>`;
                    } else {
                        $.each(data.orders, function(index, order) {
                            let clothType = JSON.parse(order.cloth_type).join(", ");
                            let price = JSON.parse(order.price).join(", ");
                            let quantity = JSON.parse(order.quantity).join(", ");
                            let itemTotal = JSON.parse(order.item_total).join(", ");

                            rows += `<tr>
                            <td>${index + 1}</td>
                            <td>#${order.customer_number}</td>
                            <td>${order.order_receiving_date}</td>
                            <td>${clothType}</td>
                            <td>${price}</td>
                            <td>${quantity}</td>
                            <td>${itemTotal}</td>
                            <td>${order.sub_total}</td>
                            <td>${order.discount}</td>
                            <td>${order.net_total}</td>
                            <td>${order.advance_paid}</td>
                            <td>${order.remaining}</td>
                            <td>${order.status}</td>
                        </tr>`;
                        });
                    }
                    $('#reportTable').html(rows);

                    // Summary Data
                    $('#totalOrders').text(data.total_orders);
                    $('#completedOrders').text(data.completed_orders);
                    $('#readyOrders').text(data.ready_orders);
                    $('#assignedOrders').text(data.assigned_orders);
                    $('#totalPrice').text(data.total_price);
                    $('#totalPaid').text(data.total_paid);
                    $('#remainingBalance').text(data.remaining_balance);
                }
            });
        });

        // Export to PDF including Customer Details
        $('#exportPdf').on('click', function() {
            const {
                jsPDF
            } = window.jspdf;
            let pdf = new jsPDF('p', 'mm', 'a4');

            html2canvas(document.getElementById("customerDetails")).then(canvas => {
                let imgData = canvas.toDataURL("image/png");
                let imgWidth = 190;
                let imgHeight = (canvas.height * imgWidth) / canvas.width;
                pdf.addImage(imgData, 'PNG', 10, 10, imgWidth, imgHeight);

                html2canvas(document.getElementById("reportTableContainer")).then(canvas2 => {
                    let imgData2 = canvas2.toDataURL("image/png");
                    let imgHeight2 = (canvas2.height * imgWidth) / canvas2.width;
                    pdf.addImage(imgData2, 'PNG', 10, imgHeight + 20, imgWidth, imgHeight2);
                    pdf.save("customer_report.pdf");
                });
            });
        });

    });
</script>