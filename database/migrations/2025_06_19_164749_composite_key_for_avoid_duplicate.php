<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('odfs',function(Blueprint $table){
            $table->unique(['report_date', 'report_time', 'venueName', 'playerId'],'odfs_composite_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('odfs', function (Blueprint $table) {
            $table->dropUnique('odfs_composite_unique');
        });    
    }
};
