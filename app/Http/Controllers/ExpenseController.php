<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the items.
     */
    public function index(Request $request)
    {
        $title = 'Expense';
        $expenseCategories = ExpenseCategory::orderBy('name', 'asc')->get();

        //        $items = Item::with('itemCategory')->first();
        //
        //        dd($items);
        if ($request->ajax()) {
            $expense = Expense::with('expenseCategory');


            return DataTables()->of($expense)
                ->addColumn('category_name', function ($expense) {
                    return $expense->expenseCategory ? $expense->expenseCategory->name : 'N/A';
                })
                ->addColumn('image', function ($expense) {
                    if ($expense->image) {
                        $imagePath = asset($expense->image);
                        //                        dd($imagePath );
                        return '<img src="' . $imagePath . '" width="50" height="50" alt="Image">';
                    }
                    return 'No Image';
                })
                ->addColumn('actions', function ($expense) {
                    return '
                    <div class="d-flex">
                        <a id="editBtn" data-url="' . route('expenses.update', $expense->id) . '"
                           data-id="' . $expense->id . '"
                           data-name="' . $expense->name . '"
                           data-image="' . $expense->iamge . '"
                           data-category="' . $expense->expense_category_id . '"
                           data-Amount="' . $expense->Amount . '"
                           data-expense_details="' . $expense->expense_details . '"
                           data-is_stock="' . $expense->is_stock . '"
                           href="javascript:void(0)"
                           class="btn btn-primary shadow btn-xs sharp me-1"><i class="fas fa-pencil-alt"></i></a>

                        <a href="javascript:void(0)"
                           data-url="' . route('expenses.destroy', $expense->id) . '"
                           data-label="delete"
                           data-id="' . $expense->id . '"
                            data-table="expensesTable"
                           class="btn btn-danger shadow btn-xs sharp delete-record"
                           title="Delete Record"><i class="fa fa-trash"></i></a>
                    </div>
                ';
                })
                ->rawColumns(['actions', 'image']) // Ensure both actions and image are treated as raw HTML
                ->make(true);
        }

        return view('expenses.index', compact('title', 'expenseCategories'));
    }






    /**
     * Store a newly created item in storage.
     */
    public function store(Request $request)
    {

        if ($request->ajax()) {

            $validatedData = $request->validate([
                'expense_category_id' => 'required|exists:expense_categories,id',
                'name' => 'required|string|max:255|unique:expenses,name',
                'image' => 'nullable|image|max:2048',
                'amount' => 'required|integer',
                'expense_details' => 'required|string|max:65535', // Adjust max length as needed                'is_stock' => 'boolean',
            ]);

            try {
                // Start a database transaction
                DB::beginTransaction();


                // Create the item without the image first to get the ID
                $expense = Expense::create(array_merge($validatedData, ['image' => null]));

                // Handle image upload after getting the item ID
                $imagePath = null;
                if ($request->hasFile('image')) {
                    // Generate a unique file name using timestamp
                    $fileName = now()->timestamp . '.' . $request->file('image')->getClientOriginalExtension();

                    // Store the file in the public disk under a specific folder
                    $imagePath = $request->file('image')->storeAs(
                        'images/items/' . $expense->id,
                        $fileName,
                        'public'
                    );

                    // Update the item with the correct image path
                    $expense->update(['image' => 'storage/' . $imagePath]);
                }


                // Commit the transaction
                DB::commit();


                return response()->json(['success' =>  'expense created successfully.', 'data' => $expense], 201);
            } catch (\Exception $e) {
                // Rollback the transaction on error
                DB::rollBack();
                return response()->json(['success' =>  'Failed to create expense.', 'error' => $e->getMessage()], 500);
            }
        }

        return response()->json(['success' => false, 'message' => 'Invalid request.'], 400);
    }


    /**
     * Show the form for editing the specified item.
     */
    public function edit($id)
    {
        $expense = Expense::findOrFail($id); // Fetch the item by ID
        $categories = ExpenseCategory::all(); // Assuming you have categories to list

        return view('expenses.edit', compact('expense', 'categories'));
    }


    /**
     * Update the specified item in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        $validatedData = $request->validate([
            'expense_category_id' => 'required|exists:expense_categories,id',
            'name' => 'required|string|max:255|unique:expenses,name,' . $expense->id,
            'image' => 'nullable|image|max:2048',
            'amount' => 'required|integer',
            'expense_details' => 'required|string|max:65535', // Adjust max length as needed            'is_stock' => 'boolean',
        ]);

        DB::beginTransaction(); // Start the transaction

        try {
            // Handle image upload after getting the item ID
            if ($request->hasFile('image')) {
                // Check if the item already has an image and delete it
                if ($expense->image && Storage::exists(str_replace('storage/', 'public/', $expense->image))) {
                    Storage::delete(str_replace('storage/', 'public/', $expense->image));
                }

                // Generate a unique file name using timestamp
                $fileName = now()->timestamp . '.' . $request->file('image')->getClientOriginalExtension();

                // Store the file in the public disk under a specific folder
                $imagePath = $request->file('image')->storeAs(
                    'images/items/' . $expense->id,
                    $fileName,
                    'public'
                );

                // Update the item with the correct image path
                $validatedData['image'] = 'storage/' . $imagePath;
            }

            // Update the item with the validated data
            $expense->update($validatedData);

            DB::commit(); // Commit the transaction

            return response()->json(['success' => 'expense updated successfully.']);
        } catch (\Exception $e) {
            DB::rollBack(); // Roll back the transaction on error
            return response()->json(['error' => 'Failed to update expense: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified item from storage (Soft Delete).
     */
    public function destroy($id)
    {

        try {
            $expense = Expense::findOrFail($id);
            $expense->delete();
            return response()->json(['success' => 'expense deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete expense.'], 500);
        }
    }


    /**
     * Restore a soft-deleted item.
     */
    public function restore($id)
    {
        try {
            $expense = Expense::withTrashed()->findOrFail($id);
            $expense->restore();
            return response()->json(['success' => 'expense restored successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to restore expense.'], 500);
        }
    }
}