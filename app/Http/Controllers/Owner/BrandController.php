<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Enums\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('owner.brands.index');
    }

    /**
     * Get AJAX data for DataTables
     */
    public function ajaxData(Request $request)
    {
        if ($request->ajax()) {
            $data = Brand::select('brands.*');
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('logo', function ($row) {
                    if ($row->getRawOriginal('logo')) {
                        return '<img src="' . asset('uploads/brands/' . $row->getRawOriginal('logo')) . '" alt="' . $row->name . '" class="rounded" style="width: 50px; height: 50px; object-fit: contain;">';
                    }
                    return '<img src="' . asset('assets/img/no_img.jpg') . '" alt="No Logo" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">';
                })
                ->addColumn('products_count', function ($row) {
                    return $row->products()->count();
                })
                ->addColumn('status', function ($row) {
                    $checked = $row->status == Status::Active ? 'checked' : '';
                    return '<div class="form-check form-switch">
                                <input class="form-check-input status-toggle" type="checkbox" role="switch" 
                                    data-id="' . $row->id . '" ' . $checked . '>
                            </div>';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('owner.brands.edit', $row->id);
                    
                    return '<div class="btn-group" role="group">
                                <a href="' . $editUrl . '" class="btn btn-sm btn-primary">
                                    <i class="ri-edit-line"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>';
                })
                ->rawColumns(['logo', 'status', 'action'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('owner.brands.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'nullable|string',
            'website' => 'nullable|url',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = time() . '_' . uniqid() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('uploads/brands'), $logoName);
            $data['logo'] = $logoName;
        }

        Brand::create($data);

        return redirect()->route('owner.brands.index')
            ->with('success', 'Brand created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        return view('owner.brands.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'nullable|string',
            'website' => 'nullable|url',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($brand->getRawOriginal('logo') && file_exists(public_path('uploads/brands/' . $brand->getRawOriginal('logo')))) {
                unlink(public_path('uploads/brands/' . $brand->getRawOriginal('logo')));
            }

            $logo = $request->file('logo');
            $logoName = time() . '_' . uniqid() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('uploads/brands'), $logoName);
            $data['logo'] = $logoName;
        }

        $brand->update($data);

        return redirect()->route('owner.brands.index')
            ->with('success', 'Brand updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        // Check if brand has products
        if ($brand->products()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete brand with associated products.'
            ], 400);
        }

        // Delete logo
        if ($brand->getRawOriginal('logo') && file_exists(public_path('uploads/brands/' . $brand->getRawOriginal('logo')))) {
            unlink(public_path('uploads/brands/' . $brand->getRawOriginal('logo')));
        }

        $brand->delete();

        return response()->json([
            'success' => true,
            'message' => 'Brand deleted successfully.'
        ]);
    }

    /**
     * Toggle status
     */
    public function status(Brand $brand)
    {
        $brand->status = $brand->status == Status::Active ? Status::Inactive : Status::Active;
        $brand->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.'
        ]);
    }
}
