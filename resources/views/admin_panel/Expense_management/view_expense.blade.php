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
                            <h4 class="card-title">Expense Category List</h4>
                            <!-- Button to trigger Add Modal -->
                            <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">Add new Category</a>
                        </div>

                        <div class="card-body">
                            @if (session()->has('success'))
                            <div class="alert alert-success">
                                <strong>Success!</strong> {{ session('success') }}.
                            </div>
                            @endif
                            <div class="table-responsive ticket-table">
                                <div id="example_wrapper" class="dataTables_wrapper no-footer">
                                    <table id="example" class="display dataTablesCard table-responsive-xl  no-footer" style="min-width: 845px" aria-describedby="example_info">
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th>Expense Category </th>
                                                <th>Action </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($view_expenses as $type)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $type->category_name }}</td>
                                                <td>
                                                    <div class="d-flex py-3">
                                                        <a href="javascript:void(0);"
                                                            class="btn btn-primary edit-btn shadow btn-sm sharp me-1"
                                                            data-id="{{ $type->id }}"
                                                            data-name="{{ $type->category_name }}">
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

                            <!-- Add Category Modal -->
                            <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('store-category') }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="category_name" class="form-label">Category Name</label>
                                                    <input type="text" class="form-control" id="category_name" name="category_name" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Add Category</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Edit Category Modal -->
                            <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form id="updateCategoryForm">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Category</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" id="edit_category_id"> <!-- Hidden input for category ID -->
                                                <div class="mb-3">
                                                    <label for="edit_category_name" class="form-label">Category Name</label>
                                                    <input type="text" class="form-control" id="edit_category_name" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Update Category</button>
                                            </div>
                                        </form>
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
        $(document).ready(function() {
            // Edit Button Click
            $('.edit-btn').on('click', function() {
                var categoryId = $(this).data('id');
                var categoryName = $(this).data('name');

                $('#edit_category_id').val(categoryId);
                $('#edit_category_name').val(categoryName);

                $('#editCategoryModal').modal('show');
            });

            // AJAX for Updating Category
            $('#updateCategoryForm').on('submit', function(e) {
                e.preventDefault();

                var categoryId = $('#edit_category_id').val();
                var categoryName = $('#edit_category_name').val();

                $.ajax({
                    url: "{{ route('update-category') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: categoryId,
                        category_name: categoryName
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('Category updated successfully!');
                            location.reload(); // Reload page to reflect changes
                        } else {
                            alert('Error updating category.');
                        }
                    }
                });
            });
        });
    </script>