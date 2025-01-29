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
                                <h4 class="card-title">Add New Expense Category </h4>
                        </div>
                        <div class="card-body">
                            <div class="basic-form">
                            @if (session()->has('success'))
                            <div class="alert alert-success">
                                <strong>Success!</strong> {{ session('success') }}.
                            </div>
                            @endif
                                <form action="{{ route('store-category') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                       

                                        <div class=" mb-3 col-md-6">
                                            <label class="form-label">Category Name</label>
                                            <input type="text" class="form-control" name="category_name" placeholder="Full Name">
                                        </div>

                                    </div>
                                        <button type="submit" class="btn btn-primary">Save</button>

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