<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Enums\Status;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Traits\ResponseTrait;
use Exception;

class UnitController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        return view('owner.master_data.units.index');
    }

    public function ajaxData()
    {
        $result = Unit::query();
        
        return DataTables::eloquent($result)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $editUrl = route('owner.master.units.edit', $row->id);
                $deleteUrl = route('owner.master.units.destroy', $row->id);
                
                $btn = '<a href="'.$editUrl.'" class="btn btn-sm btn-info me-1"><i class="bx bx-edit"></i> Edit</a>';
                $btn .= '<button type="button" class="btn btn-sm btn-danger delete-item" data-url="'.$deleteUrl.'"><i class="bx bx-trash"></i> Delete</button>';
                return $btn;
            })
            ->editColumn('status', function($row){
                $checked = $row->status == Status::Active ? 'checked' : '';
                return '<div class="form-check form-switch">
                            <input class="form-check-input status-toggle" type="checkbox" role="switch" 
                                data-id="' . $row->id . '" ' . $checked . ' data-url="'.route('owner.master.units.status', $row->id).'">
                        </div>';
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function create()
    {
        return view('owner.master_data.units.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:10',
            'category' => 'required|in:product,service',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            Unit::create($request->all());
            return $this->sendSuccess('Unit created successfully.');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function edit(Unit $unit)
    {
        return view('owner.master_data.units.edit', compact('unit'));
    }

    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:10',
            'category' => 'required|in:product,service',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $unit->update($request->all());
            return $this->sendSuccess('Unit updated successfully.');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();
        return $this->sendSuccess('Unit deleted successfully.');
    }

    public function status(Request $request, Unit $unit)
    {
        $unit->update(['status' => $request->status == 'true' ? Status::Active : Status::Inactive]);
        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}
