<?php

namespace App\Http\Controllers\ATI\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Country;
use App\Models\Admin\Indicator;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function dashboard(){
        $data['countries'] = Country::where('level',1)->filterATICountry()->count();
        $data['indicators'] = Indicator::filterIndicator()->count();
        
        return view('ati.admin.dashboard.index',compact('data'));
    }
}
