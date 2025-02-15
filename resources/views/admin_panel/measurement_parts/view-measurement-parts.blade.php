@include('main_includes.header_include')
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
    @include('main_includes.navbar_include')

    @include('main_includes.admin_sidebar_include')


    <div class="content-body rightside-event">
        <!-- row -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Measurement Parts List</h4>
                            <!-- Button to trigger Add Modal -->
                            <a href="{{ route('create-measurement-parts')}}" class="btn btn-primary">Add Measurement Parts</a>
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
                                                <th>S.No</th>
                                                <th>Measurement Category</th>
                                                <th>Measurement Name </th>
                                                <th>Action </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($MeasurementPart as $type)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $type->Measurement_category }}</td>
                                                <td>{{ $type->Measurement_name }}</td>
                                                <td>
                                                    <div class="d-flex">
                                                        <a href="javascript:void(0);" class="btn btn-primary edit-btn shadow btn-sm sharp me-1">
                                                            <i class="fas fa-pencil-alt"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="py-5">
                                        {{ $MeasurementPart->appends(request()->input())->links() }}
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