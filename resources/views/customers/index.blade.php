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
                <form id="itemForm" class="ajax-form" data-table="customersTable"
                    action="{{ route('customers.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label>Customer Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Customer Name">
                        <div id="nameError" class="text-danger mt-1"></div>
                    </div>

                    <div class="form-group">
                        <label>Customer Address <span class="text-danger">*</span></label>
                        <input type="text" name="address" class="form-control" placeholder="Customer Address">
                        <div id="addressError" class="text-danger mt-1"></div>
                    </div>

                    <div class="form-group">
                        <label>Customer Phone <span class="text-danger">*</span></label>
                        <input type="phone" name="phone" class="form-control" placeholder="Customer Phone">
                        <div id="phoneError" class="text-danger mt-1"></div>
                    </div>



                    <button type="submit" id="submitBtn" class="btn btn-primary mt-3 float-end">Save</button>
                    <button type="button" id="cancelBtn" class="btn btn-secondary mt-3 float-end me-2 d-none">
                        Cancel Update
                    </button>
                </form>
            </div>

            <div class="col-md-8">
                <table class="display table" id="customersTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer Name</th>
                            <th>Customer Address</th>
                            <th>Customer Phone</th>
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
    const table = $('#customersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('customers.index') }}",
        columns: [{
                data: 'id',
                name: 'id'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'address',
                name: 'address'
            },
            {
                data: 'phone',
                name: 'phone'
            },
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
        let name = $(this).data('name');
        let address = $(this).data('address');
        let phone = $(this).data('phone');
        let formAction = $(this).data('url');

        $('form').attr('action', formAction);
        $('form').append('<input type="hidden" name="_method" value="PUT">');

        $('input[name="name"]').val(name);
        $('input[name="address"]').val(address);
        $('input[name="phone"]').val(phone);

        $('#submitBtn').text('Update');
        $('#cancelBtn').removeClass('d-none');
    });

    // Reset form on cancel
    $(document).on('click', '#cancelBtn', function() {
        $('form').attr('action', "{{ route('customers.store') }}");
        $('form').find('input[name="_method"]').remove();
        //   $('form')[0].reset();
        $('input[name="name"]').val('');
        $('input[name="address"]').val('');
        $('input[name="phone"]').val('');
        $('#submitBtn').text('Save');
        $(this).addClass('d-none');
    });
})
</script>
@endpush