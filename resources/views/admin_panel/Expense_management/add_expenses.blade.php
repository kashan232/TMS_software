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
                <div class="col-xl-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                                <h4 class="card-title">Add Expense </h4>
                        </div>
                        <div class="card-body">
                            <div class="basic-form">
                            @if (session()->has('success'))
                            <div class="alert alert-success">
                                <strong>Success!</strong> {{ session('success') }}.
                            </div>
                            @endif
                            <form action="{{ route('store-expenses') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <!-- Category Selection -->
                                    <div class="mb-3 col-md-6">
                                        <label for="category" class="form-label">Select Category</label>
                                        <select id="category" name="category_id" class="form-control" required>
                                            <option value="">-- Select Category --</option>
                                            @foreach ($categories as $id => $name)
                                                <option value="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Title -->
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Title</label>
                                        <input type="text" class="form-control" name="title" placeholder="Enter Title" required>
                                    </div>

                                    <!-- Amount -->
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Amount</label>
                                        <input type="number" class="form-control" name="expense_amount" placeholder="Enter Amount" required>
                                    </div>

                                    <!-- Date -->
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Date</label>
                                        <input type="date" class="form-control" name="date" required>
                                    </div>

                                    <!-- Description -->
                                    <div class="mb-3 col-md-12">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" name="description" placeholder="Enter Description"></textarea>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">Save</button>
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