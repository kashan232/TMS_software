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

    <!-- Add Cloth Variants Modal -->
    <div class="modal fade" id="addClothTypeModal" tabindex="-1" aria-labelledby="addClothTypeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addClothTypeModalLabel">Add Cloth Variants</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('store-cloth-Variants') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="cloth_type_name">Cloth Variants Name</label>
                            <select name="cloth_type_name" id="cloth_type_name" class="form-control">
                                @foreach($ClothTypes as $ClothType)
                                <option value="{{ $ClothType->cloth_type_name }}">{{ $ClothType->cloth_type_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="variants-container">
                            <div class="row variants-row">
                                <div class="col-md-10">
                                    <label class="form-label">Variants Name</label>
                                    <input type="text" name="variants_name[]" class="form-control mt-1 mb-1" placeholder="Enter Variants Name">
                                </div>
                                <div class="col-md-2">
                                    <label>Add</label>
                                    <button type="button" class="btn btn-success mt-2 mb-1 add-variants">Add More</button>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
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
                            <h4 class="card-title">Cloth Variants List</h4>
                            <!-- Button to trigger Add Modal -->
                            <!-- <button class="btn btn-primary" data-toggle="modal" data-target="#addClothTypeModal">Add Cloth Variants</button> -->

                            <!-- Trigger Button -->
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClothTypeModal">Add Cloth Variants</button>
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
                                                <th>Variants</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($ClothVariants as $Variants)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $Variants->cloth_type_name }}</td>
                                                <td>{{ $Variants->variants_name }}</td>
                                                <td>
                                                    <button class="btn btn-danger delete-measuremt"
                                                        data-id="{{ $Variants->id }}"><i class="fas fa-trash"></i></button>
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.delete-measuremt').forEach(button => {
                button.addEventListener('click', function() {
                    let measuremtId = this.getAttribute('data-id');

                    Swal.fire({
                        title: "Are you sure?",
                        text: "Do you really want to delete this Cloth Variant?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Yes, delete it!",
                        cancelButtonText: "No, cancel!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch("{{ route('delete-Variants') }}", {
                                    method: "POST",
                                    headers: {
                                        "X-CSRF-TOKEN": document.querySelector(
                                            'meta[name="csrf-token"]').getAttribute(
                                            'content'),
                                        "Content-Type": "application/json"
                                    },
                                    body: JSON.stringify({
                                        id: measuremtId
                                    })
                                }).then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire("Deleted!",
                                                "The Cloth Variant has been deleted.",
                                                "success")
                                            .then(() => location.reload());
                                    } else {
                                        Swal.fire("Error!", data.message, "error");
                                    }
                                }).catch(error => {
                                    console.error("Error:", error);
                                    Swal.fire("Error!", "Something went wrong.",
                                        "error");
                                });
                        }
                    });
                });
            });
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('variants-container');

            // Add Variants button
            document.querySelector('.add-variants').addEventListener('click', function() {
                const row = document.createElement('div');
                row.className = 'row variants-row';
                row.innerHTML = `
                <div class="mb-3 col-md-10">
                    <input type="text" name="variants_name[]" class="form-control mt-1 mb-1" placeholder="Enter Variants Name">
                </div>
                <div class="mb-3 col-md-2">
                    <button type="button" class="btn btn-danger mt-2 mb-1 remove-variants">Remove</button>
                </div>`;
                container.appendChild(row);

                // Remove Variants button
                row.querySelector('.remove-variants').addEventListener('click', function() {
                    row.remove();
                });
            });
        });
    </script>