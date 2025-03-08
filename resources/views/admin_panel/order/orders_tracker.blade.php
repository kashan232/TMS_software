@include('main_includes.header_include')
<div id="main-wrapper">
    @include('main_includes.navbar_include')
    @include('main_includes.admin_sidebar_include')
    <style>
        .bottom--impo th {
            padding-right: 28px !important;
            font-size: 22px !important;
            color: #000 !important;
            text-align: center;
        }

        .h-5 {
            width: 30px;
        }

        .leading-5 {
            padding: 20px 0px;
        }

        .leading-5 span:nth-child(3) {
            color: red;
            font-weight: 500;
        }
    </style>
    <div class="content-body rightside-event">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Orders Tracker</h4>
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
                                            <th>Quantity</th>
                                            <th>Collection Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($Orders as $order)
                                        <tr>
                                            <td>{{ $order->id }}</td>
                                            <td>#{{ $order->customer_number }} <br> {{ $order->customer->full_name }} </td>
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
                                                $quantities = json_decode($order->quantity, true);
                                                @endphp
                                                @if ($quantities)
                                                {{ implode(', ', $quantities) }}
                                                @endif
                                            </td>
                                            <td>{{ $order->collection_date }}</td>
                                            <td>
                                                @if($order->status === 'Order Received')
                                                <span class="btn btn-sm btn-success" style="font-size: 10px!important;">{{ $order->status }}</span>
                                                @else
                                                <span class="btn btn-sm btn-warning" style="font-size: 10px!important;">{{ $order->status }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('order.track.view', $order->id) }}" class="btn btn-primary btn-sm text-white">View</a>
                                            </td>

                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="py-5">
                                    {{ $Orders->appends(request()->input())->links() }}
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