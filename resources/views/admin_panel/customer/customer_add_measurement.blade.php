@include('main_includes.header_include')

<div id="main-wrapper">
    @include('main_includes.navbar_include')
    @include('main_includes.admin_sidebar_include')

    <div class="content-body rightside-event">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h4 class="card-title">Add Measurement</h4>
                        </div>
                        <div class="card-body">
                            <div class="basic-form">
                                @if (session()->has('success'))
                                <div class="alert alert-success">
                                    <strong>Success!</strong> {{ session('success') }}.
                                </div>
                                @endif
                                <form action="{{ route('measurements.store', $customer->id) }}" method="POST">
                                    @csrf

                                    <!-- Customer Details -->
                                    <div class="mb-4 p-2 border rounded bg-light">
                                        <h5 class="text-primary">Customer Details</h5>
                                        <hr>
                                        <h4>Customer Number : {{ $customer->customer_number }}</h4>
                                        <p class="mb-1"><strong>Name:</strong> {{ $customer->full_name }}</p>
                                        <p class="mb-1"><strong>Phone:</strong> {{ $customer->phone_number }}</p>
                                        <p class="mb-1"><strong>Address:</strong> {{ $customer->address }}</p>
                                    </div>

                                    <!-- Cloth Types and Measurement Parts -->
                                    @foreach ($clothTypes as $clothType)
                                    <div class="mb-4">
                                        <h6 class="text-dark border-bottom pb-1">{{ $clothType->cloth_type_name }}</h6>
                                        <div class="row gx-2">
                                            @foreach ($measurementParts->where('Measurement_category', $clothType->cloth_type_name) as $part)
                                            @php
                                            $key = $clothType->id . '-' . $part->id;
                                            $existingValue = $measurements[$key]->value ?? '';
                                            @endphp
                                            <div class="col-6 col-sm-4 col-md-2 mb-2">
                                                <label class="form-label d-block small text-muted">{{ $part->Measurement_name }}</label>
                                                <input type="text"
                                                    name="measurements[{{ $clothType->id }}][{{ $part->id }}]"
                                                    class="form-control form-control-sm border-secondary"
                                                    placeholder="Value"
                                                    value="{{ $existingValue }}" />
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endforeach


                                    <button type="submit" class="btn btn-primary shadow mt-3">Save</button>
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