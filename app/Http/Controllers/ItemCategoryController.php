<?php

namespace App\Http\Controllers;

use App\Models\ItemCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemCategoryController extends Controller
{
    /**
     * Display all item categories.
     */
    public function index()
    {
        $title = 'Item Category';
        $itemCategories = ItemCategory::all();
        return view('item_categories.index', compact('title','itemCategories'));
    }

    /**
     * Show the form to create a new item category.
     */
    public function create()
    {
        return view('item_categories.create');
    }

    /**
     * Store a new item category.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:item_categories,name',
        ]);


        try {
            // Start a database transaction
            DB::beginTransaction();

            // Create the item category
            ItemCategory::create([
                'name' => $validatedData['name'],
                'status' => $validatedData['status'] ?? 0,
            ]);

            // Commit the transaction
            DB::commit();

            return redirect()->route('item.categories.index')->with('success', 'Category added successfully.');
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollBack();

            // Return back with error message and input
            return redirect()->back()->withErrors(['error' => 'Something went wrong. Please try again.'])->withInput();
        }
    }


    /**
     * Show the form to edit an item category.
     */
    public function edit(ItemCategory $itemCategory)
    {
        return view('item_categories.edit', compact('itemCategory'));
    }

    /**
     * Update an item category.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:item_categories,name,' . $id,
        ]);

        try {
            $category = ItemCategory::findOrFail($id);
            $category->update(['name' => $request->name]);

            return redirect()->route('item.categories.index')->with('success', 'Category updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to update category.']);
        }
    }
    /**
     * Delete an item category.
     */
    public function destroy(ItemCategory $itemCategory)
    {
        $itemCategory->delete();
        return redirect()->route('item_categories.index')->with('success', 'Category deleted successfully.');
    }
}
