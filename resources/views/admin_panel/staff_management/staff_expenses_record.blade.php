@include('main_includes.header_include')

<!--**********************************
        Main wrapper start
    ***********************************-->
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
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Staff Expense Record</h4>
                            <!-- Button to trigger Add Modal -->
                            <a href="{{ route('staff.expenses.index') }}" class="btn btn-primary">Add Staff Expense Record</a>
                        </div>
                        <div class="card-body">
                            @if (session()->has('success'))
                            <div class="alert alert-success">
                                <strong>Success!</strong> {{ session('success') }}.
                            </div>
                            @endif
                            <button id="export-pdf" class="btn btn-danger mb-3">Export PDF</button>
                            <div class="table-responsive ticket-table">
                                <div id="example_wrapper" class="dataTables_wrapper no-footer">
                                    <table id="example" class="display dataTablesCard table-responsive-xl" style="min-width: 845px">
                                        <thead>
                                            <tr>
                                                <th>SNo</th>
                                                <th>Staff Member</th>
                                                <th>Week Start</th>
                                                <th>Week End</th>
                                                <th>Previous Balance</th>
                                                <th>Suits Stitched</th>
                                                <th>Rate Per Suit</th>
                                                <th>Total Amount</th>
                                                <th>Paid Amount</th>
                                                <th>Closing Balance</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($StaffExpenses as $Expense)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $Expense->staff->full_name ?? 'Unknown' }}</td>
                                                <td>{{ $Expense->week_start_date }}</td>
                                                <td>{{ $Expense->week_end_date }}</td>
                                                <td>{{ $Expense->previous_balance ?? 0 }}</td>
                                                <td>{{ $Expense->suits_stitched }}</td>
                                                <td>{{ $Expense->rate_per_suit }}</td>
                                                <td>{{ $Expense->total_amount }}</td>
                                                <td>{{ $Expense->paid_amount }}</td>
                                                <td>{{ $Expense->closing_balance }}</td>
                                                <td>
                                                    <a href="javascript:void(0);" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
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
    <script>
        document.getElementById('export-pdf').addEventListener('click', function() {
        const {
            jsPDF
        } = window.jspdf;
        const doc = new jsPDF();

        doc.text("Expense Record", 14, 10); // PDF Title

        doc.autoTable({
            html: '#example', // Table ID
            startY: 20,
            styles: {
                fontSize: 8
            }, // Adjust Font Size
            headStyles: {
                fillColor: [211, 47, 47]
            }, // Red Header
            columnStyles: {
                1: {
                    cellWidth: 25
                } // Adjust Image Column Width
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

        doc.save('Expense_Record.pdf');
    });
    </script>