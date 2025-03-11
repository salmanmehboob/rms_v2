<!--**********************************
        Scripts
    ***********************************-->


<!-- Required vendors -->
<script src="{{ asset('assets/vendor/global/global.min.js') }}"></script>
<script src="{{ asset('assets/vendor/chart.js/Chart.bundle.min.js')  }}"></script>
<script src="{{ asset('assets/vendor/bootstrap-select/dist/js/bootstrap-select.min.js')  }}"></script>
<script src="{{ asset('assets/vendor/apexchart/apexchart.js')  }}"></script>


<!-- Toastr -->
<script src="{{ asset('assets/vendor/toastr/js/toastr.min.js') }}"></script>

<!-- Datatable -->
<script src="{{ asset('assets/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>

<!-- Sweet Alert -->
<script src="{{ asset('assets/vendor/sweetalert2/dist/sweetalert2.min.js') }}"></script>


<script src="{{ asset('assets/js/theme_custom.js')  }}"></script>
<script src="{{ asset('assets/js/custom.js')  }}"></script>
<script src="{{ asset('assets/js/deznav-init.js')  }}"></script>
<script src="{{ asset('assets/js/styleSwitcher.js')  }}"></script>

<script>
    var urlPath = '<?php echo url(""); ?>';
    var CSRF_TOKEN = '<?php echo csrf_token(); ?>';


    window.sessionMessages = {
        success: @json(session('success')),
        error: @json(session('error')),
        info: @json(session('info'))
    };
</script>

@stack('js')
