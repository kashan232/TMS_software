@include('main_includes.header_include')

<!--**********************************
        Main wrapper start
    ***********************************-->
<div id="main-wrapper">
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
    <!--**********************************
            Nav header start
        ***********************************-->

    @include('main_includes.navbar_include')

    @include('main_includes.admin_sidebar_include')
    <!--**********************************
            Content body start
        ***********************************-->

    <!-- Add Staff Designations Modal -->
    <div class="modal fade" id="addDesignationModal" tabindex="-1" aria-labelledby="addDesignationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDesignationModalLabel">Add Staff Designations</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('store-designations') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="designations">Designations Name</label>
                            <input type="text" class="form-control" id="designations" name="designations" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editDesignationmodal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Staff Designations</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('designations.update') }}" method="POST">
                        @csrf
                        <input type="hidden" id="edit_staff_designations" name="id_edit_staff_designations">

                        <div class="form-group">
                            <label for="edit_designations">Designations Name</label>
                            <input type="text" class="form-control" id="edit_designations" name="designations">
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="content-body rightside-event">
        <!-- row -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Staff Designations</h4>
                            <!-- Button to trigger Add Modal -->
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDesignationModal">Add Staff Designations</button>
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
                                                <th>SNo</th>
                                                <th>Name</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($StaffDesignations as $designations)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $designations->designations }}</td>
                                                <td>
                                                    <div class="d-flex">
                                                        <a href="javascript:void(0);" class="btn btn-primary edit-btn shadow btn-sm sharp me-1"
                                                            data-type-id="{{ $designations->id }}" data-type-name="{{ $designations->designations }}">
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

    @include('main_includes.copyright_include')


    @include('main_includes.footer_include')
    <script>
        $(document).on('click', '.edit-btn', function() {
            // Show the modal
            $('#editDesignationmodal').modal('show');

            // Get the data from the button
            var designationId = $(this).data('type-id');
            var Designationsname = $(this).data('type-name');
            // Set the values in the modal
            $('#edit_staff_designations').val(designationId);
            $('#edit_designations').val(Designationsname);

            // Set gender in the select field (ensure the value is matching with the options)
        });
    </script>