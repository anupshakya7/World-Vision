@extends('admin.dashboard.layout.web')
@section('title') {{ "Dashboard" }} @endsection

@section('content')
<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">

                <!--content goes here-->
                <div class="col">
                    <div class="h-100">
                        <div class="row mb-3 pb-1">
                            <div class="col-12">
                                <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                                    <div class="flex-grow-1">
                                        <h4 class="fs-16 mb-1">Hello, {{\Auth::user()->name}}!</h4>
                                        <p class="text-muted mb-0"></p>
                                    </div>
                                    <div class="mt-3 mt-lg-0">
                                        <div class="row g-3 mb-0 align-items-center">
                                            {{--<div class="col-sm-auto">
                                                <a href="{{route('admin.products.create')}}"
                                                    class="btn btn-soft-success"><i
                                                        class="ri-add-circle-line align-middle me-1"></i> Add
                                                    Product</a>
                                            </div>
                                            <div class="col-auto">
                                                <button type="button"
                                                    class="btn btn-soft-info btn-icon waves-effect waves-light layout-rightside-btn"><i
                                                        class="ri-pulse-line"></i></button>
                                            </div>--}}
                                        </div>
                                        <!--end row-->
                                    </div>
                                </div><!-- end card header -->
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->

                        <div class="row">



                        </div> <!-- end row-->

                        <div class="row">

                        </div> <!-- end row-->
                    </div> <!-- end .h-100-->
                </div> <!-- end col -->
            </div>
        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
    @endsection
    @section('scripts')
    @parent
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>

    @endsection