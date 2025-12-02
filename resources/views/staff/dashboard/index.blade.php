@extends('staff.master')
@section('title','Dashboard')

@section('main')
    <div class="row">
        <div class="col">
            <div class="h-100">
                <div class="row mb-3 pb-1">
                    <div class="col-12">
                        <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                            <div class="flex-grow-1">
                                <h4 class="fs-16 mb-1">Good Morning, {{Auth::guard('staff')->user()->name}}!</h4>
                                <p class="text-muted mb-0">
                                    Welcome to your staff dashboard. Manage sellers efficiently.
                                </p>
                            </div>
                        </div>
                        <!-- end card header -->
                    </div>
                    <!--end col-->
                </div>
                <!--end row-->

                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <!-- card -->
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                            Total Sellers
                                        </p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end justify-content-between mt-4">
                                    <div>
                                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                            <span class="counter-value" data-target="{{ $sellerCount }}">0</span>
                                        </h4>
                                        <a href="{{ route('staff.sellers.index') }}" class="text-decoration-underline">View All Sellers</a>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-success-subtle rounded fs-3">
                                            <i class="bx bxs-store text-success"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col -->

                </div>
                <!-- end row-->

            </div>
            <!-- end .h-100-->
        </div>
        <!-- end col -->

    </div>
@endsection
@section('script')
    <script !src="">
        $(document).ready(function (){
            sendToast("Welcome to Staff Dashboard!",'primary');
        });
    </script>
@endsection
