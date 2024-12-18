<?php

namespace App\Http\Controllers\ATI\API;

use App\Http\Controllers\Controller;
use App\Models\Admin\Country;
use App\Models\Admin\CountryData;
use App\Models\Admin\CountryDomainData;
use App\Models\Admin\Indicator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AllAPIController extends Controller
{
    //Upcoming Election and Historical Democratic Disruptions
    public function mapAPI(Request $request){
        $validator = Validator::make($request->all(),[
            'political_type' => 'required|string'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),404);
        }

        if($request->filled('political_type')){
            $type = $request->political_type;
            
            if($type == 'election'){
                $map = Country::select(['countries.country','countries.country_code','country_data.year'])->leftJoin('country_data','countries.country_code','=','country_data.countrycode')->where('countries.ati',1)->where('country_data.political_context',0)->orderBy('country_data.year','asc')->distinct()->get();
            }elseif($type=='disruption'){
                $map = Country::select(['countries.country','countries.country_code','country_data.country_score as score'])->leftJoin('country_data','countries.country_code','=','country_data.countrycode')->where('countries.ati',1)->where('country_data.political_context',1)->orderBy('countries.country','asc')->distinct()->get();
            }
            
            if(isset($map)){
                return response()->json([
                    'success'=>true,
                    'map'=>$map
                ]);
            }else{
                return response()->json([
                    'success'=>false,
                    'message'=>'Please enter correct political type'
                ]);
            }
        }
    }

    //Domain Result and Voice Of People API
    public function domainVoiceAPI(Request $request){
        $validator = Validator::make($request->all(),[
            'countrycode' => 'required|string|max:3',
            'year'=>'nullable'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),404);
        }
        $year = $request->year ? $request->year : Carbon::now()->year; 

        if($request->filled('countrycode')){
            //Domain Data
            $domains = Indicator::where('level',0)->where('company_id',2)->whereNot('variablename','Overall Score')->get();
            $domainResult = [];
            $domainTrendResult10Year = [];
            $domainMainResult = [];
            
            foreach($domains as $domain){
                for($i=(Carbon::now()->year-10);$i<Carbon::now()->year;$i++){
                    $score= CountryDomainData::where('domain_id',$domain->id)->where('countrycode',$request->countrycode)->where('year',$i)->pluck('score')->first();
                    $domainTrendResult10Year[$i] = $score ? $score:0;
                }
                $domainResult = CountryDomainData::select('countrycode','year','score','domain_result','trend_result','trend_percentage')->where('domain_id',$domain->id)->where('countrycode',$request->countrycode)->where('year',$year)->latest()->first();

                $domainMainResult[$domain->variablename] = [
                    'countrycode'=>isset($domainResult->countrycode)?$domainResult->countrycode:null,
                    'year'=>isset($domainResult->year)?$domainResult->year:null,
                    'score'=>isset($domainResult->score)?$domainResult->score:null,
                    'domain_result'=>isset($domainResult->domain_result)?$domainResult->domain_result:null,
                    'trend_result'=>isset($domainResult->trend_result)?$domainResult->trend_result:null,
                    'trend_percentage'=>isset($domainResult->trend_percentage)?$domainResult->trend_percentage:null,
                    'trend_10_year'=>$domainTrendResult10Year
                ];
            }
            //Domain Data

            //Voice of People Data
            $voiceOfPeoples = ["The Judicial System","Politics","Elections"];
            $voiceOfPeopleResult = [];

            foreach($voiceOfPeoples as $voiceOfPeople){
                $voiceOfPeopleResult[$voiceOfPeople] = CountryData::select('countrycode','year','country_score as score')->where('political_context',3)->where('countrycode',$request->countrycode)->where('year',$year)->latest()->first();
            }

            //Voice of People Data
            // $domainResult = Indicator::select(['indicators.variablename','country_domain_data.countrycode','country_domain_data.year','country_domain_data.score','country_domain_data.domain_result','country_domain_data.trend_result','country_domain_data.trend_percentage'])->leftJoin('country_domain_data','indicators.id','=','country_domain_data.domain_id')->where('country_domain_data.countrycode',$request->countrycode)->where('indicators.level',0)->where('indicators.company_id',2)->where('country_domain_data.year',$year)->get();
            // $voiceOfPeopleResult = CountryData::select('countrycode','remarks as category','year','country_score as score')->where('countrycode',$request->countrycode)->where('year',$year)->where('political_context',3)->get();

            return response()->json([
                'success'=>true,
                'domain_result'=>$domainMainResult,
                'voice_of_people'=>$voiceOfPeopleResult
            ]);
        }
        
    }

    //Radar Chart Domain and Indicator
    public function radarTrendChartDomainIndicator(Request $request){
        $validator = Validator::make($request->all(),[
            'countrycode' => 'nullable|string|max:3',
            'domain_id' => 'required|integer',
            'year'=>'nullable',
            'graphType'=>'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),404);
        }

        //Graph Type
        $type = $request->graphType;

        $indicator = Indicator::query();
        $indicatorData = CountryData::query();
        $indicatorData->where('political_context',2);

        if($request->filled('countrycode')){
            $indicatorData->where('countrycode',$request->countrycode);
        }
        
        $indicatorResult =[];
        if($type == 'radar'){
            $indicators = $indicator->where('domain_id',$request->domain_id)->get();

            foreach($indicators as $indicator){
                $indicatorQuery = clone $indicatorData;
                $indicatorCount = $indicatorQuery->where('indicator_id',$indicator->id)->count();
                $indicatorCount = $indicatorCount ? $indicatorCount : 1;

                $indicatorScore = $indicatorQuery->where('indicator_id',$indicator->id)->sum('country_score');
                $indicatorResult[$indicator->variablename] = $indicatorScore / $indicatorCount;
            }
        }elseif($type == 'trend'){
            $indicators = $indicator->where('id',$request->domain_id)->where('level',1)->first();
            $indicatorYearResult=[];

            if($indicators){
                for($i=(Carbon::now()->year-10);$i<=Carbon::now()->year;$i++){
                    $scoreQuery = clone $indicatorData;
                    // $indicatorScore = $scoreQuery->where('indicator_id',$request->domain_id)->where('year',$i)->pluck('country_score')->first();
                    // $indicatorCount = $scoreQuery->where('indicator_id',$request->domain_id)->where('year',$i)->count();
                    $indicatorScore = $scoreQuery->where('indicator_id',$request->domain_id)->where('year',$i)->pluck('country_score')->first();

                    $indicatorYearResult[$i] = $indicatorScore ? $indicatorScore:0;
                }

                $indicatorResult = [
                    'id'=>$indicators->id,
                    'title'=>$indicators->variablename,
                    'trend_score'=>$indicatorYearResult
                ];   
            }
        }


        if(count($indicatorResult)>0){
            return response()->json([
                'success'=>true,
                'data'=>$indicatorResult
            ]);
        }else{
            return response()->json([
                'success'=>false,
                'message'=>"Data Not Found"
            ]);
        }
    }

    //Domain Score and Governance Vs Enabling Graph
    public function domainGovernanceCompare(Request $request){
        $validator = Validator::make($request->all(),[
            'countrycode' => 'nullable|string|max:3',
            'year'=>'nullable',
            'graphType'=>'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),404);
        }

        //Graph Type
        $type = $request->graphType;

        $indicator = Indicator::query();
        $domainData = CountryDomainData::query();
        $year = $request->year;
        $domainResult =[];

        if($request->filled('countrycode')){
            $domainData->where('countrycode',$request->countrycode);
        }
        
        if($type == 'domain'){
            $domains = $indicator->where('level',0)->where('company_id',2)->get();

            $domain10YearResult = [];

            foreach($domains as $domain){
                for($i=(Carbon::now()->year-10);$i<=Carbon::now()->year;$i++){
                    if($domain->variablename !== "Overall Score"){
                        $domainQuery = clone $domainData;
                        $domainScore = $domainQuery->where('domain_id',$domain->id)->where('year',$i)->pluck('score')->first();
                        $domainScore = $domainScore ? $domainScore:0;
                        $domain10YearResult[$i] = $domainScore;
                    }

                    if($domain->variablename == "Overall Score"){
                        $domainQuery = clone $domainData;
                        $domainScore = $domainQuery->where('year',$i)->sum('score');
                        $domainCount = $domainQuery->where('year',$i)->count();
                        $domainCount = $domainCount ? $domainCount:1;
                        $domainOverallScore = ($domainScore/$domainCount);
                        $domain10YearResult[$i] = $domainOverallScore;
                    }
                }

                $domainResult[$domain->variablename]=$domain10YearResult;
            }
        }elseif($type == 'governance'){
            $countries = Country::select(['country','country_code'])->where('ati',1)->get();
            $domains = Indicator::select('id','variablename')->where('level',0)->where('company_id',2)->whereNot('variablename','Overall Score')->get();
            $domainScore = CountryDomainData::query();

            foreach($countries as $country){
                $domainEachScore =[];
                $ati_governance_score = 0;
                $ati_governance_count = 0;

                foreach($domains as $domain){
                    if(($domain->variablename == 'Rule by the People') || ($domain->variablename == 'Rule of Law')){
                        $domainQuery = clone $domainScore;
                        $ati_governance_score += $domainQuery->where('domain_id',$domain->id)->where('countrycode',$country->country_code)->where('year',$year)->sum('score');
                        $ati_governance_count += $domainQuery->where('domain_id',$domain->id)->where('countrycode',$country->country_code)->where('year',$year)->count();
                        $ati_governance_count = $ati_governance_count ? $ati_governance_count:1;
                    }else{
                        $domainQuery = clone $domainScore;
                        $domainEnablingScore = $domainQuery->where('domain_id',$domain->id)->where('countrycode',$country->country_code)->where('year',$year)->pluck('score')->first();
                        $domainEnablingScore = $domainEnablingScore ? $domainEnablingScore : 0;
                        $domainEachScore[$domain->variablename] = $domainEnablingScore;
                    }
                    $domainEachScore['ATI Governance'] = ($ati_governance_score/$ati_governance_count);
                }

                $domainResult[$country->country_code] = $domainEachScore;
            }
        }

        if(count($domainResult)>0){
            return response()->json([
                'success'=>true,
                'data'=>$domainResult
            ]);
        }else{
            return response()->json([
                'success'=>false,
                'message'=>'Data Not Found'
            ]);
        }
    }

    //Risk Outlook
    public function riskOutlookAPI(Request $request){
        $validator = Validator::make($request->all(),[
            'countrycode' => 'required|string|max:3',
            'year'=>'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),404);
        }
        // $indicatorResult = [];
        // $indicatorTrendResult10Year =[];
        // $indicators = Indicator::select('id','variablename')->where('level',1)->where('company_id',2)->get();
        // $countryData = CountryData::query();
        // $countryData->where('political_context',2);

        // foreach($indicators as $indicator){
        //     for($i=(Carbon::now()->year-10);$i<=Carbon::now()->year;$i++){
        //         $countryQuery = clone $countryData;
        //         $score= $countryQuery->where('indicator_id',$indicator->id)->where('countrycode',$request->countrycode)->where('year',$i)->pluck('country_score')->first();
        //         $indicatorTrendResult10Year[$i] = $score ? $score:0;
        //     }
        //     $indicatorData = $countryData->select('id','countrycode','year','country_score as score')->where('indicator_id',$indicator->id)->where('countrycode',$request->countrycode)->first();
        //     // if($indicatorData){
        //     //     $indicatorResult[] = [
        //     //         'id'=>$indicatorData->id,
        //     //         'title'=>$indicator->variablename,
        //     //         'countrycode'=>$indicatorData->countrycode,
        //     //         'year'=>$indicatorData->year,
        //     //         'score'=>$indicatorData->score,
        //     //         'trend10year'=>$indicatorTrendResult10Year
        //     //     ];
        //     // }
        //     $indicatorResult[$indicator->variablename] = [$indicatorData,$indicatorTrendResult10Year];
           
        // }
        // return $indicatorResult;
        $year = $request->year;

        $indicators = Indicator::select('indicators.id','indicators.variablename','country_data.countrycode','country_data.year','country_data.country_score')->leftJoin('country_data','indicators.id','=','country_data.indicator_id')->where('indicators.level',1)->where('indicators.company_id',2)->where('countrycode',$request->countrycode)->where('country_data.year',$year)->distinct()->get();
        
        if($indicators){
            return response()->json([
                'success'=>true,
                'data'=>$indicators
            ]);
        }else{
            return response()->json([
                'success'=>false,
                'message'=>'Data Not Found'
            ]);
        }
    }
}
