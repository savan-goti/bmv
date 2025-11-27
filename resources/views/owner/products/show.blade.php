@extends('owner.master')

@section('title','Product Details')

@section('main')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Product Details</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('owner.products.index') }}">Products</a></li>
                        <li class="breadcrumb-item active">Details</li>
                    </ol>
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
                                            @if ($product->image)
                                                <a class="nav-link active" id="product-1-tab" data-bs-toggle="pill"
                                                    href="#product-1" role="tab" aria-controls="product-1"
                                                    aria-selected="true">
                                                    <img src="{{ asset('uploads/products/' . $product->image) }}" alt=""
                                                        class="img-fluid mx-auto d-block rounded">
                                                </a>
                                            @endif
                                            @foreach ($product->productImages as $key => $image)
                                                <a class="nav-link {{ !$product->image && $key == 0 ? 'active' : '' }}"
                                                    id="product-{{ $key + 2 }}-tab" data-bs-toggle="pill"
                                                    href="#product-{{ $key + 2 }}" role="tab"
                                                    aria-controls="product-{{ $key + 2 }}" aria-selected="false">
                                                    <img src="{{ asset('uploads/products/gallery/' . $image->image) }}"
                                                        alt="" class="img-fluid mx-auto d-block rounded">
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="col-md-7 offset-md-1 col-sm-9 col-8">
                                        <div class="tab-content" id="v-pills-tabContent">
                                            @if ($product->image)
                                                <div class="tab-pane fade show active" id="product-1" role="tabpanel"
                                                    aria-labelledby="product-1-tab">
                                                    <div>
                                                        <img src="{{ asset('uploads/products/' . $product->image) }}"
                                                            alt="" class="img-fluid mx-auto d-block">
                                                    </div>
                                                </div>
                                            @endif
                                            @foreach ($product->productImages as $key => $image)
                                                <div class="tab-pane fade {{ !$product->image && $key == 0 ? 'show active' : '' }}"
                                                    id="product-{{ $key + 2 }}" role="tabpanel"
                                                    aria-labelledby="product-{{ $key + 2 }}-tab">
                                                    <div>
                                                        <img src="{{ asset('uploads/products/gallery/' . $image->image) }}"
                                                            alt="" class="img-fluid mx-auto d-block">
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
                                <a href="javascript: void(0);" class="text-primary">{{ $product->category->name }}
                                    @if ($product->subCategory)
                                        > {{ $product->subCategory->name }}
                                    @endif
                                </a>
                                <h4 class="mt-1 mb-3">{{ $product->name }}</h4>

                                <p class="text-muted float-start me-3">
                                    <span class="bx bxs-star text-warning"></span>
                                    <span class="bx bxs-star text-warning"></span>
                                    <span class="bx bxs-star text-warning"></span>
                                    <span class="bx bxs-star text-warning"></span>
                                    <span class="bx bxs-star"></span>
                                </p>
                                <p class="text-muted mb-4">( 0 Customers Review )</p>

                                @if ($product->discount > 0)
                                    <h6 class="text-success text-uppercase">{{ $product->discount }} % Off</h6>
                                @endif
                                <h5 class="mb-4">Price : <span
                                        class="text-muted me-2"><del>{{ currency($product->price + ($product->price * $product->discount) / 100) }}</del></span>
                                    <b>{{ currency($product->price) }}</b>
                                </h5>
                                <p class="text-muted mb-4">{{ $product->productInformation->short_description ?? '' }}</p>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div>
                                            <p class="text-muted"><i
                                                    class="bx bx-caret-right font-size-16 align-middle text-primary me-1"></i>
                                                Status: {{ $product->status->label() }}</p>
                                            <p class="text-muted"><i
                                                    class="bx bx-caret-right font-size-16 align-middle text-primary me-1"></i>
                                                Quantity: {{ $product->quantity }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div>
                                            <p class="text-muted"><i
                                                    class="bx bx-caret-right font-size-16 align-middle text-primary me-1"></i>
                                                Brand:
                                                {{ $product->productInformation->manufacturer_brand ?? 'N/A' }}</p>
                                            <p class="text-muted"><i
                                                    class="bx bx-caret-right font-size-16 align-middle text-primary me-1"></i>
                                                Published At:
                                                {{ $product->published_at ? \Carbon\Carbon::parse($product->published_at)->format('d M, Y') : 'N/A' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->

                    <div class="mt-5">
                        <h5 class="mb-3">Specifications</h5>
                        <div class="table-responsive">
                            <table class="table mb-0 table-bordered">
                                <tbody>
                                    <tr>
                                        <th scope="row" style="width: 400px;">Manufacturer Name</th>
                                        <td>{{ $product->productInformation->manufacturer_name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Manufacturer Part Number</th>
                                        <td>{{ $product->productInformation->manufacturer_part_number ?? 'N/A' }}</td>
                                    </tr>
                                    @if (isset($product->productInformation->specifications) && is_array($product->productInformation->specifications))
                                        @foreach ($product->productInformation->specifications as $key => $value)
                                            <tr>
                                                <th scope="row">{{ ucfirst($key) }}</th>
                                                <td>{{ $value }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- end Specifications -->

                    <div class="mt-5">
                        <h5 class="mb-3">Long Description</h5>
                        <div class="text-muted">
                            {!! $product->productInformation->long_description ?? 'N/A' !!}
                        </div>
                    </div>

                    <div class="mt-5">
                        <h5 class="mb-3">SEO Meta Data</h5>
                        <div class="table-responsive">
                            <table class="table mb-0 table-bordered">
                                <tbody>
                                    <tr>
                                        <th scope="row" style="width: 400px;">Meta Title</th>
                                        <td>{{ $product->productInformation->meta_title ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Meta Description</th>
                                        <td>{{ $product->productInformation->meta_description ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Meta Keywords</th>
                                        <td>{{ $product->productInformation->meta_keywords ?? 'N/A' }}</td>
                                    </tr>
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
