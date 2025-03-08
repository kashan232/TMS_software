<!-- <script src="https://code.jquery.com/jquery-3.7.1.js"></script> -->
<script src="/TMS_software/public/offlinelinksjs/jquery-3.7.0.js"></script>
<script src="/TMS_software/public/vendor/global/global.min.js"></script>
<script src="/TMS_software/public/vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
<script src="/TMS_software/public/vendor/chart-js/chart.bundle.min.js"></script>

<script src="/TMS_software/public/vendor/apexchart/apexchart.js"></script>
<script src="/TMS_software/public/vendor/peity/jquery.peity.min.js"></script>
<script src="/TMS_software/public/js/dashboard/dashboard-1.js"></script>
<script src="/TMS_software/public/vendor/bootstrap-datetimepicker/js/moment.js"></script>
<script src="/TMS_software/public/vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>

<!-- Datatable -->

<script src="/TMS_software/public/offlinelinksjs/jquery.dataTables.min.js"></script>
<script src="/TMS_software/public/offlinelinksjs/dataTables.buttons.min.js"></script>
<script src="/TMS_software/public/offlinelinksjs/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="/TMS_software/public/offlinelinksjs/vfs_fonts.js"></script>
<script src="/TMS_software/public/offlinelinksjs/buttons.html5.min.js"></script>

<!-- <script src="/TMS_software/public/vendor/datatables/js/jquery.dataTables.min.js"></script>
<script src="/TMS_software/public/js/plugins-init/datatables.init.js"></script> -->


<script src="/TMS_software/public/js/custom.min.js"></script>
<script src="/TMS_software/public/js/deznav-init.js"></script>
<script src="/TMS_software/public/js/demo.js"></script>
<script src="/TMS_software/public/js/styleSwitcher.js"></script>

<script src="/TMS_software/public/offlinelinksjs/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="/TMS_software/public/offlinelinksjs/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>


<!-- jsPDF & autoTable Script -->
<script src="/TMS_software/public/offlinelinksjs/jspdf.umd.min.js"></script>
<script src="/TMS_software/public/offlinelinksjs/jspdf.plugin.autotable.min.js"></script>

<script>
    jQuery(document).ready(function() {
        setTimeout(function() {
            dezSettingsOptions.version = 'light';
            new dezSettings(dezSettingsOptions);
            setCookie('version', 'light');
        }, 1500)
    });
</script>



<script>
    $(document).ready(function() {
        if ($.fn.DataTable.isDataTable('#example')) {
            $('#example').DataTable().destroy(); // Pehle destroy karein
        }

        $('#example').DataTable({
            "paging": false, // Pagination hata dein
            "info": false, // "Showing X of Y entries" text hata dein
            "lengthChange": false, // Length dropdown hata dein
            "ordering": false, // Sorting disable kar dein
            "searching": true, // Sirf search bar rakhain
            "bFilter": true // Search functionality ko enable rakhein
        });
    });
</script>

</body>

</html>