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
                            <h4 class="card-title">Order Record</h4>
                        </div>
                        <div class="card-body">
                            <div class="basic-form">
                                <form id="searchForm">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="week_start_date"> Start Date</label>
                                                <input type="date" class="form-control" id="week_start_date">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="week_end_date"> End Date</label>
                                                <input type="date" class="form-control" id="week_end_date">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-primary" id="searchButton">Search</button>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h5>Order Records</h5>
                                <button class="btn btn-danger" id="exportPdf">Export PDF</button>
                            </div>

                            <div class="table-responsive mt-4">
                                <table class="table table-bordered">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Order ID</th>
                                            <th>Customer</th>
                                            <th>Name</th>
                                            <th>Receiving Date</th>
                                            <th>Status</th>
                                            <th>Assigned To</th>
                                            <th>Net Total</th>
                                            <th>Paid</th>
                                            <th>Remaining</th>
                                        </tr>
                                    </thead>
                                    <tbody id="orderReportTable">
                                        {{-- Data AJAX se load hoga --}}
                                    </tbody>
                                    <tfoot class="bg-light">
                                        <tr>
                                            <td colspan="3"><b>Total Orders:</b> <span id="totalOrders">0</span></td>
                                            <td colspan="2"><b>Delivered:</b> <span id="totalDelivered">0</span></td>
                                            <td colspan="2"><b>Ready:</b> <span id="totalReady">0</span></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"><b>Assigned:</b> <span id="totalAssigned">0</span></td>
                                            <td colspan="2"><b>Received:</b> <span id="totalReceived">0</span></td>
                                            <td colspan="2"><b>Total Amount:</b> <span id="totalAmount">0</span></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"><b>Total Paid:</b> <span id="totalPaid">0</span></td>
                                            <td colspan="3"><b>Total Remaining:</b> <span id="totalRemaining">0</span></td>
                                        </tr>
                                    </tfoot>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
    $(document).ready(function() {
        $('#searchButton').on('click', function() {
            let startDate = $('#week_start_date').val();
            let endDate = $('#week_end_date').val();

            $.ajax({
                url: "{{ route('fetch-order-report') }}",
                type: "GET",
                data: {
                    week_start_date: startDate,
                    week_end_date: endDate
                },
                success: function(data) {
                    let rows = "";
                    $.each(data.orders, function(index, order) {
                        let customerName = order.customer ? order.customer.full_name : 'N/A';

                        rows += `<tr>
                        <td>${index + 1}</td>
                        <td>${order.id}</td>
                        <td>#${order.customer_number}</td>
                        <td>${customerName}</td> 
                        <td>${order.order_receiving_date}</td>
                        <td>${order.status}</td>
                        <td>${order.assign_name ?? 'N/A'}</td>
                        <td>${order.net_total}</td>
                        <td>${order.advance_paid}</td>
                        <td>${order.remaining}</td>
                    </tr>`;
                    });

                    $('#orderReportTable').html(rows);

                    // Summary Update
                    $('#totalOrders').text(data.summary.totalOrders);
                    $('#totalDelivered').text(data.summary.delivered);
                    $('#totalReady').text(data.summary.ready);
                    $('#totalAssigned').text(data.summary.assigned);
                    $('#totalReceived').text(data.summary.received);
                    $('#totalAmount').text(data.summary.totalAmount);
                    $('#totalPaid').text(data.summary.totalPaid);
                    $('#totalRemaining').text(data.summary.totalRemaining);
                }
            });
        });

        // PDF Export Function
        $('#exportPdf').on('click', function() {
            const {
                jsPDF
            } = window.jspdf;
            let doc = new jsPDF('p', 'pt', 'a4');

            doc.setFont("helvetica", "bold");
            doc.setFontSize(18);
            doc.text("Order Report", 200, 40);

            let customerDetails = `Customer Name: ${$('#orderReportTable tr:first td:nth-child(4)').text()}`;
            doc.setFontSize(12);
            doc.text(customerDetails, 10, 70);

            html2canvas(document.querySelector(".table-responsive")).then(canvas => {
                let imgData = canvas.toDataURL('image/png');
                let imgWidth = 595;
                let imgHeight = (canvas.height * imgWidth) / canvas.width;

                doc.addImage(imgData, 'PNG', 10, 90, imgWidth - 20, imgHeight);
                doc.save("Order_Report.pdf");
            });
        });
    });
</script>