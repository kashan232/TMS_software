@include('main_includes.header_include')
<style>
    .disabled {
        pointer-events: none;
        opacity: 0.5;
    }

    .past-date {
        color: gray !important;
        pointer-events: none;
    }

    .bg-light {
        background-color: #eee !important;
    }

    .calendar_weekdays td {
        background: #f3f3f3;
        font-weight: bold;
        text-align: center;
    }

    .calendar_days tr {
        width: 100%;
        height: 80px;
    }

    calendar_days {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        /* 7 columns for the days of the week */
        grid-gap: 10px;
        margin-top: 20px;
    }

    .calendar_days td {
        padding: 10px;
        text-align: center;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .calendar_days td.bg-success {
        background-color: #28a745;
        color: white;
    }

    .calendar_days td.text-muted {
        color: #6c757d;
    }

    .calendar_days td:hover {
        background-color: #f8f9fa;
    }

    .calendar_days td .badge {
        display: block;
        margin-top: 5px;
    }

    .active-date {
        background-color: #000;
        color: #fff;
        padding: 5px 10px;
        border-radius: 3px;
    }

    .order-container {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        /* Space between badges */
        justify-content: center;
        /* Center align */
    }

    .order-badge {
        font-size: 12px;
        /* Smaller size */
        padding: 4px 8px;
        /* Adjust padding */
        white-space: nowrap;
        /* Prevent line breaks */
        border-radius: 5px;
        /* Rounded look */
    }

    .order-status-guide {
        background: #f8f9fa;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 15px;
    }

    .order-status-guide ul {
        margin: 0;
        padding: 0;
    }

    .order-status-guide .list-inline-item {
        margin-right: 10px;
    }
</style>
<div id="main-wrapper">
    @include('main_includes.navbar_include')
    @include('main_includes.admin_sidebar_include')

    <div class="content-body rightside-event">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Order calender</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="order-status-guide">
                                        <h6><strong>Order Status Guide:</strong></h6>
                                        <ul class="list-inline">
                                            <li class="list-inline-item">
                                                <span class="badge bg-primary">Order Received</span>
                                            </li>
                                            <li class="list-inline-item">
                                                <span class="badge bg-danger">Order Updated</span>
                                            </li>
                                            <li class="list-inline-item">
                                                <span class="badge bg-warning text-dark">Ready</span>
                                            </li>
                                            <li class="list-inline-item">
                                                <span class="badge bg-success">Delivered</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="text-center">
                                        <h2><span class="d-none" id="prev_next_month"></span><span id="get_month"></span> <span
                                                id="get_year"></span></h2>
                                        <div class="d-flex justify-content-center mt-3 mb-3">
                                            <div><i class=""></i>
                                                <button class="btn btn-white btn-lg" id="prev_month"><i
                                                        class="fas fa-chevron-left"></i></button>&nbsp;
                                            </div>
                                            <div>
                                                <button class="btn btn-white btn-lg" id="next_month"><i
                                                        class="fas fa-chevron-right"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <table class="table table-bordered text-center">
                                        @csrf
                                        <thead>
                                            <tr class="calendar_weekdays">
                                                <td>SUN</td>
                                                <td>MON</td>
                                                <td>TUE</td>
                                                <td>WED</td>
                                                <td>THU</td>
                                                <td>FRI</td>
                                                <td>SAT</td>
                                            </tr>
                                        </thead>
                                        <tbody class="calendar_days">
                                            <tr class="tr1"></tr>
                                            <tr class="tr2"></tr>
                                            <tr class="tr3"></tr>
                                            <tr class="tr4"></tr>
                                            <tr class="tr5"></tr>
                                            <tr class="tr6"></tr>
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
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const calendarDays = document.querySelector(".calendar_days");
        const getMonth = document.getElementById("get_month");
        const getYear = document.getElementById("get_year");

        const prevBtn = document.getElementById("prev_month");
        const nextBtn = document.getElementById("next_month");

        const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

        let today = new Date();
        let currentMonth = today.getMonth();
        let currentYear = today.getFullYear();

        function renderCalendar(month, year) {
            $.ajax({
                method: 'GET',
                url: "{{ route('get-orders') }}",
                data: {
                    'month': month + 1, // Adjusting month for 1-based index
                    'year': year
                },
                success: function(response) {
                    const firstDay = new Date(year, month, 1).getDay();
                    const lastDate = new Date(year, month + 1, 0).getDate();
                    const prevLastDate = new Date(year, month, 0).getDate();

                    let daysHTML = "<tr>";
                    let todayDate = new Date();

                    // Previous month's dates (Disabled)
                    for (let i = firstDay; i > 0; i--) {
                        daysHTML += `<td class="text-muted disabled">${prevLastDate - i + 1}</td>`;
                    }

                    // Current month's dates with orders
                    for (let i = 1; i <= lastDate; i++) {
                        let isActive = (i === todayDate.getDate() && month === todayDate.getMonth() && year === todayDate.getFullYear()) ? 'active-date' : '';
                        let isPast = (new Date(year, month, i) < todayDate) ? 'text-muted past-date' : ''; // Disable past dates

                        let orderHTML = "";

                        if (response[i]) {
                            orderHTML += `<div class="order-container">`;
                            response[i].forEach(order => {
                                let statusColor = "";
                                switch (order.status) {
                                    case "Order Received":
                                        statusColor = "bg-primary"; // Blue
                                        break;
                                    case "Order Updated":
                                        statusColor = "bg-danger"; // Red
                                        break;
                                    case "Ready":
                                        statusColor = "bg-warning text-dark"; // Yellow
                                        break;
                                    case "Delivered":
                                        statusColor = "bg-success"; // Green
                                        break;
                                    default:
                                        statusColor = "bg-secondary"; // Default Gray
                                }

                                orderHTML += `<span class="badge ${statusColor} order-badge">#${order.customer_number}</span>`;
                            });
                            orderHTML += `</div>`; // Closing order-container div
                        }

                        daysHTML += `<td class="${isPast}"><span class="${isActive}">${i}</span>${orderHTML}</td>`;

                        if ((i + firstDay) % 7 === 0) daysHTML += `</tr><tr>`;
                    }

                    // Next month's dates (Disabled)
                    let nextDays = 7 - ((firstDay + lastDate) % 7);
                    if (nextDays < 7) {
                        for (let i = 1; i <= nextDays; i++) {
                            daysHTML += `<td class="text-muted disabled">${i}</td>`;
                        }
                    }

                    daysHTML += `</tr>`; // Ensure table row is properly closed

                    calendarDays.innerHTML = daysHTML;
                    getMonth.textContent = monthNames[month];
                    getYear.textContent = year;
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error: ", error);
                }
            });
        }

        prevBtn.addEventListener("click", function() {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            renderCalendar(currentMonth, currentYear);
        });

        nextBtn.addEventListener("click", function() {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            renderCalendar(currentMonth, currentYear);
        });

        renderCalendar(currentMonth, currentYear);
    });
</script>