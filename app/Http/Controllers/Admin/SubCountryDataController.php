<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SubCountryCSVData;
use App\Models\Admin\CategoryColor;
use App\Models\Admin\Indicator;
use App\Models\Admin\SubCountry;
use App\Models\Admin\SubCountryData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;

class SubCountryDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subcountriesData = SubCountryData::with(['indicator','subcountry','user'])->paginate(10);

        return view('admin.dashboard.subcountry_data.index', compact('subcountriesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $indicators = Indicator::select('id', 'variablename')->get();
        $subcountries = SubCountry::select('geoname','geocode')->get();
        $countries_colour = CategoryColor::select('country_leg_col','subcountry_leg_col','category')->get();

        return view('admin.dashboard.subcountry_data.create', compact('indicators','subcountries','countries_colour'));
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
            'geocode' => 'required|string',
            'year' => 'required|integer|digits:4',
            'raw' => 'required|numeric|between:-9999999999999999.999,9999999999999999.999',
            'banded' => 'required|numeric|between:-99999999999.999,99999999999.999',
            'in_country_rank' => 'required|integer|min:0',
            'admin_cat' => 'required|integer|min:0',
            'admin_col' => 'required|string',
            'source_id' => 'required|integer',
            'statements' => 'required|string'
        ]);

        //Adding Created By User Id
        $validatedData['created_by'] = Auth::user()->id;

        //Create a new country
        $subCountryData = SubCountryData::create($validatedData);

        return redirect()->route('admin.sub-country-data.index')->with('success', 'Sub Country Data created successfully!!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $subCountryData = SubCountryData::with(['indicator','subcountry','user'])->find($id);

        return view('admin.dashboard.subcountry_data.view', compact('subCountryData'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $subcountryData = SubCountryData::find($id);
        $indicators = Indicator::select('id', 'variablename')->get();
        $subcountries = SubCountry::select('geoname','geocode')->get();
        $countries_colour = CategoryColor::select('country_leg_col','subcountry_leg_col','category')->get();
        return view('admin.dashboard.subcountry_data.edit', compact('indicators', 'subcountries','countries_colour','subcountryData'));
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
            'geocode' => 'required|string',
            'year' => 'required|integer|digits:4',
            'raw' => 'required|numeric|between:-9999999999999999.999,9999999999999999.999',
            'banded' => 'required|numeric|between:-99999999999.999,99999999999.999',
            'in_country_rank' => 'required|integer|min:0',
            'admin_cat' => 'required|integer|min:0',
            'admin_col' => 'required|string',
            'source_id' => 'required|integer',
            'statements' => 'required|string'
        ]);

        //Adding Created By User Id
        $validatedData['created_by'] = Auth::user()->id;

        //Create a new country
        $subCountryData = SubCountryData::find($id)->update($validatedData);

        return redirect()->route('admin.sub-country-data.index')->with('success', 'Sub Country Data updated successfully!!!');
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

    //Export csv file
    public function generateCSV(){
        $subCountriesData = SubCountryData::with(['indicator','subcountry','user'])->get();
        $filename = 'sub-country-data.csv';
        $fp = fopen($filename,'w+');
        fputcsv($fp,array('ID','Indicator','Sub Country','Geo Code','Year','Raw','Banded','In Country Rank','Admin Category','Admin Color','Source Id','Statement','Created By','Created At'));

        foreach($subCountriesData as $row){
            fputcsv($fp,array(
                $row->id,
                $row->indicator->variablename,
                optional($row->subcountry)->geoname ?? 'No Sub Country',
                $row->geocode,
                $row->year,
                $row->raw,
                $row->banded,
                $row->in_country_rank,
                $row->admin_cat,
                $row->admin_col,
                $row->source_id,
                $row->statements,
                $row->user->name,
                $row->created_at,
            ));
        }

        fclose($fp);
        $headers = array('Content-Type'=>'text/csv');
        return response()->download($filename,'sub-country-data.csv',$headers);
    }

    //Bulk Import
    public function bulk(){
        return view('admin.dashboard.subcountry_data.bulk');
    }

    public function bulkInsert(Request $request){
        $validatedData = $request->validate([
            'csv_file'=>'required|file|mimes:csv|max:500000'
        ]);

        if($request->has('csv_file')){
            $csv = file($request->csv_file);
            $chunks = array_chunk($csv,1000);
            $header = [];
            $batch = Bus::batch([])->dispatch();

            foreach($chunks as $key=>$chunk){
                $data = array_map('str_getcsv',$chunk);

                if($key == 0){
                    $header = $data[0];
                    unset($data[0]);
                }
                $batch->add(new SubCountryCSVData($header,$data));
            }
        }

        $subcountriesData = SubCountryData::with(['indicator','subcountry','user'])->paginate(10);
        return redirect()->route('admin.sub-country-data.index',compact('subcountriesData'))->with('success','CSV import added on queue. Will update you once done!!!');
    }
}
