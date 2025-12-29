<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Size;
use App\Enums\Status;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Traits\ResponseTrait;
use Exception;

class SizeController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        return view('owner.master_data.sizes.index');
    }

    public function ajaxData()
    {
        $result = Size::query();
        
        return DataTables::eloquent($result)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $editUrl = route('owner.master.sizes.edit', $row->id);
                $deleteUrl = route('owner.master.sizes.destroy', $row->id);
                
                $btn = '<a href="'.$editUrl.'" class="btn btn-sm btn-info me-1"><i class="bx bx-edit"></i> Edit</a>';
                $btn .= '<button type="button" class="btn btn-sm btn-danger delete-item" data-url="'.$deleteUrl.'"><i class="bx bx-trash"></i> Delete</button>';
                return $btn;
            })
            ->editColumn('status', function($row){
                $checked = $row->status == Status::Active ? 'checked' : '';
                return '<div class="form-check form-switch">
                            <input class="form-check-input status-toggle" type="checkbox" role="switch" 
                                data-id="' . $row->id . '" ' . $checked . ' data-url="'.route('owner.master.sizes.status', $row->id).'">
                        </div>';
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function create()
    {
        return view('owner.master_data.sizes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            Size::create($request->all());
            return $this->sendResponse('Size created successfully.');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function edit(Size $size)
    {
        return view('owner.master_data.sizes.edit', compact('size'));
    }

    public function update(Request $request, Size $size)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $size->update($request->all());
            return $this->sendResponse('Size updated successfully.');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function destroy(Size $size)
    {
        $size->delete();
        return $this->sendSuccess('Size deleted successfully.');
    }

    public function status(Request $request, Size $size)
    {
        $size->update(['status' => $request->status == 'true' ? Status::Active : Status::Inactive]);
        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}
