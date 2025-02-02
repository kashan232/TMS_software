@include('main_includes.header_include')
<div id="main-wrapper">

    @include('main_includes.navbar_include')

    @include('main_includes.admin_sidebar_include')


    <!-- Edit Expense Modal -->
    <div class="modal fade" id="editExpenseModal" tabindex="-1" aria-labelledby="editExpenseLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editExpenseLabel">Edit Expense</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateExpenseForm">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" id="edit_expense_id">

                        <!-- Category -->
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select id="edit_category_id" class="form-control" required>
                                <option value="">-- Select Category --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Title -->
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" id="edit_title" required>
                        </div>

                        <!-- Amount -->
                        <div class="mb-3">
                            <label class="form-label">Amount</label>
                            <input type="number" class="form-control" id="edit_expense_amount" required>
                        </div>

                        <!-- Date -->
                        <div class="mb-3">
                            <label class="form-label">Date</label>
                            <input type="date" class="form-control" id="edit_date" required>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" id="edit_description"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteExpenseModal" tabindex="-1" aria-labelledby="deleteExpenseLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteExpenseLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this expense?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Yes, Delete</button>
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
                            <h4 class="card-title">All Expense Record</h4>
                            <!-- Button to trigger Add Modal -->
                            <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#addCategoryModal">Add new Category</a>
                        </div>

                        <div class="card-body">
                            @if (session()->has('success'))
                                <div class="alert alert-success">
                                    <strong>Success!</strong> {{ session('success') }}.
                                </div>
                            @endif
                            <div class="table-responsive ticket-table">
                                <div id="example_wrapper" class="dataTables_wrapper no-footer">
                                    <table class="table  table-bordered  text-center">
                                        <thead class="table-info text-light py-3">
                                                <th>#</th>
                                                <th>Category</th>
                                                <th>Title</th>
                                                <th>Amount</th>
                                                <th>Date</th>
                                                <th>Description</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($view_expenses as $expense)
                                                <tr>
                                                    <td><strong>{{ $loop->iteration }}</strong></td>
                                                    <td>
                                                        <span class="badge bg-primary py-2 font-xl px-3">
                                                            {{ $expense->category ? $expense->category->category_name : 'N/A' }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $expense->title }}</td>
                                                    <td><strong
                                                            class="text-success">{{ number_format($expense->expense_amount, 2) }}</strong>
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($expense->date)->format('d M, Y') }}
                                                    </td>
                                                    <td>{{ $expense->description ?: '-' }}</td>
                                                    <td>
                                                        <!-- Edit Button -->
                                                        <button class="btn btn-warning btn-sm edit-expense"
                                                            data-id="{{ $expense->id }}"
                                                            data-category="{{ $expense->category_id }}"
                                                            data-title="{{ $expense->title }}"
                                                            data-amount="{{ $expense->expense_amount }}"
                                                            data-date="{{ $expense->date }}"
                                                            data-description="{{ $expense->description }}">
                                                            Edit
                                                        </button>

                                                        <!-- Delete Button -->
                                                        <button class="btn btn-danger btn-sm delete-expense"
                                                            data-id="{{ $expense->id }}">
                                                            Delete
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                </div>
                            </div>

                            <!-- Add Category Modal -->
                            <div class="modal fade" id="addCategoryModal" tabindex="-1"
                                aria-labelledby="addCategoryModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('store-category') }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="addCategoryModalLabel">Add New Record</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">

                                                    <select id="category_name" name="category_name"
                                                        class="form-control" required>
                                                        <option value="">-- Select Category --</option>
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}">
                                                                {{ $category->category_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Add Category</button>
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
        let updateExpenseUrl = "{{ route('update-expense', ':id') }}"; // Define update URL
        let deleteExpenseUrl = "{{ route('delete-expense', ':id') }}"; // Define delete URL

        // Open Edit Modal & Fill Data
        $('.edit-expense').click(function() {
            let expenseId = $(this).data('id');

            $('#edit_expense_id').val(expenseId);
            $('#edit_category_id').val($(this).data('category'));
            $('#edit_title').val($(this).data('title'));
            $('#edit_expense_amount').val($(this).data('amount'));
            $('#edit_date').val($(this).data('date'));
            $('#edit_description').val($(this).data('description'));

            $('#editExpenseModal').modal('show');
        });

        // Submit Edit Form (AJAX)
        $('#updateExpenseForm').submit(function(e) {
            e.preventDefault();

            let expenseId = $('#edit_expense_id').val();
            let formData = {
                _token: '{{ csrf_token() }}',
                _method: 'PUT', // Laravel requires PUT
                category_id: $('#edit_category_id').val(),
                title: $('#edit_title').val(),
                expense_amount: $('#edit_expense_amount').val(),
                date: $('#edit_date').val(),
                description: $('#edit_description').val(),
            };

            let updateUrl = updateExpenseUrl.replace(':id', expenseId); // Ensure correct URL

            $.ajax({
                url: updateUrl,
                type: "POST", // Laravel will treat it as PUT
                data: formData,
                success: function(response) {
                    if (response.success) {
                        alert("Expense updated successfully!");
                        location.reload();
                    } else {
                        alert("Failed to update expense.");
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    alert("Error updating expense!");
                }
            });
        });

        // Open Delete Confirmation Modal
        $('.delete-expense').click(function() {
            let expenseId = $(this).data('id');
            $('#confirmDelete').attr('data-id', expenseId);
            $('#deleteExpenseModal').modal('show');
        });

        // Confirm Delete (AJAX)
        $('#confirmDelete').click(function() {
            let expenseId = $(this).data('id');
            let deleteUrl = deleteExpenseUrl.replace(':id', expenseId);

            $.ajax({
                url: deleteUrl,
                type: "POST", // Laravel needs `_method: 'DELETE'`
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'DELETE'
                },
                success: function(response) {
                    if (response.success) {
                        alert("Expense deleted successfully!");
                        location.reload();
                    } else {
                        alert("Failed to delete expense.");
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    alert("Error deleting expense!");
                }
            });
        });

    });
</script>

