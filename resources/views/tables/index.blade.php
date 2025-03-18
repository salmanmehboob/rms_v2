@extends('layouts.app')
@section('title', $title)

@section('content')
<div class="card">
    <div class="card-header">
        <h6>{{ $title }}</h6>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <form id="itemForm" class="ajax-form" data-table="tablesTable" action="{{ route('tables.store') }}"
                    method="POST">
                    @csrf

                    <div class="form-group">
                        <label>Table Number <span class="text-danger">*</span></label>
                        <input type="number" name="table" class="form-control" placeholder="Table number">
                        <div id="tableError" class="text-danger mt-1"></div>
                    </div>

                    <div class="form-group">
                        <label>Total Person <span class="text-danger">*</span></label>
                        <input type="number" name="person" class="form-control" placeholder="Total person">
                        <div id="personError" class="text-danger mt-1"></div>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <button type="submit" id="submitBtn" class="btn btn-primary mt-3 float-end">Save</button>
                    <button type="button" id="cancelBtn" class="btn btn-secondary mt-3 float-end me-2 d-none">
                        Cancel Update
                    </button>
                </form>
            </div>

            <div class="col-md-8">
                <table class="display table" id="tablesTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Table Number</th>
                            <th>Total Person</th>
                            <th>Status</th>
                            <th>QR Code</th> <!-- ✅ Added -->
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script>
$(document).ready(function() {
    // DataTable Initialization
    const table = $('#tablesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('tables.index') }}",
        columns: [{
                data: 'id',
                name: 'id'
            },
            {
                data: 'table',
                name: 'table'
            },
            {
                data: 'person',
                name: 'person'
            },
            {
                data: 'status',
                name: 'status'
            }, // Status column
            {
                data: 'qr_code',
                name: 'qr_code'
            }, // QR Code column ✅ Added
            {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false
            }
        ]
    });

    // Edit Button Click
    $(document).on('click', '#editBtn', function(e) {
        e.preventDefault();

        let id = $(this).data('id');
        let table = $(this).data('table');
        let person = $(this).data('person');
        let status = $(this).data('status');
        let formAction = $(this).data('url');

        $('form').attr('action', formAction);
        $('form').append('<input type="hidden" name="_method" value="PUT">');

        $('input[name="table"]').val(table);
        $('input[name="person"]').val(person);
        $('select[name="status"]').val(status);

        $('#submitBtn').text('Update');
        $('#cancelBtn').removeClass('d-none');
    });

    // Reset form on cancel
    $(document).on('click', '#cancelBtn', function() {
        $('form').attr('action', "{{ route('tables.store') }}");
        $('form').find('input[name="_method"]').remove();
        $('input[name="table"]').val('');
        $('input[name="person"]').val('');
        $('select[name="status"]').val(''); // Reset status field
        $('#submitBtn').text('Save');
        $(this).addClass('d-none');
    });

    // ✅ QR Code Functionality (Following Your Existing Structure)
    $(document).on('click', '.generateQr', function(e) {
        e.preventDefault();

        let id = $(this).data('id');
        let button = $(this); // Store button reference

        $.ajax({
            url: "{{ route('tables.generateQr', '') }}/" + id, // Use Laravel route helper
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}"
            },
            beforeSend: function() {
                button.prop('disabled', true).text('Generating...');
            },
            success: function(response) {
                if (response.success) {
                    button.replaceWith('<a href="' + response.qr_code +
                        '" class="btn btn-success btn-sm" download>Download</a>');
                } else {
                    alert('Failed to generate QR code.');
                }
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                alert('Something went wrong.');
            },
            complete: function() {
                button.prop('disabled', false).text('Generate');
            }
        });
    });
});
</script>
@endpush