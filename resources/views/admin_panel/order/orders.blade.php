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
                                                <span class="badge badge-success">{{ $order->status }}</span>
                                                @else
                                                <span class="badge badge-warning">{{ $order->status }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div style="display: flex; gap: 5px;">
                                                    @if ($order->remaining > 0)
                                                    <a href="#" class="btn btn-sm btn-success"> Payment</a>
                                                    @endif
                                                    <a href="#" class="btn btn-sm btn-primary">Edit</a>
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