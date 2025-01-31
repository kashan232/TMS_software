@include('main_includes.header_include')
<style>
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
            const firstDay = new Date(year, month, 1).getDay();
            const lastDate = new Date(year, month + 1, 0).getDate();
            const prevLastDate = new Date(year, month, 0).getDate();

            let daysHTML = "";
            let todayDate = new Date();

            // Previous month's dates
            for (let i = firstDay; i > 0; i--) {
                daysHTML += `<td class="text-muted">${prevLastDate - i + 1}</td>`;
            }

            // Current month's dates
            for (let i = 1; i <= lastDate; i++) {
                let isActive = (i === todayDate.getDate() && month === todayDate.getMonth() && year === todayDate.getFullYear()) ? 'bg-success text-white' : '';
                daysHTML += `<td class="${isActive}">${i}</td>`;
                if ((i + firstDay) % 7 === 0) daysHTML += `</tr><tr>`;
            }

            // Next month's dates
            let nextDays = 7 - ((firstDay + lastDate) % 7);
            if (nextDays < 7) {
                for (let i = 1; i <= nextDays; i++) {
                    daysHTML += `<td class="text-muted">${i}</td>`;
                }
            }

            calendarDays.innerHTML = `<tr>${daysHTML}</tr>`;
            getMonth.textContent = monthNames[month];
            getYear.textContent = year;
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