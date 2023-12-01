<?php

use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardMeeting;
use App\Models\AdvisoryBoardMeetingTranslation;
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
        Schema::create((new AdvisoryBoardMeeting())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('advisory_board_id');
            $table->foreign('advisory_board_id')->references('id')->on((new AdvisoryBoard())->getTable());
            $table->timestamp('next_meeting');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create((new AdvisoryBoardMeetingTranslation())->getTable(), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedBigInteger('advisory_board_meeting_id');
            $table->unique(['advisory_board_meeting_id', 'locale'], 'unique_advisory_board_meeting_translations');
            $table->foreign('advisory_board_meeting_id')
                ->references('id')
                ->on((new AdvisoryBoardMeeting())->getTable())
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
        Schema::dropIfExists((new AdvisoryBoardMeeting())->getTable());
        Schema::dropIfExists((new AdvisoryBoardMeetingTranslation())->getTable());
    }
};
