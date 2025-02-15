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

    <!-- Add Price Management Modal -->
    <div class="modal fade" id="addClothTypeModal" tabindex="-1" aria-labelledby="addPriceModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPriceModalLabel">Price Management</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('store-price-list') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="cloth_type_name">Cloth Type Name</label>
                            <select name="cloth_type_name" class="form-control" required>
                                <option value="" selected disabled>Select One</option>
                                @foreach($ClothTypes as $type)
                                <option value="{{ $type->cloth_type_name }}">
                                    {{ $type->cloth_type_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="Price">Price</label>
                            <input type="text" class="form-control" id="Price" name="Price" required>
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
                    <h5 class="modal-title" id="editModalLabel">Edit Price Management</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('price-list.update') }}" method="POST">
                        @csrf
                        <input type="hidden" id="edit_price_list_id" name="edit_price_list_id">

                        <div class="form-group">
                            <label for="cloth_type_name">Cloth Type Name</label>
                            <select name="cloth_type_name" class="form-control" id="edit_cloth_type_name" required>
                                @foreach($ClothTypes as $type)
                                <option value="{{ $type->cloth_type_name }}">
                                    {{ $type->cloth_type_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="Price">Price</label>
                            <input type="text" class="form-control" id="edit_price" name="Price" required>
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
                            <h4 class="card-title">Price Management</h4>
                            <!-- Button to trigger Add Modal -->
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClothTypeModal">Add Price Management</button>
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
                                                <th>Cloth Type</th>
                                                <th>Price</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($PriceList as $list)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $list->cloth_type_name }}</td>
                                                <td>{{ $list->Price }}</td>
                                                <td>
                                                    <div class="d-flex">
                                                        <a href="javascript:void(0);" class="btn btn-primary edit-btn shadow btn-sm sharp me-1"
                                                            data-type-id="{{ $list->id }}" data-type-name="{{ $list->cloth_type_name }}"
                                                            data-price="{{ $list->Price }}">
                                                            <i class="fas fa-pencil-alt"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="py-5">
                                        {{ $PriceList->appends(request()->input())->links() }}
                                    </div>
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
            var priceid = $(this).data('type-id');
            var clothTypeName = $(this).data('type-name');
            var price = $(this).data('price');
            // Set the values in the modal
            $('#edit_price_list_id').val(priceid);
            $('#edit_cloth_type_name').val(clothTypeName);
            $('#edit_price').val(price);
        });
    </script>