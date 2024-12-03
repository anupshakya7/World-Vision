<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Country;
use App\Models\Admin\Indicator;
use App\Models\Admin\Source;
use App\Models\Admin\SubCountry;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function dashboard()
    {
        $data['countries'] = Country::where('level',1)->count();
        $data['indicators'] = Indicator::where('company_id',auth()->user()->company_id)->count();
        $data['subcountries'] = SubCountry::count();
        $data['sources'] = Source::count();

        return view('admin.dashboard.index',compact('data'));
    }
}
