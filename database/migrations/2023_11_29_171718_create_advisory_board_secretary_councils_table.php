<?php

use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardSecretaryCouncil;
use App\Models\AdvisoryBoardSecretaryCouncilTranslation;
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
        Schema::create((new AdvisoryBoardSecretaryCouncil())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('advisory_board_id');
            $table->foreign('advisory_board_id')->references('id')->on((new AdvisoryBoard())->getTable());
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create((new AdvisoryBoardSecretaryCouncilTranslation())->getTable(), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedBigInteger('advisory_board_secretary_council_id');
            $table->unique(['advisory_board_secretary_council_id', 'locale'], 'unique_advisory_board_secretary_council_translations');
            $table->foreign('advisory_board_secretary_council_id')
                ->references('id')
                ->on((new AdvisoryBoardSecretaryCouncil())->getTable())
                ->onDelete('cascade');

            $table->string('name');
            $table->string('job')->nullable();
            $table->string('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists((new AdvisoryBoardSecretaryCouncil())->getTable());
        Schema::dropIfExists((new AdvisoryBoardSecretaryCouncilTranslation())->getTable());
    }
};
