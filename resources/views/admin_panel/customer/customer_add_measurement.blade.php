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
                            <h4 class="card-title">Add Measurement</h4>
                        </div>
                        <div class="card-body">
                            <div class="basic-form">
                                @if (session()->has('success'))
                                <div class="alert alert-success">
                                    <strong>Success!</strong> {{ session('success') }}.
                                </div>
                                @endif
                                <form action="#" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <!-- Customer Details -->
                                    <h5>Customer Details</h5>
                                    <p><strong>Name:</strong> {{ $customer->full_name }}</p>
                                    <p><strong>Phone Number:</strong> {{ $customer->phone_number }}</p>
                                    <p><strong>Address:</strong> {{ $customer->address }}</p>

                                    <!-- Cloth Type Selection -->
                                    <div class="form-group">
                                        <label for="cloth_type">Select Cloth Type</label>
                                        <select id="cloth_type" name="cloth_type[]" class="form-control select2" multiple>
                                            @foreach ($clothTypes as $clothType)
                                            <option value="{{ $clothType->cloth_type_name }}">{{ $clothType->cloth_type_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Measurement Parts (Dynamic) -->
                                    <div id="measurement_parts_section" class="form-group">
                                        <label for="measurement_parts">Select Measurement Parts</label>
                                        <!-- Measurement parts will be dynamically added here -->
                                    </div>

                                    <!-- Measurement Input Fields (Dynamic) -->
                                    <div id="measurement_inputs_section">
                                        <!-- Measurement inputs will be dynamically added here -->
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
</div>



<script>
    // JavaScript to handle dynamic changes based on Cloth Type selection
    document.getElementById('cloth_type').addEventListener('change', function() {
        let clothTypeIds = Array.from(this.selectedOptions).map(option => option.value);
        fetchMeasurementParts(clothTypeIds);
    });

    function fetchMeasurementParts(clothTypeIds) {
        // Make an AJAX request to fetch measurement parts for the selected cloth types
        fetch(`/fetch-measurement-parts`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    clothTypeIds
                })
            })
            .then(response => response.json())
            .then(data => {
                // Update the measurement parts section
                let measurementPartsSection = document.getElementById('measurement_parts_section');
                let measurementInputsSection = document.getElementById('measurement_inputs_section');

                measurementPartsSection.innerHTML = ''; // Clear previous parts
                measurementInputsSection.innerHTML = ''; // Clear previous inputs

                data.measurementParts.forEach(part => {
                    // Add part as a checkbox
                    let checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.name = 'measurement_parts[]';
                    checkbox.value = part.id;

                    let label = document.createElement('label');
                    label.textContent = part.name;

                    measurementPartsSection.appendChild(checkbox);
                    measurementPartsSection.appendChild(label);
                    measurementPartsSection.appendChild(document.createElement('br'));

                    // Add measurement input for each part
                    let inputField = document.createElement('input');
                    inputField.type = 'text';
                    inputField.name = `measurement[${part.id}]`;
                    inputField.placeholder = `Enter ${part.name} measurement`;

                    measurementInputsSection.appendChild(inputField);
                    measurementInputsSection.appendChild(document.createElement('br'));
                });
            })
            .catch(error => {
                console.error('Error fetching measurement parts:', error);
            });
    }
</script>

@include('main_includes.footer_include')