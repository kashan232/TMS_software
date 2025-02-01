@include('main_includes.header_include')
<div id="main-wrapper">
    @include('main_includes.navbar_include')

    @include('main_includes.admin_sidebar_include')
    <div class="content-body rightside-event">
        <!-- row -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-4 col-xxl-6 col-lg-6 col-sm-6">
                    <div class="widget-stat card bg-info">
                        <div class="card-body p-4">
                            <div class="media">
                                <span class="me-3">
                                    <i class="flaticon-381-heart"></i>
                                </span>
                                <div class="media-body text-white text-end">
                                    <p class="mb-1">Total Customers</p>
                                    <h3 class="text-white">1200</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-xxl-6 col-lg-6 col-sm-6">
                    <div class="widget-stat card bg-success">
                        <div class="card-body p-4">
                            <div class="media">
                                <span class="me-3">
                                    <i class="flaticon-381-diamond"></i>
                                </span>
                                <div class="media-body text-white text-end">
                                    <p class="mb-1">Active Order</p>
                                    <h3 class="text-white">50</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-xxl-6 col-lg-6 col-sm-6">
                    <div class="widget-stat card bg-danger">
                        <div class="card-body  p-4">
                            <div class="media">
                                <span class="me-3">
                                    <i class="flaticon-381-calendar-1"></i>
                                </span>
                                <div class="media-body text-white text-end">
                                    <p class="mb-1">Order Delivered</p>
                                    <h3 class="text-white">200</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div id="user-activity" class="card">
                        <div class="card-header border-0 pb-0 d-sm-flex d-block">
                            <div>
                                <h4 class="card-title mb-1">Sales Revenue</h4>
                            </div>
                            <div class="card-action card-tabs mt-3 mt-sm-0">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#user" role="tab" aria-selected="true">
                                            Monthly
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#user" role="tab" aria-selected="false">
                                            Weekly
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#user" role="tab" aria-selected="false">
                                            Today
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade active show" id="user" role="tabpanel">
                                    <div class="chartjs-size-monitor">
                                        <div class="chartjs-size-monitor-expand">
                                            <div class=""></div>
                                        </div>
                                        <div class="chartjs-size-monitor-shrink">
                                            <div class=""></div>
                                        </div>
                                    </div>
                                    <canvas id="activityLine" class="chartjs chartjs-render-monitor" height="350" style="display: block; width: 1041px; height: 350px;" width="1041"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

    @include('main_includes.copyright_include')

</div>


@include('main_includes.footer_include')