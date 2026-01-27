<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Color;
use App\Enums\Status;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Traits\ResponseTrait;
use Exception;

class ColorController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        return view('owner.master_data.colors.index');
    }

    public function ajaxData()
    {
        $result = Color::query();
        
        return DataTables::eloquent($result)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $editUrl = route('owner.master.colors.edit', $row->id);
                $deleteUrl = route('owner.master.colors.destroy', $row->id);
                
                $btn = '<a href="'.$editUrl.'" class="btn btn-sm btn-info me-1"><i class="bx bx-edit"></i> Edit</a>';
                $btn .= '<button type="button" class="btn btn-sm btn-danger delete-item" data-url="'.$deleteUrl.'"><i class="bx bx-trash"></i> Delete</button>';
                return $btn;
            })
            ->editColumn('status', function($row){
                $checked = $row->status == Status::Active ? 'checked' : '';
                return '<div class="form-check form-switch">
                            <input class="form-check-input status-toggle" type="checkbox" role="switch" 
                                data-id="' . $row->id . '" ' . $checked . ' data-url="'.route('owner.master.colors.status', $row->id).'">
                        </div>';
            })
            ->editColumn('color_code', function($row){
                return '<div class="d-flex align-items-center">
                            <div class="rounded-circle me-2" style="width: 20px; height: 20px; background-color: '.$row->color_code.'; border: 1px solid #ddd;"></div>
                            <span>'.$row->color_code.'</span>
                        </div>';
            })
            ->rawColumns(['action', 'status', 'color_code'])
            ->make(true);
    }

    public function create()
    {
        return view('owner.master_data.colors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:colors,name',
            'color_code' => 'required|string|max:20',
            'status' => 'required|in:active,inactive',
        ], [
            'name.unique' => 'This Color already exists in our records.',
        ]);

        try {
            Color::create($request->all());
            return $this->sendResponse('Color created successfully.');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function edit(Color $color)
    {
        return view('owner.master_data.colors.edit', compact('color'));
    }

    public function update(Request $request, Color $color)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'color_code' => 'required|string|max:20',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $color->update($request->all());
            return $this->sendResponse('Color updated successfully.');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function destroy(Color $color)
    {
        $color->delete();
        return $this->sendSuccess('Color deleted successfully.');
    }

    public function status(Request $request, Color $color)
    {
        $color->update(['status' => $request->status == 'true' ? Status::Active : Status::Inactive]);
        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}
