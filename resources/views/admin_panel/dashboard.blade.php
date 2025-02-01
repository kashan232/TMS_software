@include('main_includes.header_include')
<div id="main-wrapper">
    @include('main_includes.navbar_include')

    @include('main_includes.admin_sidebar_include')
    <div class="content-body rightside-event">
        <!-- row -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-4 col-xxl-6 col-lg-6 col-sm-6">
                    <div class="widget-stat card shadow bg-dark text-white rounded-lg">
                        <div class="card-body p-4">
                            <div class="media">
                                <span class="me-3">
                                    <i class="fas fa-users fa-1x"></i>
                                </span>
                                <div class="media-body text-end">
                                    <p class="mb-1 fw-bold">Total Customers</p>
                                    <h3>{{ $totalCustomers }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-xxl-6 col-lg-6 col-sm-6">
                    <div class="widget-stat card shadow bg-info text-white rounded-lg">
                        <div class="card-body p-4">
                            <div class="media">
                                <span class="me-3">
                                    <i class="fas fa-boxes fa-1x"></i>
                                </span>
                                <div class="media-body text-end">
                                    <p class="mb-1 fw-bold">Total Orders</p>
                                    <h3>{{ $totalOrders }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-xxl-6 col-lg-6 col-sm-6">
                    <div class="widget-stat card shadow bg-primary text-dark rounded-lg">
                        <div class="card-body p-4">
                            <div class="media">
                                <span class="me-3">
                                    <i class="fas fa-calendar-week fa-1x"></i>
                                </span>
                                <div class="media-body text-end">
                                    <p class="mb-1 fw-bold">Order Received</p>
                                    <h3>{{ $orderReceived }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-xxl-6 col-lg-6 col-sm-6">
                    <div class="widget-stat card shadow bg-danger text-white rounded-lg">
                        <div class="card-body p-4">
                            <div class="media">
                                <span class="me-3">
                                    <i class="fas fa-calendar-check fa-1x"></i>
                                </span>
                                <div class="media-body text-end">
                                    <p class="mb-1 fw-bold">Order Updated</p>
                                    <h3>{{ $orderUpdated }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-xxl-6 col-lg-6 col-sm-6">
                    <div class="widget-stat card shadow bg-warning text-white rounded-lg">
                        <div class="card-body p-4">
                            <div class="media">
                                <span class="me-3">
                                    <i class="fas fa-bookmark fa-1x"></i>
                                </span>
                                <div class="media-body text-end">
                                    <p class="mb-1 fw-bold">Ready Orders</p>
                                    <h3>{{ $readyOrders }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-xxl-6 col-lg-6 col-sm-6">
                    <div class="widget-stat card shadow bg-success text-white rounded-lg">
                        <div class="card-body p-4">
                            <div class="media">
                                <span class="me-3">
                                    <i class="fas fa-check-square fa-1x"></i>
                                </span>
                                <div class="media-body text-end">
                                    <p class="mb-1 fw-bold">Order Delivered</p>
                                    <h3>{{ $deliveredOrders }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card shadow rounded-lg">
                        <div class="card-body">
                            <h4 class="text-center mb-3">Last 30 Days Orders & Revenue Overview</h4>
                            <div id="orderChart"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <!-- Yearly Chart -->
                    <div class="card shadow rounded-lg">
                        <div class="card-body">
                            <h4 class="text-center mb-3">Yearly Orders & Revenue Overview (2025)</h4>
                            <div id="yearlyOrderChart"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @include('main_includes.copyright_include')

</div>


@include('main_includes.footer_include')

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var options = {
            chart: {
                type: 'bar',
                height: 400,
                toolbar: {
                    show: false
                },
                background: '#ffffff',
            },
            colors: ['#4A90E2', '#4CAF50'], // Dark Blue for Orders & Dark Green for Amount
            series: [{
                    name: 'Total Orders',
                    data: @json($orderCounts)
                },
                {
                    name: 'Total Amount',
                    data: @json($orderAmounts)
                }
            ],
            xaxis: {
                categories: @json($orderLabels),
                labels: {
                    rotate: -45,
                    style: {
                        fontSize: '12px',
                        colors: '#333'
                    }
                }
            },
            yaxis: [{
                    title: {
                        text: 'Orders Count'
                    },
                },
                {
                    opposite: true,
                    title: {
                        text: 'Total Amount (PKR)'
                    },
                }
            ],
            grid: {
                borderColor: '#f1f1f1'
            },
            tooltip: {
                theme: 'light'
            }
        };

        var chart = new ApexCharts(document.querySelector("#orderChart"), options);
        chart.render();
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var options = {
            chart: {
                type: 'bar',
                height: 400,
                toolbar: { show: false },
                background: '#ffffff',
            },
            colors: ['#4A90E2', '#4CAF50'], // Dark Blue for Orders & Dark Green for Amount
            series: [
                {
                    name: 'Total Orders',
                    data: @json($yearlyOrderCounts)  // Array for each month's order count
                },
                {
                    name: 'Total Amount',
                    data: @json($yearlyOrderAmounts)  // Array for each month's total amount
                }
            ],
            xaxis: {
                categories: [
                    'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
                ],
                labels: {
                    rotate: -45,
                    style: { fontSize: '12px', colors: '#333' }
                }
            },
            yaxis: [
                {
                    title: { text: 'Orders Count' },
                },
                {
                    opposite: true,
                    title: { text: 'Total Amount (PKR)' },
                }
            ],
            grid: { borderColor: '#f1f1f1' },
            tooltip: { theme: 'light' }
        };

        var chart = new ApexCharts(document.querySelector("#yearlyOrderChart"), options);
        chart.render();
    });
</script>