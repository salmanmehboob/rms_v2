<?php
namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;



class TableController extends Controller
{
    /**
     * Display the table list.
     */
    public function index(Request $request)
    {
        $title = 'Table Management';

        if ($request->ajax()) {
            $tables = Table::select(['id', 'table', 'person', 'status', 'qr_code']);

            return DataTables::of($tables)
                ->addColumn('status', function ($table) {
                    return $table->status
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('qr_code', function ($table) {
                    if ($table->qr_code) {
                        return '<a href="' . asset($table->qr_code) . '" download class="btn btn-success btn-sm">Download</a>';
                    }
                    return '<button class="btn btn-primary btn-sm generateQr" data-id="' . $table->id . '">Generate</button>';
                })
                ->addColumn('actions', function ($table) {
                    return '
                    <div class="d-flex">
                        <a id="editBtn" data-url="' . route('tables.update', $table->id) . '"
                           data-id="' . $table->id . '"
                           data-table="' . $table->table . '"
                           data-person="' . $table->person . '"
                           data-status="' . $table->status . '"
                           href="javascript:void(0)"
                           class="btn btn-primary shadow btn-xs sharp me-1"><i class="fas fa-pencil-alt"></i></a>

                        <a href="javascript:void(0)"
                           data-url="' . route('tables.destroy', $table->id) . '"
                           data-id="' . $table->id . '"
                           class="btn btn-danger shadow btn-xs sharp delete-record"
                           title="Delete"><i class="fa fa-trash"></i></a>
                    </div>
                    ';
                })
                ->rawColumns(['status', 'actions', 'qr_code']) // Ensure status & actions are rendered as HTML
                ->make(true);
        }

        return view('tables.index', compact('title'));
    }

    /**
     * Store a new table.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'table' => 'required|integer|unique:tables,table',
            'person' => 'required|integer',
            'status' => 'boolean',
        ]);

        try {
            Table::create($validatedData);

            return response()->json(['success' => 'Table added successfully.'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to add table.'], 500);
        }
    }

    /**
     * Update an existing table.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'table' => 'required|integer|unique:tables,table,' . $id,
            'person' => 'required|integer',
            'status' => 'boolean',
        ]);

        try {
            $table = Table::findOrFail($id);
            $table->update($validatedData);

            return response()->json(['success' => 'Table updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update table.'], 500);
        }
    }

    /**
     * Delete a table.
     */
    public function destroy($id)
    {
        try {
            Table::findOrFail($id)->delete();

            return response()->json(['success' => 'Table deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete table.'], 500);
        }
    }

    /**
     * Generate QR Code for a table.
     */
    public function generateQr($id)
    {
        try {
            // Find the table
            $table = Table::findOrFail($id);

            // Define QR code path
            $qrCodePath = 'qrcodes/table_' . $table->id . '.png';

            // Generate and save the QR code
           $qrCodeData = QrCode::format('png')->size(200)->generate($table->id);
            Storage::disk('public')->put($qrCodePath, $qrCodeData);

            // Save QR code path to the database
            $table->update(['qr_code' => 'storage/' . $qrCodePath]);

            // Return success response
            return response()->json([
                'success' => true,
                'qr_code' => asset('storage/' . $qrCodePath)
            ]);
        } catch (\Exception $e) {
            \Log::error('QR Code Generation Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}