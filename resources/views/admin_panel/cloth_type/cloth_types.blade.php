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

    <!-- Add Cloth Type Modal -->
    <div class="modal fade" id="addClothTypeModal" tabindex="-1" aria-labelledby="addClothTypeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addClothTypeModalLabel">Add Cloth Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('store-cloth-type') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="cloth_type_name">Cloth Type Name</label>
                            <input type="text" class="form-control" id="cloth_type_name" name="cloth_type_name" required>
                        </div>
                        <div class="form-group">
                            <label for="cloth_type_gender">Gender</label>
                            <select class="form-control" id="cloth_type_gender" name="cloth_type_gender" required>
                                <option value="male">Male</option>
                                <option value="kids">kids</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editClothTypeModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Cloth Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('cloth_type.update') }}" method="POST">
                        @csrf
                        <input type="hidden" id="edit_cloth_type_id" name="cloth_type_id">

                        <div class="form-group">
                            <label for="edit_cloth_type_name">Cloth Type Name</label>
                            <input type="text" class="form-control" id="edit_cloth_type_name" name="cloth_type_name">
                        </div>

                        <div class="form-group">
                            <label for="edit_gender">Gender</label>
                            <select class="form-control" id="edit_gender" name="gender">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="unisex">Unisex</option>
                            </select>
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
                            <h4 class="card-title">Cloth Type List</h4>
                            <!-- Button to trigger Add Modal -->
                            <!-- <button class="btn btn-primary" data-toggle="modal" data-target="#addClothTypeModal">Add Cloth Type</button> -->

                            <!-- Trigger Button -->
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClothTypeModal">Add Cloth Type</button>
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
                                                <th>Gender</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($ClothTypes as $type)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $type->cloth_type_name }}</td>
                                                <td>{{ $type->cloth_type_gender }}</td>
                                                <td>
                                                    <div class="d-flex">
                                                        <a href="javascript:void(0);" class="btn btn-primary edit-btn shadow btn-sm sharp me-1"
                                                            data-type-id="{{ $type->id }}" data-type-name="{{ $type->cloth_type_name }}"
                                                            data-type-gender="{{ $type->cloth_type_gender }}">
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
            $('#editClothTypeModal').modal('show');

            // Get the data from the button
            var clothTypeId = $(this).data('type-id');
            var clothTypeName = $(this).data('type-name');
            var clothTypeGender = $(this).data('type-gender');

            // Set the values in the modal
            $('#edit_cloth_type_id').val(clothTypeId);
            $('#edit_cloth_type_name').val(clothTypeName);

            // Set gender in the select field (ensure the value is matching with the options)
            $('#edit_gender').val(clothTypeGender);
        });
    </script>