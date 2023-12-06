<?php

use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardModeratorInformation;
use App\Models\AdvisoryBoardModeratorInformationTranslation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create((new AdvisoryBoardModeratorInformation())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('advisory_board_id');
            $table->foreign('advisory_board_id')->references('id')->on((new AdvisoryBoard())->getTable())->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create((new AdvisoryBoardModeratorInformationTranslation())->getTable(), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedBigInteger('advisory_board_moderator_information_id');
            $table->unique(['advisory_board_moderator_information_id', 'locale'], 'unique_advisory_board_moderator_information_translations');
            $table->foreign('advisory_board_moderator_information_id')
                ->references('id')
                ->on((new AdvisoryBoardModeratorInformation())->getTable())
                ->onDelete('cascade');

            $table->longText('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists((new AdvisoryBoardModeratorInformation())->getTable());
    }
};
