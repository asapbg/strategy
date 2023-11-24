<?php

use App\Models\AdvisoryActType;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardTranslation;
use App\Models\AdvisoryChairmanType;
use App\Models\PolicyArea;
use App\Models\StrategicDocuments\Institution;
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
        Schema::create((new AdvisoryBoard())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('policy_area_id');
            $table->foreign('policy_area_id')->references('id')->on((new PolicyArea())->getTable())->onDelete('cascade');
            $table->unsignedBigInteger('advisory_chairman_type_id');
            $table->foreign('advisory_chairman_type_id')->references('id')->on((new AdvisoryChairmanType())->getTable())->onDelete('cascade');
            $table->unsignedBigInteger('advisory_act_type_id');
            $table->foreign('advisory_act_type_id')->references('id')->on((new AdvisoryActType())->getTable())->onDelete('cascade');
            $table->smallInteger('meetings_per_year')->nullable();
            $table->unsignedBigInteger('report_institution_id');
            $table->foreign('report_institution_id')->references('id')->on((new Institution())->getTable())->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create((new AdvisoryBoardTranslation())->getTable(), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedInteger('advisory_board_id');
            $table->unique(['advisory_board_id', 'locale']);
            $table->foreign('advisory_board_id')
                ->references('id')
                ->on((new AdvisoryBoard())->getTable())
                ->onDelete('cascade');

            $table->string('name');
            $table->string('advisory_specific_name');
            $table->string('advisory_act_specific_name');
            $table->string('report_institution_specific_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists((new AdvisoryBoard())->getTable());
    }
};
