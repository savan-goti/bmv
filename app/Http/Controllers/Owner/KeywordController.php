<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTrait;
use App\Models\Keyword;
use App\Enums\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class KeywordController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('owner.keywords.index');
    }

    /**
     * Get AJAX data for DataTables
     */
    public function ajaxData(Request $request)
    {
        if ($request->ajax()) {
            $data = Keyword::select('keywords.*');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('type', function ($row) {
                    $badgeClass = $row->type == 'product' ? 'bg-primary' : 'bg-info';
                    return '<span class="badge ' . $badgeClass . '">' . ucfirst($row->type) . '</span>';
                })
                ->addColumn('status', function ($row) {
                    $checked = $row->status == Status::Active ? 'checked' : '';
                    return '<div class="form-check form-switch">
                                <input class="form-check-input status-toggle" type="checkbox" role="switch" 
                                    data-id="' . $row->id . '" ' . $checked . '>
                            </div>';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('owner.master.keywords.edit', $row->id);

                    return '<div class="btn-group" role="group">
                                <a href="' . $editUrl . '" class="btn btn-sm btn-primary">
                                    <i class="ri-edit-line"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>';
                })
                ->rawColumns(['type', 'status', 'action'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('owner.keywords.form');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Keyword $keyword)
    {
        return view('owner.keywords.form', compact('keyword'));
    }

    /**
     * Save keyword (create or update) via AJAX
     */
    public function save(Request $request, $id = null)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:keywords,name',
                'description' => 'nullable|string',
                'type' => 'required|in:product,service',
                'status' => 'required|in:active,inactive',
            ], [
                'name.unique' => 'This keyword already exists in our records.',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $data = $request->all();
            $data['slug'] = slug($request->name);

            if ($id) {
                // Update existing keyword
                $keyword = Keyword::findOrFail($id);
                $keyword->update($data);
                $message = 'Keyword updated successfully.';
            } else {
                // Create new keyword
                $keyword = Keyword::create($data);
                $message = 'Keyword created successfully.';
            }

            DB::commit();
            return $this->sendSuccess($message);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Keyword $keyword)
    {
        $keyword->delete();

        return $this->sendSuccess('Keyword deleted successfully.');
    }

    /**
     * Toggle status
     */
    public function status(Keyword $keyword)
    {
        $keyword->status = $keyword->status == Status::Active ? Status::Inactive : Status::Active;
        $keyword->save();

        return $this->sendSuccess('Status updated successfully.');
    }
}
