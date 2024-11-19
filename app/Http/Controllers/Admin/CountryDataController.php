<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\CategoryColor;
use App\Models\Admin\Country;
use App\Models\Admin\CountryData;
use App\Models\Admin\Indicator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CountryDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $countriesData = CountryData::with(['indicator','country','user'])->paginate(10);
       
        return view('admin.dashboard.country_data.index', compact('countriesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $indicators = Indicator::select('id', 'variablename')->get();
        $countries = Country::select('country','country_code')->where('level',1)->get();
        $countries_colour = CategoryColor::select('country_leg_col','subcountry_leg_col','category')->get();

        return view('admin.dashboard.country_data.create', compact('indicators','countries','countries_colour'));
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
            'indicator_id' => 'required|exists:indicators,id',
            'countrycode' => 'required|string|max:5|exists:countries,country_code',
            'year' => 'required|integer|between:2000,2100',
            'country_score' => 'required|numeric|between:0,999999.999999999',
            'country_col' => 'required|string',
            'country_cat' => 'required|string',
        ]);

        //Adding Created By User Id
        $validatedData['created_by'] = Auth::user()->id;

        //Create a new country
        $countryData = CountryData::create($validatedData);

        return redirect()->route('admin.country-data.index')->with('success', 'Country Data created successfully!!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $countryData = CountryData::with(['indicator','country','user'])->find($id);

        return view('admin.dashboard.country_data.view', compact('countryData'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $countryData = CountryData::find($id);
        $indicators = Indicator::select('id', 'variablename')->get();
        $countries = Country::select('country','country_code')->where('level',1)->get();
        $countries_colour = CategoryColor::select('country_leg_col','subcountry_leg_col','category')->get();
        return view('admin.dashboard.country_data.edit', compact('indicators', 'countries','countries_colour','countryData'));
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
            'indicator_id' => 'required|exists:indicators,id',
            'countrycode' => 'required|string|max:5|exists:countries,country_code',
            'year' => 'required|integer|between:2000,2100',
            'country_score' => 'required|numeric|between:0,999999.999999999',
            'country_col' => 'required|string',
            'country_cat' => 'required|string',
        ]);

        //Adding Created By User Id
        $validatedData['created_by'] = Auth::user()->id;

        //Create a new country
        $countryData = CountryData::find($id)->update($validatedData);

        return redirect()->route('admin.country-data.index')->with('success', 'Country Data updated successfully!!!');
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
