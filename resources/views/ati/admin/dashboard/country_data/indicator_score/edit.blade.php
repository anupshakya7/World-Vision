@extends('ati.admin.dashboard.layout.web')
@section('title','Indicator Score Update')
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
                        <h4 class="mb-sm-0">Indicator Score</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{route('admin.ati.home')}}">ATI</a></li>
                                <li class="breadcrumb-item">Country Data</li>
                                <li class="breadcrumb-item"><a href="{{route('admin.ati.indicator-score.index')}}">Indicator Score</a></li>
                                <li class="breadcrumb-item active">{{ 'Update Indicator Score'}}</li>
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
                            <h5 class="card-title mb-0">{{ 'Update Indicator Score' }}</h5>
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
                            <form action="{{ route('admin.ati.indicator-score.update',$countryData) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="indicator_id">{{ 'Indicator' }} <span
                                                            style="color:red;">*</span></label>
                                                        <select class="form-control form-select" id="indicator_id"
                                                        name="indicator_id">
                                                            <option value="">None</option>
                                                            @foreach ($indicators as $indicator)
                                                            <option value="{{ $indicator->id }}" {{$countryData->indicator_id == $indicator->id ? 'selected':''}}>{{ $indicator->variablename }}</option>
                                                            @endforeach
                                                        </select>
                                                    @if($errors->has('indicator_id'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('indicator_id') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12" style="margin-top:20px;">
                                                <div class="form-group">
                                                    <label for="countrycode">{{ 'Country' }} <span
                                                            style="color:red;">*</span></label>
                                                        <select class="form-control form-select" id="countrycode"
                                                        name="countrycode">
                                                            <option value="">None</option>
                                                            @foreach ($countries as $country)
                                                            <option value="{{ $country->country_code }}" {{$countryData->countrycode == $country->country_code ? 'selected':''}}>{{ $country->country }}</option>
                                                            @endforeach
                                                        </select>
                                                    @if($errors->has('countrycode'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('countrycode') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-4">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="year">{{ 'Year' }} <span
                                                            style="color:red;">*</span></label>
                                                    <select class="form-control form-select" id="year"
                                                    name="year">
                                                        <option value="">None</option>
                                                        @for ($i=now()->year;$i>=2000;$i--)
                                                        <option value="{{ $i }}" {{$countryData->year == $i ? 'selected':''}}>{{ $i }}</option>
                                                        @endfor
                                                    </select>
                                                    @if($errors->has('year'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('year') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12" style="margin-top:20px;">
                                                <div class="form-group">
                                                    <label for="country_score">{{ 'Indicator Score' }} <span
                                                            style="color:red;">*</span></label>
                                                    <input type="text" name="country_score" class="form-control"
                                                            value="{{ old('country_score',$countryData->country_score) }}" placeholder="Country Score">
                                                    @if($errors->has('country_score'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('country_score') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12" style="margin-top:30px;">
                                    <a class="btn btn-info" href="{{route('admin.ati.indicator-score.index')}}">
                                        <i class="ri-arrow-left-line"></i> Back to list
                                    </a>
                                    <button class="btn btn-success float-end" type="submit" id="uploadButton">
                                        <i class="ri-save-line"></i> Update
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

            //For Color Category
            $('#country_col').change(function(){
                var selectedOption = $(this).find('option:selected');
                var countryCategory = selectedOption.data('category') ?? null;

                if(countryCategory !== null){
                    $('#country_cat').val(countryCategory);
                }else{
                    $('#country_cat').val('');
                }
                
            });
            
        });

      var _url = "settings";
      @if(Session::has("message"))
        toastr.success("{{session('message')}}")
      @endif

    </script>
    @endsection