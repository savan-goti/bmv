<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Product;
use App\Enums\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('owner.collections.index');
    }

    /**
     * Get AJAX data for DataTables
     */
    public function ajaxData(Request $request)
    {
        if ($request->ajax()) {
            $data = Collection::select('collections.*');
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    if ($row->getRawOriginal('image')) {
                        return '<img src="' . asset('uploads/collections/' . $row->getRawOriginal('image')) . '" alt="' . $row->name . '" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">';
                    }
                    return '<img src="' . asset('assets/img/no_img.jpg') . '" alt="No Image" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">';
                })
                ->addColumn('products_count', function ($row) {
                    return $row->products()->count();
                })
                ->addColumn('dates', function ($row) {
                    if ($row->start_date && $row->end_date) {
                        return $row->start_date->format('M d, Y') . ' - ' . $row->end_date->format('M d, Y');
                    }
                    return '-';
                })
                ->addColumn('featured', function ($row) {
                    return $row->is_featured ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>';
                })
                ->addColumn('status', function ($row) {
                    $checked = $row->status == Status::Active ? 'checked' : '';
                    return '<div class="form-check form-switch">
                                <input class="form-check-input status-toggle" type="checkbox" role="switch" 
                                    data-id="' . $row->id . '" ' . $checked . '>
                            </div>';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('owner.collections.edit', $row->id);
                    
                    return '<div class="btn-group" role="group">
                                <a href="' . $editUrl . '" class="btn btn-sm btn-primary">
                                    <i class="ri-edit-line"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>';
                })
                ->rawColumns(['image', 'featured', 'status', 'action'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::where('status', Status::Active)->get();
        return view('owner.collections.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_featured' => 'nullable|boolean',
            'status' => 'required|in:active,inactive',
            'products' => 'nullable|array',
            'products.*' => 'exists:products,id',
        ]);

        $data = $request->except('products');
        $data['slug'] = Str::slug($request->name);
        $data['is_featured'] = $request->has('is_featured') ? true : false;

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/collections'), $imageName);
            $data['image'] = $imageName;
        }

        $collection = Collection::create($data);

        // Attach products
        if ($request->has('products')) {
            $collection->products()->attach($request->products);
        }

        return redirect()->route('owner.collections.index')
            ->with('success', 'Collection created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Collection $collection)
    {
        $products = Product::where('status', Status::Active)->get();
        $selectedProducts = $collection->products->pluck('id')->toArray();
        
        return view('owner.collections.edit', compact('collection', 'products', 'selectedProducts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Collection $collection)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_featured' => 'nullable|boolean',
            'status' => 'required|in:active,inactive',
            'products' => 'nullable|array',
            'products.*' => 'exists:products,id',
        ]);

        $data = $request->except('products');
        $data['slug'] = Str::slug($request->name);
        $data['is_featured'] = $request->has('is_featured') ? true : false;

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($collection->getRawOriginal('image') && file_exists(public_path('uploads/collections/' . $collection->getRawOriginal('image')))) {
                unlink(public_path('uploads/collections/' . $collection->getRawOriginal('image')));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/collections'), $imageName);
            $data['image'] = $imageName;
        }

        $collection->update($data);

        // Sync products
        if ($request->has('products')) {
            $collection->products()->sync($request->products);
        } else {
            $collection->products()->detach();
        }

        return redirect()->route('owner.collections.index')
            ->with('success', 'Collection updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Collection $collection)
    {
        // Detach all products
        $collection->products()->detach();

        // Delete image
        if ($collection->getRawOriginal('image') && file_exists(public_path('uploads/collections/' . $collection->getRawOriginal('image')))) {
            unlink(public_path('uploads/collections/' . $collection->getRawOriginal('image')));
        }

        $collection->delete();

        return response()->json([
            'success' => true,
            'message' => 'Collection deleted successfully.'
        ]);
    }

    /**
     * Toggle status
     */
    public function status(Collection $collection)
    {
        $collection->status = $collection->status == Status::Active ? Status::Inactive : Status::Active;
        $collection->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.'
        ]);
    }
}
