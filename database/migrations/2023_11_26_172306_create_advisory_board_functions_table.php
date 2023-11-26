<?php

use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardFunction;
use App\Models\AdvisoryBoardFunctionTranslation;
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
        Schema::create((new AdvisoryBoardFunction())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('advisory_board_id');
            $table->foreign('advisory_board_id')->references('id')->on((new AdvisoryBoard())->getTable());
            $table->timestamps();
        });

        Schema::create((new AdvisoryBoardFunctionTranslation())->getTable(), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedBigInteger('advisory_board_function_id');
            $table->unique(['advisory_board_function_id', 'locale'], 'unique_advisory_board_function_translations');
            $table->foreign('advisory_board_function_id')
                ->references('id')
                ->on((new AdvisoryBoardFunction())->getTable())
                ->onDelete('cascade');

            $table->string('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists((new AdvisoryBoardFunction())->getTable());
        Schema::dropIfExists((new AdvisoryBoardFunctionTranslation())->getTable());
    }
};
