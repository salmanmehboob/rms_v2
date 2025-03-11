$(document).ready(function () {
    // Check if jQuery is loaded
    if (typeof $ === 'undefined') {
        console.error('jQuery is not loaded.');
        return;
    }

    // Check if Toastr is loaded
    if (typeof toastr === 'undefined') {
        console.error('Toastr is not loaded.');
        return;
    }

// Access session messages from the global `window.sessionMessages` object
    const messages = window.sessionMessages || {};

    if (messages.success) {
        toastr.success(messages.success, "Success", {
            timeOut: 5000,
            closeButton: true,
            debug: false,
            newestOnTop: true,
            progressBar: true,
            positionClass: "toast-top-right",
            preventDuplicates: true,
            onclick: null,
            showDuration: "300",
            hideDuration: "1000",
            extendedTimeOut: "1000",
            showEasing: "swing",
            hideEasing: "linear",
            showMethod: "fadeIn",
            hideMethod: "fadeOut",
            tapToDismiss: false
        });
    }

    if (messages.error) {
        toastr.error(messages.error, "Error", {
            timeOut: 5000,
            closeButton: true,
            debug: false,
            newestOnTop: true,
            progressBar: true,
            positionClass: "toast-top-right",
            preventDuplicates: true,
            onclick: null,
            showDuration: "300",
            hideDuration: "1000",
            extendedTimeOut: "1000",
            showEasing: "swing",
            hideEasing: "linear",
            showMethod: "fadeIn",
            hideMethod: "fadeOut",
            tapToDismiss: false
        });
    }

    if (messages.info) {
        toastr.info(messages.info, "Info", {
            timeOut: 5000,
            closeButton: true,
            debug: false,
            newestOnTop: true,
            progressBar: true,
            positionClass: "toast-top-right",
            preventDuplicates: true,
            onclick: null,
            showDuration: "300",
            hideDuration: "1000",
            extendedTimeOut: "1000",
            showEasing: "swing",
            hideEasing: "linear",
            showMethod: "fadeIn",
            hideMethod: "fadeOut",
            tapToDismiss: false
        });
    }

    if (messages.warning) {
        toastr.warning(messages.warning, "Warning", {
            timeOut: 5000,
            closeButton: true,
            debug: false,
            newestOnTop: true,
            progressBar: true,
            positionClass: "toast-top-right",
            preventDuplicates: true,
            onclick: null,
            showDuration: "300",
            hideDuration: "1000",
            extendedTimeOut: "1000",
            showEasing: "swing",
            hideEasing: "linear",
            showMethod: "fadeIn",
            hideMethod: "fadeOut",
            tapToDismiss: false
        });
    }


    // Initialize DataTables
    if ($.fn.DataTable) {

        $('.datatable').DataTable({
            createdRow: function (row, data, index) {
                $(row).addClass('selected')
            },
            language: {
                paginate: {
                    next: '>',
                    previous: '<'
                }
            }
        });
    } else {
        console.error('DataTables is not loaded.');
    }

    //
    // if ($.fn.select2) {
    //     $('.select2').select2({
    //         placeholder: "Select Option",
    //         allowClear: true
    //     });
    // } else {
    //     console.error('Select2 is not loaded.');
    // }
    // Initialize Select2


    $(document).on('click', '.delete-record', function (e) {
        e.preventDefault();

        var url = $(this).data('url'); // Get delete URL
        var id = $(this).data('id');   // Get record ID
        var dataTable = $(this).data('table');   // Get record ID


        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: "DELETE",
                    data: {
                        "_token": CSRF_TOKEN
                    },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: response.success,
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                // Reload the DataTable on success
                                $('#'+dataTable).DataTable().ajax.reload(null, false);

                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: response.error,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function (xhr) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Something went wrong. Please try again later.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    });


});
