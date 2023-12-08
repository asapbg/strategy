<?php

use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardModerator;
use App\Models\User;
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
        Schema::create((new AdvisoryBoardModerator())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('advisory_board_id');
            $table->foreign('user_id')->references('id')->on((new User())->getTable());
            $table->foreign('advisory_board_id')->references('id')->on((new AdvisoryBoard())->getTable());
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
        Schema::dropIfExists((new AdvisoryBoardModerator())->getTable());
    }
};
