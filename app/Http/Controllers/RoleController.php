<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    /**
     * Display the table list.
     */
    public function index(Request $request)
    {
        $title = 'Role Management';

        if ($request->ajax()) {
            $roles = Role::select(['id', 'name', 'status']);

            return DataTables::of($roles)
                ->addColumn('status', function ($roles) {
                    return $roles->status
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('actions', function ($role) {
                    return '
                    <div class="d-flex">
                        <a id="editBtn" data-url="' . route('tables.update', $role->id) . '"
                           data-id="' . $role->id . '"
                           data-name="' . $role->name . '"
                           data-status="' . $role->status . '"
                           href="javascript:void(0)"
                           class="btn btn-primary shadow btn-xs sharp me-1"><i class="fas fa-pencil-alt"></i></a>

                        <a href="javascript:void(0)"
                           data-url="' . route('tables.destroy', $role->id) . '"
                           data-id="' . $role->id . '"
                           class="btn btn-danger shadow btn-xs sharp delete-record"
                           title="Delete"><i class="fa fa-trash"></i></a>
                    </div>
                    ';
                })
                ->rawColumns(['status', 'actions']) // Ensure status & actions are rendered as HTML
                ->make(true);
        }

        return view('roles.index', compact('title'));
    }

    /**
     * Store a new table.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'status' => 'boolean',
        ]);

        try {
            Role::create($validatedData);

            return response()->json(['success' => 'Role added successfully.'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to add Role.'], 500);
        }
    }

    /**
     * Update an existing table.
     */
    public function update(Request $request,  $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|unique:roles,name,' . $id,
            'status' => 'boolean',
        ]);

        try {
            $role = Role::findOrFail($id);
            $role->update($validatedData);

            return response()->json(['success' => 'Role updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update Role.'], 500);
        }
    }

    /**
     * Delete a table.
     */
    public function destroy($id)
    {
        try {
            Role::findOrFail($id)->delete();

            return response()->json(['success' => 'Role deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete Role.'], 500);
        }
    }
}