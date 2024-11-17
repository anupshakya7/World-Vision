@extends('admin.dashboard.layout.web')
@section('title','Country Create')
@section('content')
<style>
    .error {
        color: red;
    }

    .hidden {
        display: none;
    }

    .has-error .invalid-feedback {
        display: block;
        font-size: 16px;
    }

    .has-error .form-control {
        border: 1px solid red;
    }
</style>
<link href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/ui/trumbowyg.min.css" rel="stylesheet">
<!-- Import table plugin specific stylesheet -->
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/plugins/table/ui/trumbowyg.table.min.css">
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Country Region</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{route('admin.home')}}">World Vision</a></li>
                                <li class="breadcrumb-item active">{{ 'Create Country'}}</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header border-bottom-dashed">
                            <h5 class="card-title mb-0">{{ 'Add Country Region' }}</h5>
                        </div>

                        <div class="card-body">
                            @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            <form action="{{ route('admin.country.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="title">{{ 'Title' }} <span
                                                            style="color:red;">*</span></label>
                                                    <input type="text" name="title" class="form-control"
                                                        value="{{ old('title') }}" required>
                                                    @if($errors->has('title'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('title') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="title">{{ 'Geo Code' }} <span
                                                            style="color:red;"></span></label>
                                                    <input type="text" name="geo_code" class="form-control"
                                                        value="{{ old('geo_code') }}" placeholder="eg. AUS">
                                                    @if($errors->has('geo_code'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('geo_code') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-4">
                                        <div class="row">
                                            <div class="col-12">
                                                <label for="parent_id">Parent Region</label>
                                                <select class="form-control form-select" id="parent_id"
                                                    name="parent_id">
                                                    <option value="">None</option>

                                                    {{-- @foreach ($regions as $region)
                                                    <option value="{{ $region->id }}">{{ $region->title }}</option>
                                                    @if ($region->children->count())
                                                    @include('admin.country-regions.partials.subcategories-create',
                                                    ['regions' => $region->children, 'prefix' => '---'])
                                                    @endif
                                                    @endforeach --}}
                                                </select>
                                            </div>

                                            <div class="col-12" style="margin-top:30px;">
                                                <label for="latitude">Latitude</label>
                                                <input type="text" class="form-control" id="latitude" name="latitude"
                                                    value="{{ old('longitude') }}" required>
                                            </div>

                                            <div class="col-12" style="margin-top:30px;">
                                                <label for="longitude">Longitude</label>
                                                <input type="text" class="form-control" id="longitude" name="longitude"
                                                    value="{{ old('longitude') }}" required>
                                            </div>

                                            <div class="col-12" style="margin-top:30px;">
                                                <label for="bounding_box">Bounding Box (JSON)</label>
                                                <textarea class="form-control" id="bounding_box" name="bounding_box"
                                                    rows="4">{{ old('bounding_box') }}</textarea>
                                                <small class="form-text text-muted">Enter the bounding box coordinates
                                                    in JSON format. <br>Example: {"min_latitude":
                                                    40.477399,"min_longitude": -74.259090,"max_latitude":
                                                    40.917577,"max_longitude": -73.700272}</small>
                                            </div>

                                            <div class="col-12" style="margin-top:30px;">
                                                <label for="population">Population</label>
                                                <input type="number" class="form-control" id="population"
                                                    name="population" value="{{ old('population') }}" required>
                                            </div>

                                            <div class="col-12" style="margin-top:30px;">
                                                <label for="area_size">Area Size</label>
                                                <input type="text" class="form-control" id="area_size" name="area_size"
                                                    value="{{ old('area_size') }}" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 text-end" style="margin-top:15px;">
                                    <button class="btn btn-success" type="submit" id="uploadButton">
                                        <i class="ri-save-line"></i> Save
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @endsection
    @section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/trumbowyg.min.js"></script>
    <!-- Import all plugins you want AFTER importing jQuery and Trumbowyg -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/plugins/table/trumbowyg.table.min.js"></script>
    <script>
        $( document ).ready(function() {
            $('#body-desc').trumbowyg({btns: [
			['viewHTML'],
			['formatting'],
			['strong', 'em', 'del'],
			['superscript', 'subscript'],
			['link'],
			['image'], // Our fresh created dropdown
			['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
			['unorderedList', 'orderedList'],
			['horizontalRule'],
			['removeformat'],
			['fullscreen'],
			['table'], 
			['tableCellBackgroundColor', 'tableBorderColor']
			]});
			//$('#sifaris').trumbowyg();
        });

      var _url = "settings";
      @if(Session::has("message"))
        toastr.success("{{session('message')}}")
      @endif

    </script>
    @endsection