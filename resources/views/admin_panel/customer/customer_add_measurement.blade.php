@include('main_includes.header_include')

<div id="main-wrapper">
    @include('main_includes.navbar_include')
    @include('main_includes.admin_sidebar_include')
    <style>
        .form-label {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
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
                                <button id="exportPDF" class="btn btn-danger mt-3 mb-3">Export PDF</button>

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

                                    <!-- Description Box (Before Measurements) -->
                                    <div class="mb-4">
                                        <h6 class="text-dark border-bottom pb-1">Description</h6>
                                        <textarea name="description" class="form-control mt-2" placeholder="Enter a description for the customer measurements">{{ $customerDescription ?? '' }}</textarea>
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
                                            <div class="col-3 col-sm-2 col-md-1 mb-2">
                                                <label class="form-label d-block small text-muted">{{ $part->Measurement_name }}</label>
                                                <input type="text"
                                                    name="measurements[{{ $clothType->id }}][{{ $part->id }}]"
                                                    class="form-control form-control-sm border-secondary"
                                                    placeholder="Value"
                                                    value="{{ $existingValue }}" />
                                            </div>
                                            @endforeach
                                        </div>

                                        <!-- Cloth Variants (Yes/No for each variant under this cloth type) -->
                                        <div class="mt-3">
                                            <h6 class="text-dark">Cloth Variants</h6>
                                            <div class="d-flex flex-wrap align-items-center"> <!-- Use flexbox to keep items in one row -->
                                                @foreach ($clothVariants->where('cloth_type_name', $clothType->cloth_type_name)->unique('variants_name') as $variant)
                                                @php
                                                $selectedVariant = $existingVariants[$variant->id] ?? null; // Get saved value (yes/no)
                                                @endphp
                                                <div class="me-4 d-flex align-items-center"> <!-- Add margin between items -->
                                                    <span class="fw-bold me-2">{{ $variant->variants_name }}:</span>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="cloth_variant[{{ $variant->id }}]" value="yes"
                                                            {{ $selectedVariant == 'yes' ? 'checked' : '' }} />
                                                        <label class="form-check-label">Yes</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="cloth_variant[{{ $variant->id }}]" value="no"
                                                            {{ $selectedVariant == 'no' ? 'checked' : '' }} />
                                                        <label class="form-check-label">No</label>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("exportPDF").addEventListener("click", function() {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF();
            let y = 10; // Initial Y position for text placement

            // Add Title
            doc.setFontSize(16);
            doc.text("Customer Measurements", 10, y);
            y += 10;

            // Extract Customer Details
            let customerDetails = document.querySelector(".border.bg-light");
            if (customerDetails) {
                let detailsText = customerDetails.innerText.split("\n").filter(line => line.trim() !== "");
                detailsText.forEach(line => {
                    doc.setFontSize(12);
                    doc.text(line, 10, y);
                    y += 7; // Move down
                });
            }

            y += 5; // Add spacing before next section

            // Extract Measurements
            doc.setFontSize(14);
            doc.text("Measurements:", 10, y);
            y += 8;

            let measurementSections = document.querySelectorAll(".mb-4"); // Select measurement sections
            measurementSections.forEach(section => {
                let sectionTitle = section.querySelector("h6")?.innerText || null;
                if (sectionTitle) {
                    doc.setFontSize(12);
                    doc.text(sectionTitle, 10, y);
                    y += 6;
                }

                let inputs = section.querySelectorAll("input[type='text']");
                inputs.forEach(input => {
                    let label = input.previousElementSibling?.innerText || "Measurement";
                    let value = input.value.trim() || "N/A";
                    doc.setFontSize(10);
                    doc.text(`${label}: ${value}`, 15, y);
                    y += 5;
                });

                y += 3;
            });

            y += 5; // Spacing before next section

            // Extract Cloth Variants
            doc.setFontSize(14);
            doc.text("Cloth Variants:", 10, y);
            y += 8;

            let variantSections = document.querySelectorAll(".d-flex.flex-wrap.align-items-center");
            variantSections.forEach(section => {
                let variants = section.querySelectorAll(".me-4");
                variants.forEach(variant => {
                    let variantName = variant.querySelector("span")?.innerText.trim() || "Variant";
                    let checkedInput = variant.querySelector("input:checked");
                    let selectedValue = checkedInput ? checkedInput.value : "N/A";

                    doc.setFontSize(10);
                    doc.text(`${variantName} - ${selectedValue}`, 15, y);
                    y += 5;
                });
                y += 3;
            });

            // Save PDF
            doc.save("Customer_Measurements.pdf");
        });
    });
</script>