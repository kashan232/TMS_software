@include('main_includes.header_include')
<div id="main-wrapper">
    @include('main_includes.navbar_include')
    @include('main_includes.admin_sidebar_include')

    <div class="content-body rightside-event">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Staff Expenses</h4>
                        </div>
                        <div class="card-body">
                            @if (session()->has('success'))
                            <div class="alert alert-success">
                                <strong>Success!</strong> {{ session('success') }}.
                            </div>
                            @endif

                            <!-- Add Expense Form -->
                            <form action="{{ route('staff-expenses.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="staff_id">Staff Member</label>
                                        <select class="form-control" name="staff_id" id="staff_id" required>
                                            <option value="" disabled selected>Select Staff</option>
                                            @foreach($staffMembers as $staff)
                                            <option value="{{ $staff->id }}">{{ $staff->full_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="week_start_date">Week Start Date</label>
                                        <input type="date" class="form-control" id="week_start_date" name="week_start_date" required>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="week_end_date">Week End Date</label>
                                        <input type="date" class="form-control" id="week_end_date" name="week_end_date" required>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="previous_balance">Previous Balance</label>
                                        <input type="number" class="form-control" id="previous_balance" name="previous_balance" value="0" readonly>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="suits_stitched">Suits Stitched</label>
                                        <input type="number" class="form-control" id="suits_stitched" name="suits_stitched" required>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="rate_per_suit">Rate Per Suit</label>
                                        <input type="number" class="form-control" id="rate_per_suit" name="rate_per_suit" required>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="total_amount">Total Amount</label>
                                        <input type="number" class="form-control" id="total_amount" name="total_amount" readonly>
                                    </div>



                                    <!-- Closing Balance -->
                                    <div class="form-group col-md-6">
                                        <label for="closing_balance">Closing Balance</label>
                                        <input type="number" class="form-control" id="closing_balance" name="closing_balance" readonly>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="paid_amount">Paid Amount</label>
                                        <input type="number" class="form-control" id="paid_amount" name="paid_amount" required>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">Save Expense</button>
                            </form>
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
    document.addEventListener('DOMContentLoaded', function() {
        const suitsStitched = document.getElementById('suits_stitched');
        const ratePerSuit = document.getElementById('rate_per_suit');
        const totalAmount = document.getElementById('total_amount');
        const paidAmount = document.getElementById('paid_amount');
        const previousBalanceField = document.getElementById('previous_balance');
        const closingBalanceField = document.getElementById('closing_balance');

        // Calculate total amount based on suits stitched and rate per suit
        function calculateAmounts() {
            const stitched = parseFloat(suitsStitched.value) || 0;
            const rate = parseFloat(ratePerSuit.value) || 0;

            // Calculate total amount
            const total = stitched * rate;
            totalAmount.value = total;

            calculateClosingBalance(); // Recalculate closing balance whenever the total amount changes
        }

        // Calculate closing balance
        function calculateClosingBalance() {
            const previousBalance = parseFloat(previousBalanceField.value) || 0;
            const total = parseFloat(totalAmount.value) || 0;
            const paid = parseFloat(paidAmount.value) || 0;

            // Closing balance = Previous balance + Total amount - Paid amount
            const closingBalance = previousBalance + total - paid;
            closingBalanceField.value = closingBalance;
        }

        // Event listeners for changes in input fields
        suitsStitched.addEventListener('input', calculateAmounts);
        ratePerSuit.addEventListener('input', calculateAmounts);
        paidAmount.addEventListener('input', calculateClosingBalance); // Recalculate closing balance when paid amount changes
    });
</script>

<script>
    $(document).ready(function() {
        $('#staff_id').change(function() {
            var staff_id = $(this).val();

            if (staff_id) {
                // Use the route name to generate the URL
                var url = "{{ route('staff.previous_balance', ':staff_id') }}";
                url = url.replace(':staff_id', staff_id);

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        // Set the previous balance value in the input field
                        $('#previous_balance').val(response.previous_balance);
                    },
                    error: function(xhr, status, error) {
                        alert('An error occurred. Please try again.');
                    }
                });
            } else {
                $('#previous_balance').val('0');
            }
        });
    });
</script>