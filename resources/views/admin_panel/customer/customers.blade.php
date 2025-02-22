@include('main_includes.header_include')
<div id="main-wrapper">
    @include('main_includes.navbar_include')
    @include('main_includes.admin_sidebar_include')
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
    <div class="content-body rightside-event">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Customers</h4>
                            <a href="{{ route('add-Customer') }}" class="btn btn-primary">Add Customer</a>
                        </div>
                        <div class="card-body">
                            @if (session()->has('success'))
                            <div class="alert alert-success">
                                <strong>Success!</strong> {{ session('success') }}.
                            </div>
                            @endif
                            <!-- Export PDF Button -->
                            <button id="export-pdf" class="btn btn-danger mb-3">Export PDF</button>
                            <div class="table-responsive ticket-table">

                                <table id="example" class="display dataTablesCard table-responsive-xl  no-footer" style="min-width: 845px">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Image</th>
                                            <th>Customer Number</th>
                                            <th>Full Name</th>
                                            <th>Email</th>
                                            <th>Address</th>
                                            <th>Phone Number</th>
                                            <th>City</th>
                                            <th>Gender</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($Customers as $Customer)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @if($Customer->image)
                                                <img src="{{ asset('TMS_software/public/customer_images/' . $Customer->image) }}" width="50" height="50" alt="Customer Image">
                                                @else
                                                <span>No Image</span>
                                                @endif
                                            </td>
                                            <td>#{{ $Customer->customer_number }}</td>
                                            <td>{{ $Customer->full_name }}</td>
                                            <td>{{ $Customer->email }}</td>
                                            <td>{{ $Customer->address }}</td>
                                            <td>{{ $Customer->phone_number }}</td>
                                            <td>{{ $Customer->city }}</td>
                                            <td>{{ ucfirst($Customer->gender) }}</td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <!-- Add Measurement Button -->
                                                    <a href="{{ route('customer-add-measurement', ['id' => $Customer->id ]) }}" class="btn btn-success btn-sm text-white">
                                                        Measurement
                                                    </a>

                                                    <!-- Edit Button -->
                                                    <a href="javascript:void(0);"
                                                        class="btn btn-primary btn-sm edit-btn shadow"
                                                        data-id="{{ $Customer->id }}"
                                                        data-customer_number="{{ $Customer->customer_number }}"
                                                        data-full_name="{{ $Customer->full_name }}"
                                                        data-email="{{ $Customer->email }}"
                                                        data-address="{{ $Customer->address }}"
                                                        data-phone_number="{{ $Customer->phone_number }}"
                                                        data-city="{{ $Customer->city }}"
                                                        data-gender="{{ $Customer->gender }}">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>

                                                    <!-- Send Email Button -->
                                                    <a href="{{ route('customer.send-email', ['id' => $Customer->id]) }}" class="btn btn-info btn-sm text-white">
                                                        Email
                                                    </a>
                                                </div>
                                            </td>

                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="py-5">
                                    {{ $Customers->appends(request()->input())->links() }}
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

    <!-- Edit Customer Modal -->
    <div class="modal fade" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="updateCustomerForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCustomerLabel">Edit Customer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit_customer_id">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_customer_number" class="form-label">Customer Number</label>
                                <input type="text" class="form-control" id="edit_customer_number" name="customer_number" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_full_name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="edit_full_name" name="full_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_Email" class="form-label">Email</label>
                                <input type="text" class="form-control" id="edit_Email" name="email" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="edit_address" name="address" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="edit_phone_number" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="edit_phone_number" name="phone_number" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_city" class="form-label">City</label>
                                <input type="text" class="form-control" id="edit_city" name="city" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_gender" class="form-label">Gender</label>
                                <select class="form-control" id="edit_gender" name="gender" required>
                                    <option value="male">Male</option>
                                    <option value="kids">Kids</option>
                                </select>
                            </div>

                        </div>



                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


</div>


<script>
document.getElementById('export-pdf').addEventListener('click', function() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    doc.text("Customer List", 14, 10); // PDF Title

    doc.autoTable({
        html: '#example', // Table ID
        startY: 20,
        styles: { fontSize: 8 }, // Adjust Font Size
        headStyles: { fillColor: [211, 47, 47] }, // Red Header
        columnStyles: {
            1: { cellWidth: 25 } // Adjust Image Column Width
        },
        didDrawCell: function(data) {
            // Convert Image URLs to Base64 for PDF
            if (data.column.index === 1 && data.cell.raw.querySelector('img')) {
                const imgElement = data.cell.raw.querySelector('img');
                const canvas = document.createElement("canvas");
                const ctx = canvas.getContext("2d");
                const img = new Image();
                img.src = imgElement.src;
                img.onload = function() {
                    canvas.width = img.width;
                    canvas.height = img.height;
                    ctx.drawImage(img, 0, 0);
                    const imgData = canvas.toDataURL("image/jpeg");
                    doc.addImage(imgData, "JPEG", data.cell.x + 2, data.cell.y + 2, 15, 15);
                };
            }
        }
    });

    doc.save('customer_list.pdf');
});
</script>
<script>
    $(document).on('click', '.edit-btn', function() {
        $('#editCustomerModal').modal('show');

        $('#edit_customer_id').val($(this).data('id'));
        $('#edit_customer_number').val($(this).data('customer_number'));
        $('#edit_full_name').val($(this).data('full_name'));
        $('#edit_Email').val($(this).data('email'));
        $('#edit_address').val($(this).data('address'));
        $('#edit_phone_number').val($(this).data('phone_number'));
        $('#edit_city').val($(this).data('city'));
        $('#edit_gender').val($(this).data('gender'));


    });
</script>

<script>
    $('#updateCustomerForm').submit(function(e) {
        e.preventDefault();

        let formData = $(this).serialize();

        $.ajax({
            url: '{{ route("update-customer") }}', // Update with your route
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.status === 'success') {
                    alert(response.message);
                    location.reload(); // Reload the page to reflect changes
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr) {
                alert('An error occurred while updating the customer.');
            }
        });
    });
</script>