@include('main_includes.header_include')

<div id="main-wrapper">
    @include('main_includes.navbar_include')
    @include('main_includes.admin_sidebar_include')

    <div class="content-body rightside-event">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header d-print-none">
                            <h4 class="card-title">Order Receipt</h4>
                            <button onclick="window.print()" class="btn btn-primary">Print Receipt</button>
                        </div>
                        <div class="card-body print-area">
                            <div class="invoice-header p-4 border-bottom mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="logo">
                                        <img src="/TMS_software/public/images/TMS_black_logo.png" alt="Company Logo" style="height: 70px;">
                                    </div>
                                    <div class="text-center flex-grow-1">
                                        <h2 class="mb-1 text-uppercase fw-bold">Invoice</h2>
                                    </div>
                                    <div class="text-end">
                                        <h5 class="mb-1"><strong>Invoice Number:</strong>
                                            <span class="text-primary">INV-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                                        </h5>
                                        <p class="mb-1"><strong>Date:</strong> {{ \Carbon\Carbon::parse($order->created_at)->format('d M, Y') }}</p>
                                        <p class="mb-0"><strong>Time:</strong> {{ \Carbon\Carbon::parse($order->created_at)->format('h:i A') }}</p>
                                    </div>
                                </div>
                                <div class="border rounded p-3 bg-light">
                                    <h5 class="mb-2">Customer Details</h5>
                                    <p class="mb-1"><strong>Customer Number:</strong> # {{ $order->customer_number }}</p>
                                    <p class="mb-1"><strong>Name:</strong> {{ $order->customer->full_name ?? 'N/A' }}</p>
                                    <p class="mb-1"><strong>Phone:</strong> {{ $order->customer->phone_number ?? 'N/A' }}</p>
                                    <p class="mb-0"><strong>Address:</strong> {{ $order->customer->address ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <table class="table table-bordered">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Cloth Type</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Sub Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $clothTypes = json_decode($order->cloth_type, true);
                                    $prices = json_decode($order->price, true);
                                    $quantities = json_decode($order->quantity, true);
                                    @endphp
                                    @foreach ($clothTypes as $index => $clothType)
                                    <tr>
                                        <td>{{ $clothType }}</td>
                                        <td>{{ $prices[$index] ?? 0 }}</td>
                                        <td>{{ $quantities[$index] ?? 0 }}</td>
                                        <td>{{ ($prices[$index] ?? 0) * ($quantities[$index] ?? 0) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-light">
                                    <tr>
                                        <th colspan="3" class="text-end">Subtotal:</th>
                                        <td>{{ $order->sub_total }}</td>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-end">Discount:</th>
                                        <td>{{ $order->discount }}</td>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-end">Net Total:</th>
                                        <td>{{ $order->net_total }}</td>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-end">Advance Paid:</th>
                                        <td>{{ $order->advance_paid }}</td>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-end">Remaining:</th>
                                        <td>{{ $order->remaining }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                            <div class="text-center mt-3 border-top pt-2">
                                <p class="mb-0" style="font-size: 12px;">Designed & Developed by <strong>ProWave Software Solution</strong></p>
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

<!-- PRINT CSS -->
<style>
    @media print {
        body * {
            visibility: hidden;
        }

        .print-area,
        .print-area * {
            visibility: visible;
        }

        .print-area {
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            max-width: 800px;
            /* Set a fixed width */
            margin: 0 auto;
            background: white;
            padding: 30px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .d-print-none {
            display: none !important;
        }
    }
</style>