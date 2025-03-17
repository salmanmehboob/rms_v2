<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Expense;
use App\Models\ItemCategory;
use App\Models\ExpenseCategory;
use App\Models\Role;
use App\Models\Table;

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

        $itemCategoryCount = ItemCategory::count();
        $itemCount = Item::count();
        $expenseCategoryCount = ExpenseCategory::count();
        $expenseCount = Expense::count();
        $tableCount = Table::count();
        $roleCount = Role::count();
        return view('management',compact('itemCategoryCount' ,'itemCount', 'expenseCategoryCount', 'expenseCount', 'tableCount', 'roleCount'));
    }
}