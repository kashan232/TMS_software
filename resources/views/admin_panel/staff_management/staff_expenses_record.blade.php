@include('main_includes.header_include')

<!--**********************************
        Main wrapper start
    ***********************************-->
<div id="main-wrapper">

    <!--**********************************
            Nav header start
        ***********************************-->

    @include('main_includes.navbar_include')

    @include('main_includes.admin_sidebar_include')
    <!--**********************************
            Content body start
        ***********************************-->
    <div class="content-body rightside-event">
        <!-- row -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Staff Expense Record</h4>
                            <!-- Button to trigger Add Modal -->
                            <a href="{{ route('staff.expenses.index') }}" class="btn btn-primary">Add Staff Expense Record</a>
                        </div>
                        <div class="card-body">
                            @if (session()->has('success'))
                            <div class="alert alert-success">
                                <strong>Success!</strong> {{ session('success') }}.
                            </div>
                            @endif
                            <div class="table-responsive ticket-table">
                                <div id="example_wrapper" class="dataTables_wrapper no-footer">
                                    <!-- <table id="example" class="display dataTablesCard table-responsive-xl dataTable no-footer" style="min-width: 845px" aria-describedby="example_info"> -->
                                    <table id="example" class="display dataTablesCard table-responsive-xl" style="min-width: 845px">
                                        <thead>
                                            <tr>
                                                <th>SNo</th>
                                                <th>Staff Member</th>
                                                <th>Week Start</th>
                                                <th>Week End</th>
                                                <th>Previous Balance</th>
                                                <th>Suits Stitched</th>
                                                <th>Rate Per Suit</th>
                                                <th>Total Amount</th>
                                                <th>Paid Amount</th>
                                                <th>Closing Balance</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($StaffExpenses as $Expense)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $Expense->staff->full_name ?? 'Unknown' }}</td>
                                                <td>{{ $Expense->week_start_date }}</td>
                                                <td>{{ $Expense->week_end_date }}</td>
                                                <td>{{ $Expense->previous_balance ?? 0 }}</td>
                                                <td>{{ $Expense->suits_stitched }}</td>
                                                <td>{{ $Expense->rate_per_suit }}</td>
                                                <td>{{ $Expense->total_amount }}</td>
                                                <td>{{ $Expense->paid_amount }}</td>
                                                <td>{{ $Expense->closing_balance }}</td>
                                                <td>
                                                    <a href="javascript:void(0);" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
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
    </div>

    @include('main_includes.copyright_include')


    @include('main_includes.footer_include')