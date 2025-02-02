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
                            <h4 class="card-title">All Expense Record</h4>
                            <!-- Button to trigger Add Modal -->
                            <a href="{{ route('add-expenses') }}" class="btn btn-primary">Add new Expense</a>
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
                                                    <a href="{{ route('expenses.edit', $expense->id) }}" class="btn btn-primary btn-sm">Edit</a>

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
            document.querySelectorAll('.delete-expense').forEach(button => {
                button.addEventListener('click', function() {
                    let expenseId = this.getAttribute('data-id');

                    Swal.fire({
                        title: "Are you sure?",
                        text: "Do you really want to delete this expense?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Yes, delete it!",
                        cancelButtonText: "No, cancel!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch("{!! route('delete-expense') !!}", { // Laravel ka route use kar raha hoon
                                    method: "POST", // DELETE nahi, POST use karo
                                    headers: {
                                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                        "Content-Type": "application/json"
                                    },
                                    body: JSON.stringify({
                                        id: expenseId
                                    }) // Bas ID bhejni hai
                                }).then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire("Deleted!", "The expense has been deleted.", "success")
                                            .then(() => location.reload());
                                    } else {
                                        Swal.fire("Error!", data.message, "error");
                                    }
                                }).catch(error => {
                                    console.error("Error:", error);
                                    Swal.fire("Error!", "Something went wrong.", "error");
                                });
                        }
                    });
                });
            });
        });
    </script>