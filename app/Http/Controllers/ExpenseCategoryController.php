<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log; // ✅ Import Log facade

class ExpenseCategoryController extends Controller
{
    /**
     * Display all expense categories.
     */
    public function index(Request $request)
    {
        $title = 'Expense Category';

        if ($request->ajax()) {
            $expenseCategories = ExpenseCategory::select(['id', 'name']);

            return DataTables()->of($expenseCategories)
                ->addColumn('actions', function ($category) {
                    return '
                    <div class="d-flex">
                        <a id="editBtn" data-url="' . route('expense.categories.update', $category->id) . '"
                           data-id="' . $category->id . '" data-name="' . $category->name . '" href="javascript:void(0)"
                           class="btn btn-primary shadow btn-xs sharp me-1"><i class="fas fa-pencil-alt"></i></a>

                        <a href="javascript:void(0)"
                           data-url="' . route('expense.categories.destroy', $category->id) . '"
                           data-label="delete"
                           data-id="' . $category->id . '"
                           data-table="expenseCategoriesTable"
                           class="btn btn-danger shadow btn-xs sharp delete-record"
                           title="Delete Record"><i class="fa fa-trash"></i></a>
                    </div>
                ';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('expense_categories.index', compact('title'));
    }

    /**
     * Show the form to create a new expense category.
     */
    public function create()
    {
        return view('expense_categories.create');
    }

    /**
     * Store a new expense category.
     */
    public function store(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:expense_categories,name',
        ]);

        if ($validator->fails()) {
            return $request->ajax()
                ? response()->json(['errors' => $validator->errors()], 422)
                : redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            ExpenseCategory::create([
                'name' => $request->name,
                'status' => $request->status ?? 0,
            ]);

            DB::commit();

            return $request->ajax()
                ? response()->json(['success' => 'Category added successfully.'])
                : redirect()->route('expense.categories.index')->with('success', 'Category added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Category Store Error: ' . $e->getMessage()); // ✅ Proper error logging

            return $request->ajax()
                ? response()->json(['error' => 'Something went wrong. Please try again.'], 500)
                : redirect()->back()->withErrors(['error' => 'Something went wrong. Please try again.'])->withInput();
        }
    }

    /**
     * Show the form to edit an expense category.
     */
    public function edit(ExpenseCategory $expenseCategory)
    {
        return view('expense_categories.edit', compact('expenseCategory'));
    }

    /**
     * Update an expense category.
     */
    public function update(Request $request, $id)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:expense_categories,name,' . $id,
        ]);

        if ($validator->fails()) {
            return $request->ajax()
                ? response()->json(['errors' => $validator->errors()], 422)
                : redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $category = ExpenseCategory::findOrFail($id);
            $category->update(['name' => $request->name]);

            return $request->ajax()
                ? response()->json(['success' => 'Category updated successfully.'])
                : redirect()->route('expense.categories.index')->with('success', 'Category updated successfully.');
        } catch (\Exception $e) {
            Log::error('Category Update Error: ' . $e->getMessage()); // ✅ Proper error logging

            return $request->ajax()
                ? response()->json(['error' => 'Failed to update category. Please try again.'], 500)
                : redirect()->back()->withErrors(['error' => 'Failed to update category.']);
        }
    }

    /**
     * Delete an expense category.
     */
    public function destroy($id)
    {
        try {
            $expenseCategory = ExpenseCategory::findOrFail($id);
            $expenseCategory->delete();

            return response()->json(['success' => 'Category deleted successfully.']);
        } catch (\Exception $e) {
            Log::error('Category Delete Error: ' . $e->getMessage()); // ✅ Proper error logging
            return response()->json(['error' => 'Failed to delete category.'], 500);
        }
    }
}