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
        $data['countries'] = Country::count();
        $data['subcountries'] = SubCountry::count();
        $data['indicators'] = Indicator::count();
        $data['sources'] = Source::count();

        return view('admin.dashboard.index',compact('data'));
    }
}
