<?php

use App\Models\LegislativeInitiativeVote;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create((new LegislativeInitiativeVote())->getTable(), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('legislative_initiative_id')->unsigned();
            $table->foreign('legislative_initiative_id')->references('id')->on('legislative_initiative')->onDelete('cascade');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists((new LegislativeInitiativeVote())->getTable());
    }
};
