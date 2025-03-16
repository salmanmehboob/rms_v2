<?php

namespace App\Http\Controllers;

use App\Models\ItemCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log; // ✅ Import this!

class ItemCategoryController extends Controller
{
    /**
     * Display all item categories.
     */
    public function index(Request $request)
    {
        $title = 'Item Category';

        if ($request->ajax()) {
            $itemCategories = ItemCategory::select(['id', 'name']);
            return DataTables()->of($itemCategories)
                ->addColumn('actions', function ($category) {
                    return '
                    <div class="d-flex">
                        <a id="editBtn" data-url="' . route('item.categories.update', $category->id) . '"
                           data-id="' . $category->id . '" data-name="' . $category->name . '" href="javascript:void(0)"
                           class="btn btn-primary shadow btn-xs sharp me-1"><i class="fas fa-pencil-alt"></i></a>

                        <a href="javascript:void(0)"
                           data-url="' . route('item.categories.destroy', $category->id) . '"
                           data-label="delete"
                           data-id="' . $category->id . '"
                           data-table="itemCategoriesTable"
                           class="btn btn-danger shadow btn-xs sharp delete-record"
                           title="Delete Record"><i class="fa fa-trash"></i></a>
                    </div>
                ';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('item_categories.index', compact('title'));
    }

    /**
     * Store a new item category.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:item_categories,name',
            ]);

            if ($validator->fails()) {
                if ($request->ajax()) {
                    return response()->json(['errors' => $validator->errors()], 422);
                }
                return redirect()->back()->withErrors($validator)->withInput();
            }

            DB::beginTransaction();

            ItemCategory::create([
                'name' => $request->name,
                'status' => $request->status ?? 0,
            ]);

            DB::commit();

            if ($request->ajax()) {
                return response()->json(['success' => 'Category added successfully.']);
            }

            return redirect()->route('item.categories.index')->with('success', 'Category added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            // ✅ Log the error correctly
            Log::error('Category Store Error: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json(['error' => 'Something went wrong. Please try again.'], 500);
            }

            return redirect()->back()->withErrors(['error' => 'Something went wrong. Please try again.'])->withInput();
        }
    }

    /**
     * Update an item category.
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:item_categories,name,' . $id,
            ]);

            if ($validator->fails()) {
                if ($request->ajax()) {
                    return response()->json(['errors' => $validator->errors()], 422);
                }
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $category = ItemCategory::findOrFail($id);
            $category->update(['name' => $request->name]);

            if ($request->ajax()) {
                return response()->json(['success' => 'Category updated successfully.']);
            }

            return redirect()->route('item.categories.index')->with('success', 'Category updated successfully.');
        } catch (\Exception $e) {
            // ✅ Log the error correctly
            Log::error('Category Update Error: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json(['error' => 'Failed to update category. Please try again.'], 500);
            }

            return redirect()->back()->withErrors(['error' => 'Failed to update category.']);
        }
    }

    /**
     * Delete an item category.
     */
    public function destroy($id)
    {
        try {
            $itemCategory = ItemCategory::findOrFail($id);
            $itemCategory->delete();

            return response()->json(['success' => 'Category deleted successfully.']);
        } catch (\Exception $e) {
            // ✅ Log the error correctly
            Log::error('Category Delete Error: ' . $e->getMessage());

            return response()->json(['error' => 'Failed to delete category.']);
        }
    }
}