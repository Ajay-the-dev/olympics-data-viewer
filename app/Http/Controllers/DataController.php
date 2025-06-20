<?php

namespace App\Http\Controllers;

use App\Models\Odf;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use stdClass;

class DataController extends Controller
{
    public function test()
    {
        return response()->json([
            "success" => 1,
            "message" => "api successfully completed",
            "data" => null
        ]);
    }

    public function saveXml(Request $request)
    {
        try {

            // return $request->all();

                $validation = Validator::make($request->all(), [
                    "eventName" => "required",
                    "gameName" => "required",
                    "reportDate" => "required",
                    "reportTime" => "required",
                    "venueName" => "required",
                    "venueLocation" => "required",
                    "playerId" => "required",
                    "playerName" => "required",
                    "playerDob" => "required",
                    "playerGender" => "required",
                    "playerTeam" => "required",
                    "statistics" => "required"
                ]);

                if($validation->fails())
                {
                    return response()->json([
                        "success" => 0,
                        "message" => "validation failed",
                        "data" => $request->all()
                    ]); 
                }
                else{

                    $request->eventDetails = $request->eventDetails ? json_encode($request->eventDetails) : json_encode('{}');
                    $request->reportDetails = $request->reportDetails ? json_encode($request->reportDetails) : json_encode('{}');
                    $request->statistics = $request->statistics ? json_encode($request->statistics) : json_encode('{}');
                    
                    $hours = substr($request->reportTime, 0, 2);
                    $minutes = substr($request->reportTime, 2, 2);
                    $seconds = substr($request->reportTime, 4, 2);

                    $mysqlTime = "$hours:$minutes:$seconds";

                    try{
                        $data = Odf::create([
                            "eventName" => $request->eventName,
                            "eventGame" => $request->gameName,
                            "eventDetails" => $request->eventDetails,
                            "report_date" => $request->reportDate,
                            "report_time" => $mysqlTime,
                            "reportDetails" => $request->reportDetails,
                            "venueName" => $request->venueName,
                            "venueLocation" => $request->venueLocation,
                            "playerId" => $request->playerId,
                            "playerName" => $request->playerName,
                            "playerDob" => $request->playerDob,
                            "playerGender" => $request->playerGender,
                            "playerTeam" =>$request->playerTeam,
                            "stats" => $request->statistics
                        ]);
                    }catch (QueryException $e) {

                        if($e->errorInfo[1] == 1062)
                        {
                            return response()->json([
                                "success" => 0,
                                "message" => "Entry already exists",
                                "data" => null
                            ]);     
                        }
                    }

                    return response()->json([
                                "success" => 1,
                                "message" => "XML is saved successfully",
                                "data" => $request->all()
                            ]);        
                }

            }
            catch (\Throwable $th) {
            return response()->json([
                        "success" => 0,
                        "message" => "Server : Something went wrong while saving",
                        "data" => json_encode($th),
                        "error" => [
                                    'message' => $th->getMessage()
                                ]
                    ]);        
            }
    }

    public function getAllTeams(Request $request)
    {
        try {
            
            $uniqueTeams = Odf::distinct()->pluck('playerTeam')->values();

            return response()->json([
                                "success" => 1,
                                "message" => "Teams fetched successfully",
                                "data" => $uniqueTeams
                ]);    

        } catch (\Throwable $th) {
            return response()->json([
                        "success" => 0,
                        "message" => "Server : Something went wrong while getting teams",
                        "data" => json_encode($th),
                        "error" => [
                                    'message' => $th->getMessage()
                                ]
                ]);      
        }
    }


    public function getPlayers(Request $request)
    {
        try {

                if($request->country)
                {

                    if($request->playerId)
                    {
                        $players = Odf::where('playerId', $request->playerId )->paginate(30);
                        return response()->json([
                                        "success" => 1,
                                        "message" => "players successfully",
                                        "data" => json_encode($players)
                        ]);
                    }
                    $players = Odf::where('playerTeam', $request->country )->paginate(30);
                    return response()->json([
                                    "success" => 1,
                                    "message" => "players successfully",
                                    "data" => json_encode($players)
                    ]);
                }
                elseif($request->name) {

                        $players = Odf::where('playerName', 'like', '%' . $request->name . '%')->paginate(30);   
                        return response()->json([
                                        "success" => 1,
                                        "message" => "players successfully",
                                        "data" => json_encode($players)
                        ]);
                }
                else{
                    $players = Odf::latest()->paginate(30);
                    return response()->json([
                                    "success" => 1,
                                    "message" => "players successfully",
                                    "data" => json_encode($players)
                    ]);
                }


        } catch (\Throwable $th) {
           return response()->json([
                        "success" => 0,
                        "message" => "Server : Something went wrong while getting players",
                        "data" => json_encode($th),
                        "error" => [
                                        'message' => $th->getMessage()
                                    ]
                    ]);   
        }
    }

    public function getMaxMinDobOfplayers(Request $request)
    {
        try {

            
            if($request->country)
            {
                $max = Odf::where('playerTeam', $request->country)->max('playerDob');
                $min = Odf::where('playerTeam', $request->country)->min('playerDob');
            }
            else{
                $max = Odf::max('playerDob');
                $min = Odf::min('playerDob');
            }

                $data = new stdClass();
                $data->min = $min;
                $data->max = $max;

                return response()->json([
                        "success" => 1,
                        "message" => "got max min date ".$request->country,
                        "data" => $data
                    ]);   

        } catch (\Throwable $th) {
            return response()->json([
                        "success" => 0,
                        "message" => "Server : Something went wrong while getting max min data",
                        "data" => json_encode($th),
                        "error" => [
                                        'message' => $th->getMessage()
                                    ]
                    ]);   
        }
    }


    public function getPlayerDataForFilter(Request $request)
    {
        try{


            $query = Odf::query();

            //player team condition
            if ($request->filled('playerTeam')) {
                $query->where('playerTeam', $request->playerTeam );
            }

            //player age condition
            if ($request->filled('fromAge') || $request->filled('toAge')) 
            {
                    $today = date('Y-m-d');
                    if ($request->filled('fromAge') && $request->filled('toAge')) 
                    {
                        if($request->fromAge == $request->toAge)
                        {
                            $maxDob = date('Y-m-d', strtotime("-$request->fromAge years", strtotime($today)));      
                            $minDob = date('Y-m-d', strtotime("-" . ($request->fromAge + 1) . " years", strtotime($today)));
                            $query->whereDate('playerDob', '>=', $minDob)->whereDate('playerDob', '<', $maxDob);
                        }
                        else{
                                $minAge = min($request->fromAge, $request->toAge);
                                $maxAge = max($request->fromAge, $request->toAge);

                                $minDob = date('Y-m-d', strtotime("-$maxAge years -1 Day", strtotime($today))); // older
                                $maxDob = date('Y-m-d', strtotime("-$minAge years", strtotime($today))); // younger
                                
                                $query->whereDate('playerDob', '>=', $minDob)->whereDate('playerDob', '<=', $maxDob);

                        }
                    }
                    elseif($request->filled('fromAge'))
                    {
                        $maxDob = date('Y-m-d', strtotime("-$request->fromAge years", strtotime($today)));
                        $query->whereDate('playerDob', '<=', $maxDob);
                    }
                    elseif($request->filled('toAge'))
                    {
                        $minDob = date('Y-m-d', strtotime("-$request->toAge years -1 day", strtotime($today)));
                        $query->whereDate('playerDob', '>=', $minDob);
                    }
                   
            }

            // player report entry date

            if ($request->filled('fromdate') || $request->filled('toDate')) 
            {
                if ($request->filled('fromdate') && $request->filled('toDate')) 
                {
                    if($request->fromdate == $request->toDate)
                    {
                        $request->fromdate = $request->fromdate." 00:00:00";
                        $query->whereDate('report_date', '=', $request->fromdate);
                    }
                    else{
                        $request->fromdate = $request->fromdate." 00:00:00";
                        $request->toDate = $request->toDate." 00:00:00";
                        $query->whereDate('report_date', '>=', $request->fromdate)->whereDate('report_date', '<=', $request->toDate);
                    }
                }
                elseif ($request->filled('fromdate')) {
                        $request->fromdate = $request->fromdate." 00:00:00";
                        $query->whereDate('report_date', '>=', $request->fromdate);
    
                }
                elseif ($request->filled('toDate')) {
                        $request->toDate = $request->toDate." 00:00:00";
                        $query->whereDate('report_date', '<=', $request->toDate);
                }
            }
            if($request->filled('playerName'))
            {
               $query->where('playerName', 'like', '%' .$request->playerName. '%');
            }


            $generatedSql = $query->toSql();
            $bindings = $query->getBindings();
            // return $generatedSql;
            $fullSql = vsprintf(str_replace('?', "'%s'", $generatedSql), $bindings);

            $data = $query->paginate(30);

            return response()->json([
                        "success" => 1,
                        "message" => "data fetched successfully ",
                        "data" => $data,
                        "log" => $request->all(),
                        "query" => $fullSql,
                        "test" =>  $request->filled('toDate')
                    ]);   



        }
        catch (\Throwable $th) {
            return response()->json([
                        "success" => 0,
                        "message" => "Server : Something went wrong while getting data for filter",
                        "data" => json_encode($th),
                        "error" => [
                                        'message' => $th->getMessage()
                                    ]
                    ]);   
        }
    }
}
