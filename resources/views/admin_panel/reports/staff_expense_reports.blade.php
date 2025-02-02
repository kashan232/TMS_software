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
                            <h4 class="card-title">Staff Expense Record</h4>
                        </div>
                        <div class="card-body">
                            <div class="basic-form">
                                <form id="searchForm">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="staff_member">Staff Members</label>
                                                <select name="staff_member" id="staff_member" class="form-control">
                                                    <option value="">Select Staff</option>
                                                    @foreach($staffMembers as $staffMember)
                                                    <option value="{{ $staffMember->id }}">{{ $staffMember->full_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="week_start_date">Week Start Date</label>
                                                <input type="date" class="form-control" id="week_start_date" name="week_start_date">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="week_end_date">Week End Date</label>
                                                <input type="date" class="form-control" id="week_end_date" name="week_end_date">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-primary" id="searchButton">Search</button>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h5>Expense Records</h5>
                                <button class="btn btn-danger" id="exportPdf">Export PDF</button>
                            </div>

                            <table class="table mt-4" id="expenseTableContainer">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Staff Name</th>
                                        <th>Week Start</th>
                                        <th>Week End</th>
                                        <th>Previous Balance</th>
                                        <th>Suits Stitched</th>
                                        <th>Rate per Suit</th>
                                        <th>Total Amount</th>
                                        <th>Paid Amount</th>
                                        <th>Closing Balance</th>
                                    </tr>
                                </thead>
                                <tbody id="expenseTable">
                                    {{-- Data AJAX se load hoga --}}
                                </tbody>
                            </table>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
    $(document).ready(function() {
        $('#searchButton').on('click', function() {
            let staffId = $('#staff_member').val();
            let startDate = $('#week_start_date').val();
            let endDate = $('#week_end_date').val();

            $.ajax({
                url: "{{ route('fetch-staff-expenses') }}",
                type: "GET",
                data: {
                    staff_member: staffId,
                    week_start_date: startDate,
                    week_end_date: endDate
                },
                success: function(data) {
                    let rows = "";
                    if (data.length === 0) {
                        rows = `<tr><td colspan="10" class="text-center">No data found</td></tr>`;
                    } else {
                        $.each(data, function(index, expense) {
                            rows += `<tr>
                            <td>${index + 1}</td>
                            <td>${expense.staff ? expense.staff.full_name : 'N/A'}</td>
                            <td>${expense.week_start_date}</td>
                            <td>${expense.week_end_date}</td>
                            <td>${expense.previous_balance ?? '0'}</td>
                            <td>${expense.suits_stitched}</td>
                            <td>${expense.rate_per_suit}</td>
                            <td>${expense.total_amount}</td>
                            <td>${expense.paid_amount}</td>
                            <td>${expense.closing_balance}</td>
                        </tr>`;
                        });
                    }
                    $('#expenseTable').html(rows);
                }
            });
        });

        // PDF Export with Heading
        $('#exportPdf').on('click', function() {
            const {
                jsPDF
            } = window.jspdf;
            let doc = new jsPDF('p', 'pt', 'a4');

            // Heading
            doc.setFont("helvetica", "bold");
            doc.setFontSize(18);
            doc.text("Staff Expense Record", 200, 40);

            // Capture Table
            html2canvas(document.querySelector("#expenseTableContainer")).then(canvas => {
                let imgData = canvas.toDataURL('image/png');
                let imgWidth = 595;
                let imgHeight = (canvas.height * imgWidth) / canvas.width;

                doc.addImage(imgData, 'PNG', 10, 60, imgWidth - 20, imgHeight);
                doc.save("Staff_Expense_Report.pdf");
            });
        });
    });
</script>