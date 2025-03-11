<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemCategory;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the items.
     */
    public function index()
    {
        $items = Item::with('category')->get(); // Fetch items with category relationship
        return view('items.index', compact('items'));
    }

    /**
     * Show the form for creating a new item.
     */
    public function create()
    {
        $categories = ItemCategory::all(); // Fetch all categories
        return view('items.create', compact('categories'));
    }

    /**
     * Store a newly created item in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_category_id' => 'required|exists:item_categories,id',
            'name' => 'required|string|max:255|unique:items,name',
            'image' => 'nullable|image|max:2048',
            'quantity' => 'required|integer',
            'cost_price' => 'required|numeric',
            'retail_price' => 'required|numeric',
            'status' => 'boolean',
        ]);

        $imagePath = $request->file('image')?->store('images/items', 'public');

        Item::create([
            'item_category_id' => $request->item_category_id,
            'name' => $request->name,
            'image' => $imagePath,
            'quantity' => $request->quantity,
            'cost_price' => $request->cost_price,
            'retail_price' => $request->retail_price,
            'status' => $request->status ?? 0,
        ]);

        return redirect()->route('items.index')->with('success', 'Item created successfully.');
    }

    /**
     * Show the form for editing the specified item.
     */
    public function edit(Item $item)
    {
        $categories = ItemCategory::all(); // Fetch all categories
        return view('items.edit', compact('item', 'categories'));
    }

    /**
     * Update the specified item in storage.
     */
    public function update(Request $request, Item $item)
    {
        $request->validate([
            'item_category_id' => 'required|exists:item_categories,id',
            'name' => 'required|string|max:255|unique:items,name,' . $item->id,
            'image' => 'nullable|image|max:2048',
            'quantity' => 'required|integer',
            'cost_price' => 'required|numeric',
            'retail_price' => 'required|numeric',
            'status' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images/items', 'public');
            $item->update(['image' => $imagePath]);
        }

        $item->update($request->except('image'));

        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
    }

    /**
     * Remove the specified item from storage (Soft Delete).
     */
    public function destroy(Item $item)
    {
        $item->delete(); // Soft delete
        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }

    /**
     * Restore a soft-deleted item.
     */
    public function restore($id)
    {
        Item::withTrashed()->findOrFail($id)->restore();
        return redirect()->route('items.index')->with('success', 'Item restored successfully.');
    }
}