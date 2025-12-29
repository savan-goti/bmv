<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\HsnSac;
use App\Enums\Status;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Traits\ResponseTrait;
use Exception;

class HsnSacController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        return view('owner.master_data.hsn_sacs.index');
    }

    public function ajaxData()
    {
        $result = HsnSac::query();
        
        return DataTables::eloquent($result)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $editUrl = route('owner.master.hsn-sacs.edit', $row->id);
                $deleteUrl = route('owner.master.hsn-sacs.destroy', $row->id);
                
                $btn = '<a href="'.$editUrl.'" class="btn btn-sm btn-info me-1"><i class="bx bx-edit"></i> Edit</a>';
                $btn .= '<button type="button" class="btn btn-sm btn-danger delete-item" data-url="'.$deleteUrl.'"><i class="bx bx-trash"></i> Delete</button>';
                return $btn;
            })
            ->editColumn('status', function($row){
                $checked = $row->status == Status::Active ? 'checked' : '';
                return '<div class="form-check form-switch">
                            <input class="form-check-input status-toggle" type="checkbox" role="switch" 
                                data-id="' . $row->id . '" ' . $checked . ' data-url="'.route('owner.master.hsn-sacs.status', $row->id).'">
                        </div>';
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function create()
    {
        return view('owner.master_data.hsn_sacs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:20|unique:hsn_sacs,code',
            'description' => 'required|string',
            'type' => 'required|in:hsn,sac',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            HsnSac::create($request->all());
            return $this->sendResponse('HSN/SAC created successfully.');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function edit(HsnSac $hsnSac)
    {
        return view('owner.master_data.hsn_sacs.edit', compact('hsnSac'));
    }

    public function update(Request $request, HsnSac $hsnSac)
    {
        $request->validate([
            'code' => 'required|string|max:20|unique:hsn_sacs,code,'.$hsnSac->id,
            'description' => 'required|string',
            'type' => 'required|in:hsn,sac',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $hsnSac->update($request->all());
            return $this->sendResponse('HSN/SAC updated successfully.');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function destroy(HsnSac $hsnSac)
    {
        $hsnSac->delete();
        return $this->sendSuccess('HSN/SAC deleted successfully.');
    }

    public function status(Request $request, HsnSac $hsnSac)
    {
        $hsnSac->update(['status' => $request->status == 'true' ? Status::Active : Status::Inactive]);
        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}
