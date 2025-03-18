<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    //
    /**
     * Display the table list.
     */
    public function index(Request $request)
    {
        $title = 'Customer Management';

        if ($request->ajax()) {
            $customers = Customer::select(['id', 'name', 'address','phone']);

            return DataTables::of($customers)
                ->addColumn('actions', function ($customer) {
                    return '
                    <div class="d-flex">
                        <a id="editBtn" data-url="' . route('customers.update', $customer->id) . '"
                           data-id="' . $customer->id . '"
                           data-name="' . $customer->name . '"
                           data-address="' . $customer->address . '"
                           data-phone="' . $customer->phone . '"
                           href="javascript:void(0)"
                           class="btn btn-primary shadow btn-xs sharp me-1"><i class="fas fa-pencil-alt"></i></a>

                        <a href="javascript:void(0)"
                           data-url="' . route('customers.destroy', $customer->id) . '"
                           data-id="' . $customer->id . '"
                           data-table="customersTable"
                           class="btn btn-danger shadow btn-xs sharp delete-record"
                           title="Delete"><i class="fa fa-trash"></i></a>
                    </div>
                    ';
                })
                ->rawColumns([ 'actions']) // Ensure status & actions are rendered as HTML
                ->make(true);
        }

        return view('customers.index', compact('title'));
    }

    /**
     * Store a new table.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required|digits_between:10,15',
        ]);

        try {
            Customer::create($validatedData);

            return response()->json(['success' => 'customer added successfully.'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to add customer.'], 500);
        }
    }

    /**
     * Update an existing table.
     */
    public function update(Request $request,  $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required|digits_between:10,15',
        ]);

        try {
            $customer = Customer::findOrFail($id);
            $customer->update($validatedData);

            return response()->json(['success' => 'customer updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update customer.'], 500);
        }
    }

    /**
     * Delete a table.
     */
    public function destroy($id)
    {
        try {
            Customer::findOrFail($id)->delete();

            return response()->json(['success' => 'customer deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete customer.'], 500);
        }
    }
}