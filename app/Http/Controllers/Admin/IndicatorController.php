<?php

namespace App\Http\Controllers\Admin;

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
        $indicators = Indicator::with('user')->paginate(10);

        return view('admin.dashboard.indicator.index', compact('indicators'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.dashboard.indicator.create');
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
            'vardescription' => 'required|string',
            'varunits' => 'nullable|string',
            'is_more_better' => 'nullable',
            'transformation' => 'nullable|string',
            'lower' => 'nullable|numeric|between:-999.9,999.9',
            'upper' => 'nullable|integer|min:0',
            'sourcelinks' => 'nullable|string',
            'subnational' => 'nullable|string',
            'national' => 'nullable|string',
            'imputation' => 'nullable|string',
        ]);

        //Adding Created By User Id
        $validatedData['created_by'] = Auth::user()->id;

        //Create a new country
        $indicator = Indicator::create($validatedData);

        return redirect()->route('admin.indicator.index')->with('success', 'Indicator created successfully!!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $indicator = Indicator::with('user')->find($id);

        return view('admin.dashboard.indicator.view', compact('indicator'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Indicator $indicator)
    {
        return view('admin.dashboard.indicator.edit', compact('indicator'));
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
            'vardescription' => 'required|string',
            'varunits' => 'nullable|string',
            'is_more_better' => 'nullable',
            'transformation' => 'nullable|string',
            'lower' => 'nullable|numeric|between:-999.9,999.9',
            'upper' => 'nullable|integer|min:0',
            'sourcelinks' => 'nullable|string',
            'subnational' => 'nullable|string',
            'national' => 'nullable|string',
            'imputation' => 'nullable|string',
        ]);

        //Adding Created By User Id
        $validatedData['created_by'] = Auth::user()->id;

        //Create a new country
        $indicator = $indicator->update($validatedData);

        return redirect()->route('admin.indicator.index')->with('success', 'Indicator updated successfully!!!');
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
