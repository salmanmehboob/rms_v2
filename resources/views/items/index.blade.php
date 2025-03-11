@extends('layouts.app')
{{--@section('title', $title)--}}

@section('content')
{{--    <div class="card">--}}
{{--        <div class="card-header">--}}
{{--            <h6>{{ $title }}</h6>--}}
{{--        </div>--}}

        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <form id="itemForm" class="ajax-form" data-table="itemTable"
                          action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <label> category <span class="text-danger">*</span></label>
                        <select id="single-select">
                            <option value="AL">fastfood</option>
                            <option value="WY">drinks</option>
                        </select>

                        <div class="form-group">
                            <label> Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Name">
                            <div id="nameError" class="text-danger mt-1"></div>
                        </div>

                        <div class="form-group">
                            <label> Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" class="form-control" placeholder="Quantity">
                            <div id="quantityError" class="text-danger mt-1"></div>
                        </div>

                        <div class="form-group">
                            <label> Cost Price <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="cost_price" class="form-control" placeholder="Cost Price">
                            <div id="costPriceError" class="text-danger mt-1"></div>
                        </div>

                        <div class="form-group">
                            <label> Retail Price <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="retail_price" class="form-control" placeholder="Retail Price">
                            <div id="retailPriceError" class="text-danger mt-1"></div>
                        </div>

                        <div class="form-group">
                            <label> Image</label>
                            <input type="file" name="image" class="form-control">
                            <div id="imageError" class="text-danger mt-1"></div>
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
                            <th>Category</th>>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Quantity</th>
                            <th>Cost Price</th>
                            <th>Retail Price</th>

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
            $(document).ready(function () {
                // DataTable Initialization
                const table = $('#itemTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('items.index') }}",
                    columns: [
                        {data: 'id', name: 'id'},
                        {data: 'category', name: 'category'}, // Fixed: Added category column
                        {
                            data: 'image',
                            name: 'image',
                            render: function(data) {
                                return data ? `<img src="/storage/${data}" width="50" height="50" />` : 'N/A';
                            }
                        },
                        {data: 'name', name: 'name'},
                        {data: 'quantity', name: 'quantity'},
                        {data: 'cost_price', name: 'cost_price'},
                        {data: 'retail_price', name: 'retail_price'},
                        {data: 'actions', name: 'actions', orderable: false, searchable: false},
                    ]
                });

                // Form Submission (AJAX)
                $('#itemForm').on('submit', function (e) {
                    e.preventDefault();

                    let form = $(this);
                    let formData = new FormData(this); // Use FormData for file uploads

                    $.ajax({
                        url: form.attr('action'),
                        type: form.find('input[name="_method"]').val() || 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            if (response.success) {
                                alert(response.message);
                                table.ajax.reload();
                                form[0].reset();
                                $('#submitBtn').text('Save');
                                $('#cancelBtn').addClass('d-none');
                            } else {
                                alert('Failed to save item.');
                            }
                        },
                        error: function (xhr) {
                            let errors = xhr.responseJSON?.errors || {};
                            $('#nameError').text(errors.name ? errors.name[0] : '');
                            $('#quantityError').text(errors.quantity ? errors.quantity[0] : '');
                            $('#costPriceError').text(errors.cost_price ? errors.cost_price[0] : '');
                            $('#retailPriceError').text(errors.retail_price ? errors.retail_price[0] : '');
                            $('#imageError').text(errors.image ? errors.image[0] : '');
                        }
                    });
                });

                // Edit Button Click
                $(document).on('click', '#editBtn', function (e) {
                    e.preventDefault();
                    let name = $(this).data('name');
                    let quantity = $(this).data('quantity');
                    let costPrice = $(this).data('cost_price');
                    let retailPrice = $(this).data('retail_price');
                    let formAction = $(this).data('url');

                    $('form').attr('action', formAction).append('@method("PUT")');
                    $('input[name="name"]').val(name);
                    $('input[name="quantity"]').val(quantity);
                    $('input[name="cost_price"]').val(costPrice);
                    $('input[name="retail_price"]').val(retailPrice);
                    $('#submitBtn').text('Update');
                    $('#cancelBtn').removeClass('d-none');
                });

                // Cancel Update
                $(document).on('click', '#cancelBtn', function () {
                    $('form').attr('action', "{{ route('items.store') }}");
                    $('form').find('input[name="_method"]').remove();
                    $('form')[0].reset();
                    $('#submitBtn').text('Save');
                    $(this).addClass('d-none');
                });
            });
        </script>

@endpush
