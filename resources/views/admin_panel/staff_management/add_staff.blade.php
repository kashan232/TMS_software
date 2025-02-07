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
                            <h4 class="card-title">Add Staff </h4>
                        </div>
                        <div class="card-body">
                            <div class="basic-form">
                            @if (session()->has('success'))
                            <div class="alert alert-success">
                                <strong>Success!</strong> {{ session('success') }}.
                            </div>
                            @endif
                                <form action="{{ route('store-staff') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Select Role</label>
                                            <select name="designations" class="form-control" required>
                                                <option value="" selected disabled>Select One</option>
                                                @foreach($StaffDesignations as $Designations)
                                                <option value="{{ $Designations->designations }}">
                                                    {{ $Designations->designations }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Staff Full Name</label>
                                            <input type="text" class="form-control" name="full_name" placeholder="Full Name">
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Staff Address</label>
                                            <input type="text" class="form-control" name="address" placeholder="Address">
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Staff Phone Number</label>
                                            <input type="number" class="form-control" name="phone_number" placeholder="Phone Number">
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Save</button>
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