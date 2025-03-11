<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ItemCategoryController;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('dashboard');
    }
    public function management()
    {
        // Fetch counts
        // $totalItemCategories = ItemCategoryController::count();

        // Define user permissions (In a real app, these should be fetched dynamically from roles/permissions)
        $userPermissions = [
            'viewItemCategory',
            'addItemCategory'
        ];

        return view('dashboard.management', compact('totalItemCategories', 'userPermissions'));
    }
}