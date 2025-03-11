<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
     * Display a listing of the items.
     */
    public function index(Request $request)
    {

        $title = 'Item';

        if ($request->ajax()) {
            $items = Item::with('category')->select(['id',
                'name',
                'image',
                'quantity',
                'cost_price',
                'retail_price',
                'is_stock',]);
            return datatables()->of($items)
                ->addColumn('category', function ($item) {
                   return $item->category->name;
                })
                ->addColumn('image', function ($item) {
                    return $item->image;
                })
                ->addColumn('actions', function ($item) {
                    return '
                    <div class="d-flex">
                        <a id="editBtn" data-url="' . route('items.update', $item->id) . '"
                           data-id="' . $item->id . '" data-name="' . $item->name . '" href="javascript:void(0)"
                           class="btn btn-primary shadow btn-xs sharp me-1"><i class="fas fa-pencil-alt"></i></a>

                        <a href="javascript:void(0)"
                           data-url="' . route('items.destroy', $item->id) . '"
                           data-label="delete"
                           data-id="' . $item->id . '"
                           data-table="itemTable"
                           class="btn btn-danger shadow btn-xs sharp delete-record"
                           title="Delete Record"><i class="fa fa-trash"></i></a>
                    </div>
                ';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }


        return view('items.index', compact('title'));
    }

    /**
     * Show the form for creating a new item.
     */
    public function create()
    {

        $item = Item::all();
        return view('items.create', compact($item));
    }

    /**
     * Store a newly created item in storage.
     */
    public function store(Request $request)
    {
        if ($request->ajax()) {
            $validatedData = $request->validate([
                'item_category_id' => 'required|exists:item_categories,id',
                'name' => 'required|string|max:255|unique:items,name',
                'image' => 'nullable|image|max:2048',
                'quantity' => 'required|integer',
                'cost_price' => 'required|numeric',
                'retail_price' => 'required|numeric',
                'is_stock' => 'boolean',
            ]);

            try {
                // Start a database transaction
                DB::beginTransaction();


                // Create the item without the image first to get the ID
                $item = Item::create(array_merge($validatedData, ['image' => null]));

                // Handle image upload after getting the item ID
                $imagePath = null;
                if ($request->hasFile('image')) {
                    // Construct the path with the item ID
                    $imagePath = $request->file('image')->storeAs(
                        'images/items/' . $item->id,
                        uniqueid() . '.' . $request->file('image')->getClientOriginalExtension(),
                        'public'
                    );

                    // Update the item with the correct image path
                    $item->update(['image' => $imagePath]);
                }

                // Commit the transaction
                DB::commit();


                return response()->json(['success' => true, 'message' => 'Item created successfully.', 'data' => $item], 201);
            } catch (\Exception $e) {
                // Rollback the transaction on error
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Failed to create item.', 'error' => $e->getMessage()], 500);
            }
        }

        return response()->json(['success' => false, 'message' => 'Invalid request.'], 400);
    }


    /**
     * Show the form for editing the specified item.
     */
    public function edit()
    {

        $item = Item::all();
        return view('items.edit', compact($item ));
    }

    /**
     * Update the specified item in storage.
     */
    public function update(Request $request, Item $item)
    {
        $validatedData = $request->validate([
            'item_category_id' => 'required|exists:item_categories,id',
            'name' => 'required|string|max:255|unique:items,name,' . $item->id,
            'image' => 'nullable|image|max:2048',
            'quantity' => 'required|integer',
            'cost_price' => 'required|numeric',
            'retail_price' => 'required|numeric',
            'is_stock' => 'boolean',
        ]);

        try {
            // Handle image update
            if ($request->hasFile('image')) {
                if ($item->image) {
                    Storage::disk('public')->delete($item->image);
                }
                $imagePath = $request->file('image')->store('images/items', 'public');
                $validatedData['image'] = $imagePath;
            }

            // Update the item
            $item->update($validatedData);

            return response()->json(['success' => 'Item updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update item.'], 500);
        }
    }

    /**
     * Remove the specified item from storage (Soft Delete).
     */
    public function destroy(Item $item)
    {
        try {
            $item->delete();
            return response()->json(['success' => 'Item deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete item.'], 500);
        }
    }

    /**
     * Restore a soft-deleted item.
     */
    public function restore($id)
    {
        try {
            $item = Item::withTrashed()->findOrFail($id);
            $item->restore();
            return response()->json(['success' => 'Item restored successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to restore item.'], 500);
        }
    }
}
