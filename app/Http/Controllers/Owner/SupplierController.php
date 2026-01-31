<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Enums\Status;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Traits\ResponseTrait;
use Exception;

class SupplierController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        return view('owner.master_data.suppliers.index');
    }

    public function ajaxData()
    {
        $result = Supplier::query();
        
        return DataTables::eloquent($result)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $editUrl = route('owner.master.suppliers.edit', $row->id);
                $deleteUrl = route('owner.master.suppliers.destroy', $row->id);
                
                $btn = '<a href="'.$editUrl.'" class="btn btn-sm btn-info me-1"><i class="bx bx-edit"></i> Edit</a>';
                $btn .= '<button type="button" class="btn btn-sm btn-danger delete-item" data-url="'.$deleteUrl.'"><i class="bx bx-trash"></i> Delete</button>';
                return $btn;
            })
            ->editColumn('status', function($row){
                $checked = $row->status == Status::Active ? 'checked' : '';
                return '<div class="form-check form-switch">
                            <input class="form-check-input status-toggle" type="checkbox" role="switch" 
                                data-id="' . $row->id . '" ' . $checked . ' data-url="'.route('owner.master.suppliers.status', $row->id).'">
                        </div>';
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function create()
    {
        return view('owner.master_data.suppliers.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:suppliers,name',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ], [
            'name.unique' => 'This Supplier name already exists in our records.',
        ]);

        try {
            Supplier::create($request->all());
            return $this->sendSuccess('Supplier created successfully.');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function edit(Supplier $supplier)
    {
        return view('owner.master_data.suppliers.form', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:suppliers,name,' . $supplier->id,
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ], [
            'name.unique' => 'This Supplier name already exists in our records.',
        ]);

        try {
            $supplier->update($request->all());
            return $this->sendSuccess('Supplier updated successfully.');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return $this->sendSuccess('Supplier deleted successfully.');
    }

    public function status(Request $request, Supplier $supplier)
    {
        $supplier->update(['status' => $request->status == 'true' ? Status::Active : Status::Inactive]);
        return $this->sendSuccess('Status updated successfully.');
    }
}
