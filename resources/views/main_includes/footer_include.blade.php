<!-- Required vendors -->
<!-- <script src="https://code.jquery.com/jquery-3.7.1.js"></script> -->
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="/TMS_software/public/vendor/global/global.min.js"></script>
<script src="/TMS_software/public/vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
<script src="/TMS_software/public/vendor/chart-js/chart.bundle.min.js"></script>

<script src="/TMS_software/public/vendor/apexchart/apexchart.js"></script>
<script src="/TMS_software/public/vendor/peity/jquery.peity.min.js"></script>
<script src="/TMS_software/public/js/dashboard/dashboard-1.js"></script>
<script src="/TMS_software/public/vendor/bootstrap-datetimepicker/js/moment.js"></script>
<script src="/TMS_software/public/vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>

<!-- Datatable -->
<script src="/TMS_software/public/vendor/datatables/js/jquery.dataTables.min.js"></script>
<script src="/TMS_software/public/js/plugins-init/datatables.init.js"></script>


<script src="/TMS_software/public/js/custom.min.js"></script>
<script src="/TMS_software/public/js/deznav-init.js"></script>
<script src="/TMS_software/public/js/demo.js"></script>
<script src="/TMS_software/public/js/styleSwitcher.js"></script>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script> -->

<script>
    jQuery(document).ready(function() {
        setTimeout(function() {
            dezSettingsOptions.version = 'light';
            new dezSettings(dezSettingsOptions);
            setCookie('version', 'light');
        }, 1500)
    });
</script>



</body>

</html>