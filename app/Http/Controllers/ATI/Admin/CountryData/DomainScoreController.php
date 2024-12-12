<?php

namespace App\Http\Controllers\ATI\Admin\CountryData;

use App\Helpers\PaginationHelper;
use App\Models\Admin\CountryDomainData;
use App\Http\Controllers\Controller;
use App\Models\Admin\Country;
use App\Models\Admin\Indicator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DomainScoreController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $countriesData = CountryDomainData::with(['domain','country','user'])->filterData()->paginate(10);
        $countriesData = PaginationHelper::addSerialNo($countriesData);

        return view('ati.admin.dashboard.country_data.domain_score.index', compact('countriesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $indicators = Indicator::select('id', 'variablename')->where('level',0)->whereNot('variablename','Overall Score')->filterIndicator()->get();
        $countries = Country::select('country','country_code')->where('level',1)->filterATICountry()->orderBy('country','ASC')->get();

        return view('ati.admin.dashboard.country_data.domain_score.create', compact('indicators','countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'domain_id' => 'required|exists:indicators,id',
            'countrycode' => 'required|string|max:5|exists:countries,country_code',
            'year' => 'required|integer|between:2000,2100',
            'score' => 'required|numeric|min:0|max:100',
            'domain_result' => 'required|string|max:255',
            'trend_result' => 'required|string|max:255',
            'trend_percentage' => 'required|numeric',
            'shifts_governance' => 'required|string|max:255'
        ]);

        //Adding Created By User Id
        $validatedData['created_by'] = Auth::user()->id;
        $validatedData['company_id'] = Auth::user()->company_id;

        //Create a new country
        $countryData = CountryDomainData::create($validatedData);

        return redirect()->route('admin.ati.domain-score.index')->with('success', 'Domain Score created successfully!!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $countryData = CountryDomainData::with(['domain','country','user'])->filterData()->find($id);

        if($countryData){
            return view('ati.admin.dashboard.country_data.domain_score.view', compact('countryData'));
        }else{
            return redirect()->back()->with('error','Data Not Found');
        }   
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $countryData = CountryDomainData::filterData()->find($id);
        $indicators = Indicator::select('id', 'variablename')->where('level',0)->whereNot('variablename','Overall Score')->filterIndicator()->get();
        $countries = Country::select('country','country_code')->where('level',1)->filterATICountry()->orderBy('country','ASC')->get();
        
        if($countryData){
            return view('ati.admin.dashboard.country_data.domain_score.edit', compact('indicators', 'countries','countryData'));
        }else{
            return redirect()->back()->with('error','Data Not Found');
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'domain_id' => 'required|exists:indicators,id',
            'countrycode' => 'required|string|max:5|exists:countries,country_code',
            'year' => 'required|integer|between:2000,2100',
            'score' => 'required|numeric|min:0|max:100',
            'domain_result' => 'required|string|max:255',
            'trend_result' => 'required|string|max:255',
            'trend_percentage' => 'required|numeric',
            'shifts_governance' => 'required|string|max:255'
        ]);

        //Adding Created By User Id
        $validatedData['created_by'] = Auth::user()->id;
        $validatedData['company_id'] = Auth::user()->company_id;

        //Create a new country
        $countryData = CountryDomainData::find($id)->update($validatedData);

        return redirect()->route('admin.ati.domain-score.index')->with('success', 'Country Data updated successfully!!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
