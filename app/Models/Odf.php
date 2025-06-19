<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Odf extends Model
{
    protected $fillable = ["eventGame",
                            "eventName",
                            "eventDetails",
                            "report_date",
                            "report_time",
                            "reportDetails",
                            "venueName",
                            "venueLocation",
                            "playerId",
                            "playerName",
                            "playerDob",
                            "playerGender",
                            "playerTeam",
                            "stats"];
}
