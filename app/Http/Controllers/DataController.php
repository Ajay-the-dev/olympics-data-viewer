<?php

namespace App\Http\Controllers;

use App\Models\Odf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;



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
                    $players = Odf::where('playerTeam', $request->country )->get();
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
}
