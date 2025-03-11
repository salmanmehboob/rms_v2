@extends('layouts.app')

@section('content')
<div class="container">
    <!-- <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('item_categories.index') }}">All Item Categories</a></li>
            <li class="breadcrumb-item active">Add Item Category</li>
        </ol>
    </nav> -->

    <div class="card">
        <div class="card-header">
            <h6>Add Item Category</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('item_categories.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Category Name<span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="Category Name" required>
                </div>

                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
</div>
@endsection