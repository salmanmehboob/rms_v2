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
                <form id="itemForm" class="ajax-form" data-table="itemTable" action="{{ route('expenses.store') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf


                    <div class="form-group">
                        <label> Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Name">
                        <div id="nameError" class="text-danger mt-1"></div>
                    </div>




                    <div class="form-group">
                        <label>Category <span class="text-danger">*</span></label>
                        <select name="expense_category_id" class="single-select-placeholder select2"
                            style="width: 100%;">
                            <option value="" disabled selected>Select a category</option>
                            @foreach($expenseCategories as $category)
                            <option value="{{$category->id}}">{{$category->name}}</option>
                            @endforeach
                        </select>

                        <div id="expense_category_idError" class="text-danger mt-1"></div>
                    </div>

                    <div class="form-group">
                        <label> Expense Amount <span class="text-danger">*</span></label>
                        <input type="number" name="amount" class="form-control" placeholder="Amount">
                        <div id="AmountError" class="text-danger mt-1"></div>
                    </div>

                    <div class="form-group">
                        <label> Expense Details<span class="text-danger">*</span></label>
                        <textarea name="expense_details" class="form-control" placeholder="Expense Details"
                            rows="5"></textarea>
                        <div id="expensedetailsError" class="text-danger mt-1"></div>
                    </div>


                    <div class="form-group">
                        <label> Image</label>
                        <input type="file" name="image" class="form-control">
                        <div id="imageError" class="text-danger mt-1"></div>
                    </div>

                    <button type="submit" id="submitBtn" class="btn btn-primary mt-3 float-end submit-btn">Save</button>
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
                            <th>Category</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Expense Amount</th>
                            <th>Expense Details</th>
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
        ajax: "{{ route('expenses.index') }}",
        columns: [{
                data: 'id',
                name: 'id'
            },
            {
                data: 'category_name',
                name: 'category_name'
            },
            {
                data: 'image',
                name: 'image'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'amount',
                name: 'amount'
            },
            {
                data: 'expense_details',
                name: 'expense_details'
            },
            {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false
            },
        ]
    });

    // Edit Button Click
    $(document).on('click', '#editBtn', function(e) {
        e.preventDefault();

        // Get data attributes from the button
        let id = $(this).data('id');
        let name = $(this).data('name');
        let image = $(this).data('image');
        let amount = $(this).data('amount');
        let expensedetails = $(this).data('expense_details');
        let categoryId = $(this).data('category'); // Fixed here
        let formAction = $(this).data('url');

        // Set the form action and method
        $('form').attr('action', formAction);
        $('form').append('@method("PUT")');

        // Populate input fields
        $('input[name="id"]').val(id);
        $('input[name="name"]').val(name);
        $('input[name="image"]').val(image);
        $('input[name="amount"]').val(amount);
        $('textarea[name="expense_details"]').val(expensedetails); // Populate textarea
        $('select[name="expense_category_id"]').val(categoryId).trigger('change'); // Fixed here

        // Update button and show modal
        $('#submitBtn').text('Update');
        $('#cancelBtn').removeClass('d-none');
    });


    // Reset form on new category add
    $(document).on('click', '#cancelBtn', function() {
        $('form').attr('action', "{{ route('expenses.store') }}"); // Reset to store action
        $('form').find('input[name="_method"]').remove(); // Remove the PUT method
        $('input[name="name"]').val('');
        $('input[name="image"]').val('');
        $('input[name="amount"]').val('');
        $('textarea[name="expense_details"]').val('');
        $('select[name="expense_category_id"]').val('').trigger('change');
        $('#submitBtn').text('Save'); // Reset button text
        $(this).addClass('d-none');
    });



    // // Cancel Update
    // $(document).on('click', '#cancelBtn', function() {
    //     $('form').attr('action', "{{ route('expenses.store') }}");
    //     $('form').find('input[name="_method"]').remove();
    //     $('form')[0].reset();
    //     $('#submitBtn').text('Save');
    //     $(this).addClass('d-none');
    // });
});
</script>

@endpush