<div class="deznav pt-4">
    <div class="deznav-scroll">
        <ul class="metismenu" id="menu">
            <li><a href="{{ route('home') }}" class="ai-icon" aria-expanded="false">
                    <i class="flaticon-381-networking"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>
            <li><a class="has-arrow ai-icon" href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-381-networking"></i>
                    <span class="nav-text">Measurements</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('cloth-type') }}">Cloth Type</a></li>
                    <li><a href="{{ route('create-measurement-parts') }}">Create Measurement Parts</a></li>
                    <li><a href="{{ route('view-measurement-parts') }}">View Measurement Parts</a></li>
                </ul>
            </li>
            <li><a href="{{ route('price-list') }}" class="ai-icon" aria-expanded="false">
                    <i class="flaticon-381-list"></i>
                    <span class="nav-text">Price Management</span>
                </a>
            </li>
            <li><a class="has-arrow ai-icon" href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-381-networking"></i>
                    <span class="nav-text">Manage Staff</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('designations') }}">Staff Designation</a></li>
                    <li><a href="{{ route('add-staff') }}">Add Staff</a></li>
                    <li><a href="{{ route('staffs') }}">View & Edit Staff</a></li>
                    <li><a href="{{ route('staff.expenses.index') }}">Staff Expenses</a></li>
                    <li><a href="{{ route('staff-expenses-record') }}">Expenses Record</a></li>

                    
                </ul>
            </li>
            
            <li><a class="has-arrow ai-icon" href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-381-networking"></i>
                    <span class="nav-text">Expenses</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('add-expenses') }}">Add Expenses</a></li>
                    <li><a href="{{ route('expenses') }}">View & Edit Expense</a></li>
                    <li><a href="{{ route('add-expense-category') }}">Add Expenses Category</a></li>
                    <li><a href="{{ route('expenses') }}">View & Edit Expense Category</a></li>


                    
                </ul>
            </li>
            
            <li><a href="#" class="ai-icon" aria-expanded="false">
                    <i class="flaticon-381-list"></i>
                    <span class="nav-text">Report</span>
                </a>
            </li>

        </ul>
    </div>
</div>