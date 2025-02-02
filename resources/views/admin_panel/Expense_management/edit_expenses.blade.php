@include('main_includes.header_include')

<div id="main-wrapper">
    @include('main_includes.navbar_include')
    @include('main_includes.admin_sidebar_include')

    <div class="content-body rightside-event">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Edit Expense</h4>
                        </div>
                        <div class="card-body">
                            <div class="basic-form">
                                @if (session()->has('success'))
                                    <div class="alert alert-success">
                                        <strong>Success!</strong> {{ session('success') }}.
                                    </div>
                                @endif
                                <form action="{{ route('update-expense', $expense->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <!-- Category Selection -->
                                        <div class="mb-3 col-md-6">
                                            <label for="category" class="form-label">Select Category</label>
                                            <select id="category" name="category_id" class="form-control" required>
                                                <option value="">-- Select Category --</option>
                                                @foreach ($categories as $id => $name)
                                                    <option value="{{ $id }}" {{ $expense->category_id == $id ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Title -->
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Title</label>
                                            <input type="text" class="form-control" name="title" value="{{ $expense->title }}" required>
                                        </div>

                                        <!-- Amount -->
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Amount</label>
                                            <input type="number" class="form-control" name="expense_amount" value="{{ $expense->expense_amount }}" required>
                                        </div>

                                        <!-- Date -->
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Date</label>
                                            <input type="date" class="form-control" name="date" value="{{ $expense->date }}" required>
                                        </div>

                                        <!-- Description -->
                                        <div class="mb-3 col-md-12">
                                            <label class="form-label">Description</label>
                                            <textarea class="form-control" name="description">{{ $expense->description }}</textarea>
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">Update Expense</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('main_includes.copyright_include')
    @include('main_includes.footer_include')
