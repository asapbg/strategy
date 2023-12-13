<?php

use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardEstablishment;
use App\Models\AdvisoryBoardEstablishmentTranslation;
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
        Schema::create((new AdvisoryBoardEstablishment())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('advisory_board_id');
            $table->foreign('advisory_board_id')->references('id')->on((new AdvisoryBoard())->getTable())->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create((new AdvisoryBoardEstablishmentTranslation())->getTable(), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedBigInteger('advisory_board_establishment_id');
            $table->unique(['advisory_board_establishment_id', 'locale'], 'unique_advisory_board_establishment_translations');
            $table->foreign('advisory_board_establishment_id')
                ->references('id')
                ->on((new AdvisoryBoardEstablishment())->getTable())
                ->onDelete('cascade');

            $table->longText('description')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists((new AdvisoryBoardEstablishment())->getTable());
        Schema::dropIfExists((new AdvisoryBoardEstablishmentTranslation())->getTable());
    }
};
