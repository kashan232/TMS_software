@include('main_includes.header_include')

<style>
    .btn-danger {
        width: 100%;
        padding: 5px;
        font-size: 14px;
    }

    .measurement-row {
        margin-bottom: 15px;
        align-items: end;
    }

    .btn-success, .btn-danger {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        font-size: 14px;
    }

    .btn-success {
        background-color: #4ba064;
        border-color: #4ba064;
    }

    .btn-success:hover {
        background-color: #3e8a52;
        border-color: #3e8a52;
    }

    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .btn-danger:hover {
        background-color: #c82333;
        border-color: #c82333;
    }
</style>

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
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Add Measurement Part</h4>
                        </div>
                        <div class="card-body">
                            @if (session()->has('success'))
                                <div class="alert alert-success">
                                    <strong>Success!</strong> {{ session('success') }}.
                                </div>
                            @endif
                            <div class="basic-form">
                                <form action="{{ route('measurment_store') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Select Category</label>
                                            <select name="measurement_category" class="form-control" required>
                                                <option value="" selected disabled>Select One</option>
                                                @foreach($ClothTypes as $type)
                                                    <option value="{{ $type->cloth_type_name }}">{{ $type->cloth_type_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div id="measurements-container">
                                        <div class="row measurement-row">
                                            <div class="col-md-10">
                                                <label class="form-label">Measurement Name</label>
                                                <input type="text" name="measurement_names[]" class="form-control" placeholder="Enter Measurement Name">
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-success add-measurement">Add More</button>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary mt-3">Save</button>
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

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('measurements-container');

        // Add measurement button
        document.querySelector('.add-measurement').addEventListener('click', function () {
            const row = document.createElement('div');
            row.className = 'row measurement-row';
            row.innerHTML = `
                <div class="mb-3 col-md-10">
                    <input type="text" name="measurement_names[]" class="form-control" placeholder="Enter Measurement Name">
                </div>
                <div class="mb-3 col-md-2">
                    <button type="button" class="btn btn-danger remove-measurement">Remove</button>
                </div>`;
            container.appendChild(row);

            // Remove measurement button
            row.querySelector('.remove-measurement').addEventListener('click', function () {
                row.remove();
            });
        });
    });
</script>
