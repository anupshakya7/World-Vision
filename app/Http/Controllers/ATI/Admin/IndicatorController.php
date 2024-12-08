<?php

namespace App\Http\Controllers\ATI\Admin;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin\Indicator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndicatorController extends Controller
{
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $indicators = Indicator::select('id','domain','variablename_long','variablename','vardescription','created_by')->with('user')->filterIndicator()->paginate(10);

        //Serial No
        $indicators = PaginationHelper::addSerialNo($indicators);

        return view('ati.admin.dashboard.indicator.index', compact('indicators'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('ati.admin.dashboard.indicator.create');
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
            'domain' => 'required|string',
            'variablename_long' => 'required|string',
            'variablename' => 'required|string',
            'vardescription' => 'nullable|string'
        ]);

        //Adding Created By User Id
        $validatedData['created_by'] = Auth::user()->id;
        $validatedData['company_id'] = Auth::user()->company_id;

        //Create a new country
        $indicator = Indicator::create($validatedData);

        return redirect()->route('admin.ati.indicator.index')->with('success', 'Indicator created successfully!!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $indicator = Indicator::with('user')->filterIndicator()->find($id);

        if($indicator){
            return view('ati.admin.dashboard.indicator.view', compact('indicator'));
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
    public function edit(Indicator $indicator)
    {
        $indicator = $indicator->filterIndicator()->find($indicator->id);
        
        if($indicator){
            return view('ati.admin.dashboard.indicator.edit', compact('indicator'));
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
    public function update(Request $request, Indicator $indicator)
    {
        $validatedData = $request->validate([
            'domain' => 'required|string',
            'variablename_long' => 'required|string',
            'variablename' => 'required|string',
            'vardescription' => 'nullable|string'
        ]);

        //Adding Created By User Id
        $validatedData['created_by'] = Auth::user()->id;
        $validatedData['company_id'] = Auth::user()->company_id;

        //Create a new country
        $indicator = $indicator->update($validatedData);

        return redirect()->route('admin.ati.indicator.index')->with('success', 'Indicator updated successfully!!!');
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
