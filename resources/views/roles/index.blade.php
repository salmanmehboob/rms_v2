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
                <form id="itemForm" class="ajax-form" data-table="itemTable" action="{{ route('roles.store') }}"
                    method="POST">
                    @csrf

                    <div class="form-group">
                        <label>Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Name">
                        <div id="nameError" class="text-danger mt-1"></div>
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
                <table class="display table" id="itemTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Status</th>
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
    const table = $('#itemTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('roles.index') }}",
        columns: [{
                data: 'id',
                name: 'id'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'status',
                name: 'status'
            }, // Added status column
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
        let status = $(this).data('status');
        let formAction = $(this).data('url');

        $('form').attr('action', formAction);
        $('form').append('<input type="hidden" name="_method" value="PUT">');

        $('input[name="name"]').val(name);
        $('select[name="status"]').val(status);

        $('#submitBtn').text('Update');
        $('#cancelBtn').removeClass('d-none');
    });

    // Reset form on cancel
    $(document).on('click', '#cancelBtn', function() {
        $('form').attr('action', "{{ route('roles.store') }}");
        $('form').find('input[name="_method"]').remove();
        //   $('form')[0].reset();
        $('input[name="name"]').val('');
        $('#submitBtn').text('Save');
        $(this).addClass('d-none');
    });
})
</script>
@endpush