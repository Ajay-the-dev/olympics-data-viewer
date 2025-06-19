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
        Schema::create('odfs', function (Blueprint $table) {
            $table->id();
            $table->string('eventGame');
            $table->string('eventName');
            $table->json('eventDetails')->nullable();
            $table->date('report_date');
            $table->time('report_time');
            $table->json('reportDetails')->nullable();
            $table->string('venueName');
            $table->string('venueLocation');
            $table->bigInteger('playerId');
            $table->string('playerName');
            $table->date('playerDob');
            $table->string('playerGender');
            $table->string('playerTeam');
            $table->json('stats')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('odfs');
    }
};
