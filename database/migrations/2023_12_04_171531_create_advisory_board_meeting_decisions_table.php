<?php

use App\Models\AdvisoryBoardMeeting;
use App\Models\AdvisoryBoardMeetingDecision;
use App\Models\AdvisoryBoardMeetingDecisionTranslation;
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
        Schema::create((new AdvisoryBoardMeetingDecision())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('advisory_board_meeting_id');
            $table->foreign('advisory_board_meeting_id')->references('id')->on((new AdvisoryBoardMeeting())->getTable());
            $table->timestamp('date_of_meeting');
            $table->string('agenda')->nullable();
            $table->string('protocol')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create((new AdvisoryBoardMeetingDecisionTranslation())->getTable(), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedBigInteger('advisory_board_meeting_decision_id');
            $table->foreign('advisory_board_meeting_decision_id')
                ->references('id')
                ->on((new AdvisoryBoardMeetingDecision())->getTable())
                ->onDelete('cascade');

            $table->longText('decisions')->nullable();
            $table->longText('suggestions')->nullable();
            $table->longText('other')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists((new AdvisoryBoardMeetingDecision())->getTable());
        Schema::dropIfExists((new AdvisoryBoardMeetingDecisionTranslation())->getTable());
    }
};
