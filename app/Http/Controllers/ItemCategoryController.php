<?php

namespace App\Http\Controllers;

use App\Models\ItemCategory;
use Illuminate\Http\Request;

class ItemCategoryController extends Controller
{
    /**
     * Display all item categories.
     */
    public function index()
    {
        $itemCategories = ItemCategory::all();
        return view('item_categories.index', compact('itemCategories'));
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
        $request->validate([
            'name' => 'required|string|max:255|unique:item_categories,name',
            'status' => 'boolean',
        ]);

        ItemCategory::create([
            'name' => $request->name,
            'status' => $request->status ?? 0,
        ]);

        return redirect()->route('item_categories.index')->with('success', 'Category added successfully.');
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
    public function update(Request $request, ItemCategory $itemCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:item_categories,name,' . $itemCategory->id,
            'status' => 'boolean',
        ]);

        $itemCategory->update([
            'name' => $request->name,
            'status' => $request->status ?? 0,
        ]);

        return redirect()->route('item_categories.index')->with('success', 'Category updated successfully.');
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