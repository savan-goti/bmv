@extends('owner.master')

@section('title','Product Details')

@section('main')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Product Details</h4>
                <div class="page-title-right d-flex align-items-center gap-2">
                    <a href="{{ route('owner.products.edit', $product->id) }}" class="btn btn-primary btn-sm">
                        <i class="bx bx-edit"></i> Edit Product
                    </a>
                    <a href="{{ route('owner.products.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bx bx-arrow-back"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="product-detai-imgs">
                                <div class="row">
                                    <div class="col-md-2 col-sm-3 col-4">
                                        <div class="nav flex-column nav-pills " id="v-pills-tab" role="tablist"
                                            aria-orientation="vertical">
                                            @if ($product->thumbnail_image)
                                                <a class="nav-link active" id="product-1-tab" data-bs-toggle="pill"
                                                    href="#product-1" role="tab" aria-controls="product-1"
                                                    aria-selected="true">
                                                    <img src="{{ asset('uploads/products/' . $product->thumbnail_image) }}" alt="{{ $product->image_alt_text ?? $product->product_name }}"
                                                        class="img-fluid mx-auto d-block rounded">
                                                </a>
                                            @endif
                                            @foreach ($product->productImages as $key => $image)
                                                <a class="nav-link {{ !$product->thumbnail_image && $key == 0 ? 'active' : '' }}"
                                                    id="product-{{ $key + 2 }}-tab" data-bs-toggle="pill"
                                                    href="#product-{{ $key + 2 }}" role="tab"
                                                    aria-controls="product-{{ $key + 2 }}" aria-selected="false">
                                                    <img src="{{ asset('uploads/products/gallery/' . $image->image) }}"
                                                        alt="{{ $image->alt_text ?? $product->product_name }}" class="img-fluid mx-auto d-block rounded">
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="col-md-7 offset-md-1 col-sm-9 col-8">
                                        <div class="tab-content" id="v-pills-tabContent">
                                            @if ($product->thumbnail_image)
                                                <div class="tab-pane fade show active" id="product-1" role="tabpanel"
                                                    aria-labelledby="product-1-tab">
                                                    <div>
                                                        <img src="{{ asset('uploads/products/' . $product->thumbnail_image) }}"
                                                            alt="{{ $product->image_alt_text ?? $product->product_name }}" class="img-fluid mx-auto d-block">
                                                    </div>
                                                </div>
                                            @endif
                                            @foreach ($product->productImages as $key => $image)
                                                <div class="tab-pane fade {{ !$product->thumbnail_image && $key == 0 ? 'show active' : '' }}"
                                                    id="product-{{ $key + 2 }}" role="tabpanel"
                                                    aria-labelledby="product-{{ $key + 2 }}-tab">
                                                    <div>
                                                        <img src="{{ asset('uploads/products/gallery/' . $image->image) }}"
                                                            alt="{{ $image->alt_text ?? $product->product_name }}" class="img-fluid mx-auto d-block">
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6">
                            <div class="mt-4 mt-xl-3">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <a href="javascript: void(0);" class="text-primary">{{ $product->category->name ?? 'N/A' }}
                                        @if ($product->subCategory)
                                            > {{ $product->subCategory->name }}
                                        @endif
                                        @if ($product->childCategory)
                                            > {{ $product->childCategory->name }}
                                        @endif
                                    </a>
                                    @if ($product->is_featured)
                                        <span class="badge bg-warning">Featured</span>
                                    @endif
                                </div>
                                <h4 class="mt-1 mb-3">{{ $product->product_name }}</h4>

                                @php
                                    $avgRating = $product->productReviews->avg('rating') ?? 0;
                                    $reviewCount = $product->productReviews->count();
                                @endphp
                                <p class="text-muted float-start me-3">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span class="bx bxs-star {{ $i <= $avgRating ? 'text-warning' : '' }}"></span>
                                    @endfor
                                </p>
                                <p class="text-muted mb-4">( {{ $reviewCount }} Customer{{ $reviewCount != 1 ? 's' : '' }} Review )</p>

                                @if ($product->discount_value > 0)
                                    <h6 class="text-success text-uppercase">
                                        {{ $product->discount_type == 'percentage' ? $product->discount_value . ' %' : currency($product->discount_value) }} Off
                                    </h6>
                                @endif
                                <h5 class="mb-4">Price : 
                                    @if ($product->discount_value > 0)
                                        <span class="text-muted me-2"><del>{{ currency($product->sell_price) }}</del></span>
                                        <b>{{ currency($product->getFinalPrice()) }}</b>
                                    @else
                                        <b>{{ currency($product->sell_price) }}</b>
                                    @endif
                                </h5>
                                <p class="text-muted mb-4">{{ $product->short_description ?? '' }}</p>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div>
                                            <p class="text-muted"><i
                                                    class="bx bx-caret-right font-size-16 align-middle text-primary me-1"></i>
                                                Status: <span class="badge bg-{{ $product->is_active->value == 1 ? 'success' : 'danger' }}">{{ $product->is_active->label() }}</span></p>
                                            <p class="text-muted"><i
                                                    class="bx bx-caret-right font-size-16 align-middle text-primary me-1"></i>
                                                Stock: {{ $product->available_stock ?? 0 }} / {{ $product->total_stock ?? 0 }}</p>
                                            @if ($product->sku)
                                                <p class="text-muted"><i
                                                        class="bx bx-caret-right font-size-16 align-middle text-primary me-1"></i>
                                                    SKU: {{ $product->sku }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div>
                                            <p class="text-muted"><i
                                                    class="bx bx-caret-right font-size-16 align-middle text-primary me-1"></i>
                                                Brand:
                                                {{ $product->brand->name ?? 'N/A' }}</p>
                                            @if ($product->collection)
                                                <p class="text-muted"><i
                                                        class="bx bx-caret-right font-size-16 align-middle text-primary me-1"></i>
                                                    Collection: {{ $product->collection->name }}</p>
                                            @endif
                                            <p class="text-muted"><i
                                                    class="bx bx-caret-right font-size-16 align-middle text-primary me-1"></i>
                                                Type: <span class="badge bg-info">{{ ucfirst($product->product_type) }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->

                    <div class="mt-5">
                        <h5 class="mb-3">Product Information</h5>
                        <div class="table-responsive">
                            <table class="table mb-0 table-bordered">
                                <tbody>
                                    @if ($product->barcode)
                                        <tr>
                                            <th scope="row" style="width: 400px;">Barcode</th>
                                            <td>{{ $product->barcode }}</td>
                                        </tr>
                                    @endif
                                    @if ($product->weight || $product->length || $product->width || $product->height)
                                        <tr>
                                            <th scope="row">Dimensions (L x W x H)</th>
                                            <td>{{ $product->length ?? 'N/A' }} x {{ $product->width ?? 'N/A' }} x {{ $product->height ?? 'N/A' }} cm</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Weight</th>
                                            <td>{{ $product->weight ?? 'N/A' }} kg</td>
                                        </tr>
                                    @endif
                                    @if ($product->shipping_class)
                                        <tr>
                                            <th scope="row">Shipping Class</th>
                                            <td>{{ ucfirst($product->shipping_class) }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th scope="row">Free Shipping</th>
                                        <td><span class="badge bg-{{ $product->free_shipping ? 'success' : 'secondary' }}">{{ $product->free_shipping ? 'Yes' : 'No' }}</span></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">COD Available</th>
                                        <td><span class="badge bg-{{ $product->cod_available ? 'success' : 'secondary' }}">{{ $product->cod_available ? 'Yes' : 'No' }}</span></td>
                                    </tr>
                                    @if ($product->is_returnable)
                                        <tr>
                                            <th scope="row">Return Policy</th>
                                            <td>Returnable within {{ $product->return_days ?? 'N/A' }} days</td>
                                        </tr>
                                    @endif
                                    @if ($product->gst_rate)
                                        <tr>
                                            <th scope="row">GST Rate</th>
                                            <td>{{ $product->gst_rate }}%</td>
                                        </tr>
                                    @endif
                                    @if ($product->productInformation && $product->productInformation->manufacturer_name)
                                        <tr>
                                            <th scope="row">Manufacturer Name</th>
                                            <td>{{ $product->productInformation->manufacturer_name }}</td>
                                        </tr>
                                    @endif
                                    @if ($product->productInformation && $product->productInformation->manufacturer_part_number)
                                        <tr>
                                            <th scope="row">Manufacturer Part Number</th>
                                            <td>{{ $product->productInformation->manufacturer_part_number }}</td>
                                        </tr>
                                    @endif
                                    @if (isset($product->productInformation->specifications) && is_array($product->productInformation->specifications))
                                        @foreach ($product->productInformation->specifications as $key => $value)
                                            <tr>
                                                <th scope="row">{{ ucfirst(str_replace('_', ' ', $key)) }}</th>
                                                <td>{{ $value }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- end Product Information -->

                    @if ($product->full_description || ($product->productInformation && $product->productInformation->long_description))
                        <div class="mt-5">
                            <h5 class="mb-3">Full Description</h5>
                            <div class="text-muted">
                                {!! $product->full_description ?? $product->productInformation->long_description ?? 'No description available.' !!}
                            </div>
                        </div>
                    @endif

                    <div class="mt-5">
                        <h5 class="mb-3">SEO Meta Data</h5>
                        <div class="table-responsive">
                            <table class="table mb-0 table-bordered">
                                <tbody>
                                    <tr>
                                        <th scope="row" style="width: 400px;">Meta Title</th>
                                        <td>{{ $product->meta_title ?? ($product->productInformation->meta_title ?? 'N/A') }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Meta Description</th>
                                        <td>{{ $product->meta_description ?? ($product->productInformation->meta_description ?? 'N/A') }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Meta Keywords</th>
                                        <td>{{ $product->meta_keywords ?? ($product->productInformation->meta_keywords ?? 'N/A') }}</td>
                                    </tr>
                                    @if ($product->search_tags)
                                        <tr>
                                            <th scope="row">Search Tags</th>
                                            <td>{{ $product->search_tags }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
            <!-- end card -->
        </div>
    </div>
@endsection
