<?php

use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardSecretariat;
use App\Models\AdvisoryBoardSecretariatTranslation;
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
        Schema::create((new AdvisoryBoardSecretariat())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('advisory_board_id');
            $table->foreign('advisory_board_id')->references('id')->on((new AdvisoryBoard())->getTable());
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create((new AdvisoryBoardSecretariatTranslation())->getTable(), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedBigInteger('advisory_board_secretariat_id');
            $table->unique(['advisory_board_secretariat_id', 'locale'], 'unique_advisory_board_secretariat_translations');
            $table->foreign('advisory_board_secretariat_id')
                ->references('id')
                ->on((new AdvisoryBoardSecretariat())->getTable())
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
        Schema::dropIfExists((new AdvisoryBoardSecretariat())->getTable());
        Schema::dropIfExists((new AdvisoryBoardSecretariatTranslation())->getTable());
    }
};
