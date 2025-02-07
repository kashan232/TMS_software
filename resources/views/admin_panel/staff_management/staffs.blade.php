@include('main_includes.header_include')
<div id="main-wrapper">

    @include('main_includes.navbar_include')

    @include('main_includes.admin_sidebar_include')


    <div class="content-body rightside-event">
        <!-- row -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Staff</h4>
                            <a href="{{ route('add-staff')}}" class="btn btn-primary">Add Staff</a>
                        </div>
                        <div class="card-body">
                            @if (session()->has('success'))
                            <div class="alert alert-success">
                                <strong>Success!</strong> {{ session('success') }}.
                            </div>
                            @endif
                            <div class="table-responsive ticket-table">
                                <div id="example_wrapper" class="dataTables_wrapper no-footer">
                                    <table id="example" class="display dataTablesCard table-responsive-xl dataTable no-footer" style="min-width: 845px" aria-describedby="example_info">
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th>Role</th>
                                                <th>Full Name</th>
                                                <th>Address</th>
                                                <th>Phone Number</th>
                                                <th>Previous Bal</th>
                                                <th>Total Amount</th>
                                                <th>Paid Amount</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($Staffs as $Staff)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $Staff->designations ?? 'N/A' }}</td>
                                                <td>{{ $Staff->full_name ?? 'N/A' }}</td>
                                                <td>{{ $Staff->address ?? 'N/A' }}</td>
                                                <td>{{ $Staff->phone_number ?? 'N/A' }}</td>
                                                <td>{{ $Staff->previous_balance ?? 0 }}</td>
                                                <td>{{ $Staff->total_amount ?? 0 }}</td>
                                                <td>{{ $Staff->total_paid ?? 0 }}</td>
                                                <td>
                                                    @if($Staff->status === 'Paid')
                                                    <span class="badge bg-success">Paid</span>
                                                    @elseif($Staff->status === 'Unpaid')
                                                    <span class="badge bg-danger">Unpaid</span>
                                                    @else
                                                    <span class="badge bg-secondary">Not Given</span>
                                                    @endif
                                                </td>

                                                <td>
                                                    <div class="d-flex">
                                                        <a href="javascript:void(0);" class="btn btn-primary edit-btn shadow btn-sm sharp me-1"
                                                            data-type-id="{{ $Staff->id }}"
                                                            data-type-designations="{{ $Staff->designations ?? '' }}"
                                                            data-type-full_name="{{ $Staff->full_name ?? '' }}"
                                                            data-type-address="{{ $Staff->address ?? '' }}"
                                                            data-type-phone_number="{{ $Staff->phone_number ?? '' }}">
                                                            <i class="fas fa-pencil-alt"></i>
                                                        </a>
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
    </div>



    <div class="modal fade" id="editstaffmodel" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Staff</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('staffs.update') }}" method="POST">
                        @csrf
                        <input type="hidden" id="edit_staff_id" name="edit_staff_id">

                        <div class="mb-3 form-group">
                            <label class="form-label">Select Role</label>
                            <select name="designations" id="Role_id" class="form-control" required>
                                <option value="" selected disabled>Select One</option>
                                @foreach($StaffDesignations as $Designations)
                                <option value="{{ $Designations->designations }}">
                                    {{ $Designations->designations }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 form-group">
                            <label class="form-label">Staff Full Name</label>
                            <input type="text" class="form-control" id="staff_name" name="full_name" placeholder="Full Name">
                        </div>

                        <div class="mb-3 form-group">
                            <label class="form-label">Staff Address</label>
                            <input type="text" class="form-control" id="staff_address" name="address" placeholder="Address">
                        </div>

                        <div class="mb-3 form-group">
                            <label class="form-label">Staff Phone Number</label>
                            <input type="number" class="form-control" id="staff_phone" name="phone_number" placeholder="Phone Number">
                        </div>

                        <div class="mb-3 form-group">
                            <label class="form-label">Staff Salary</label>
                            <input type="number" class="form-control" id="staff_salary" name="salary" placeholder="Salary In PKR">
                        </div>



                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('main_includes.copyright_include')


    @include('main_includes.footer_include')

    <script>
        $(document).on('click', '.edit-btn', function() {
            // Show the modal
            $('#editstaffmodel').modal('show');

            // Get the data from the button
            var staffid = $(this).data('type-id');
            var staffdesignation = $(this).data('type-designations');
            var staffname = $(this).data('type-full_name');
            var staffaddress = $(this).data('type-address');
            var staffphone = $(this).data('type-phone_number');
            var staffsalary = $(this).data('type-salary');
            // Set the values in the modal
            $('#edit_staff_id').val(staffid);
            $('#Role_id').val(staffdesignation);
            $('#staff_name').val(staffname);
            $('#staff_address').val(staffaddress);
            $('#staff_phone').val(staffphone);
            $('#staff_salary').val(staffsalary);
        });
    </script>