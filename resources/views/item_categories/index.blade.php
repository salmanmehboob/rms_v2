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
                    <form action="{{ route('item.categories.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label>Category Name<span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Category Name">
                            @if ($errors->has('name'))
                                <div class="text-danger mt-1">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                        <button type="submit" id="submitBtn" class="btn btn-primary mt-3 float-end">Save</button>
                        <button type="button" id="newCategoryBtn" class="btn btn-secondary mt-3 float-end me-2 d-none">
                            Add {{ $title }}</button>

                    </form>
                </div>
                <div class="col-md-8">
                    <table class="table table-striped" id="itemCategoriesTable">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).ready(function () {
            // Handle Edit Button Click
            $(document).on('click', '#editBtn', function (e) {
                e.preventDefault();

                // Get data attributes from the clicked button

                let name = $(this).data('name');
                let formAction = $(this).data('url');


                $('form').attr('action', formAction); // Update form action
                $('form').append('@method("PUT")'); // Add PUT method for update
                $('input[name="name"]').val(name); // Set the name value
                $('#submitBtn').text('Update'); // Change button text
                $('#newCategoryBtn').removeClass('d-none');
            });

            // Reset form on new category add
            $(document).on('click', '#newCategoryBtn', function () {
                $('form').attr('action', "{{ route('item.categories.store') }}"); // Reset to store action
                $('form').find('input[name="_method"]').remove(); // Remove the PUT method
                $('input[name="name"]').val(''); // Clear the input field
                $('#submitBtn').text('Save'); // Reset button text
                $(this).addClass('d-none');
            });

            $('#itemCategoriesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('item.categories.index') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'actions', name: 'actions', orderable: false, searchable: false},
                ]
            });
        });
    </script>

@endpush
