@extends('layouts.app')

@section('content')
<div class="container">
    <!-- <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('item_categories.index') }}">All Item Categories</a></li>
            <li class="breadcrumb-item active">Edit Item Category</li>
        </ol>
    </nav> -->

    <div class="card">
        <div class="card-header">
            <h6>Edit Item Category</h6>
            <p>Updated {{ $itemCategory->updated_at->diffForHumans() }}</p>
        </div>
        <div class="card-body">
            <form action="{{ route('item_categories.update', $itemCategory->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Category Name<span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ $itemCategory->name }}" required>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>
@endsection