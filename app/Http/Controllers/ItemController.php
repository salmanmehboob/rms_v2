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
        $itemCategories = ItemCategory::orderBy('name', 'asc')->get();

//        $items = Item::with('itemCategory')->first();
//
//        dd($items);
        if ($request->ajax()) {
            $items = Item::with('itemCategory');


            return dataTables()->of($items)
                ->addColumn('category_name', function ($item) {
                     return $item->itemCategory ? $item->itemCategory->name : 'N/A';
                })
                ->addColumn('image', function ($item) {
                    if ($item->image) {
                        $imagePath = asset($item->image) ;
//                        dd($imagePath );
                        return '<img src="'.$imagePath.'" width="50" height="50" alt="Image">';
                    }
                    return 'No Image';
                })
                ->addColumn('actions', function ($item) {
                    return '
                    <div class="d-flex">
                        <a id="editBtn" data-url="' . route('items.update', $item->id) . '"
                           data-id="' . $item->id . '"
                           data-name="' . $item->name . '"
                           data-image="' . $item->iamge . '"
                           data-category="' . $item->item_category_id . '"
                           data-quantity="' . $item->quantity . '"
                           data-cost_price="' . $item->cost_price . '"
                           data-retail_price="' . $item->retail_price . '"
                           data-is_stock="' . $item->is_stock . '"
                           href="javascript:void(0)"
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
                ->rawColumns(['actions', 'image']) // Ensure both actions and image are treated as raw HTML
                ->make(true);
        }

        return view('items.index', compact('title', 'itemCategories'));
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
                    // Generate a unique file name using timestamp
                    $fileName = now()->timestamp . '.' . $request->file('image')->getClientOriginalExtension();

                    // Store the file in the public disk under a specific folder
                    $imagePath = $request->file('image')->storeAs(
                        'images/items/' . $item->id,
                        $fileName,
                        'public'
                    );

                    // Update the item with the correct image path
                    $item->update(['image' => 'storage/' . $imagePath]);
                }


                // Commit the transaction
                DB::commit();


                return response()->json(['success' =>  'Item created successfully.', 'data' => $item], 201);
            } catch (\Exception $e) {
                // Rollback the transaction on error
                DB::rollBack();
                return response()->json(['success' =>  'Failed to create item.', 'error' => $e->getMessage()], 500);
            }
        }

        return response()->json(['success' => false, 'message' => 'Invalid request.'], 400);
    }


    /**
     * Show the form for editing the specified item.
     */
    public function edit($id)
    {
        $item = Item::findOrFail($id); // Fetch the item by ID
        $categories = ItemCategory::all(); // Assuming you have categories to list

        return view('items.edit', compact('item', 'categories'));
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

        DB::beginTransaction(); // Start the transaction

        try {
            // Handle image upload after getting the item ID
            if ($request->hasFile('image')) {
                // Check if the item already has an image and delete it
                if ($item->image && Storage::exists(str_replace('storage/', 'public/', $item->image))) {
                    Storage::delete(str_replace('storage/', 'public/', $item->image));
                }

                // Generate a unique file name using timestamp
                $fileName = now()->timestamp . '.' . $request->file('image')->getClientOriginalExtension();

                // Store the file in the public disk under a specific folder
                $imagePath = $request->file('image')->storeAs(
                    'images/items/' . $item->id,
                    $fileName,
                    'public'
                );

                // Update the item with the correct image path
                $validatedData['image'] = 'storage/' . $imagePath;
            }

            // Update the item with the validated data
            $item->update($validatedData);

            DB::commit(); // Commit the transaction

            return response()->json(['success' => 'Item updated successfully.']);
        } catch (\Exception $e) {
            DB::rollBack(); // Roll back the transaction on error
            return response()->json(['error' => 'Failed to update item: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified item from storage (Soft Delete).
     */
    public function destroy($id)
    {

        try {
            $item = Item::findOrFail($id);
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
