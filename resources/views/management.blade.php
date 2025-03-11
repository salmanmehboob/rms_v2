@extends('layouts.app')

@section('title', 'Management Dashboard')

@section('content')

    <div class="row">

        {{-- Item Categories Section --}}
        <div class="col-xl-3 col-lg-6 col-sm-6">
            <div class="widget-stat card bg-info">
                <div class="card-body p-4">
                    <a href="{{ route('item.categories.index') }}">
                        <div class="media">
                            <span class="mr-3">
                                <i class="flaticon-381-archive"></i>
                            </span>
                            <div class="media-body text-white text-right">
                                <p class="mb-1">Item Categories</p>
                                <h3 class="text-white">0</h3>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>


        {{-- Placeholder for Users Section --}}
        <div class="col-xl-3 col-lg-6 col-sm-6">
            <div class="widget-stat card bg-success">
                <div class="card-body p-4">
                    <a href="#">
                        <div class="media">
                            <span class="mr-3">
                                <i class="flaticon-381-user-8"></i>
                            </span>
                            <div class="media-body text-white text-right">
                                <p class="mb-1">Users</p>
                                <h3 class="text-white">0</h3>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        {{-- Placeholder for Items Section --}}
        <div class="col-xl-3 col-lg-6 col-sm-6">
            <div class="widget-stat card bg-primary">
                <div class="card-body p-4">
                    <a href="#">
                        <div class="media">
                            <span class="mr-3">
                                <i class="flaticon-381-umbrella"></i>
                            </span>
                            <div class="media-body text-white text-right">
                                <p class="mb-1">Items</p>
                                <h3 class="text-white">0</h3>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        {{-- Placeholder for Jobs Section --}}
        <div class="col-xl-3 col-lg-6 col-sm-6">
            <div class="widget-stat card bg-danger">
                <div class="card-body p-4">
                    <a href="#">
                        <div class="media">
                            <span class="mr-3">
                                <i class="flaticon-381-user-7"></i>
                            </span>
                            <div class="media-body text-white text-right">
                                <p class="mb-1">Jobs</p>
                                <h3 class="text-white">0</h3>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        {{-- Placeholder for Other Sections --}}
        <div class="col-xl-3 col-lg-6 col-sm-6">
            <div class="widget-stat card bg-warning">
                <div class="card-body p-4">
                    <a href="#">
                        <div class="media">
                            <span class="mr-3">
                                <i class="flaticon-381-more-2"></i>
                            </span>
                            <div class="media-body text-white text-right">
                                <p class="mb-1">Other</p>
                                <h3 class="text-white">0</h3>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

    </div>

@endsection
