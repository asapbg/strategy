<?php

use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardNpo;
use App\Models\AdvisoryBoardNpoTranslation;
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
        Schema::create((new AdvisoryBoardNpo())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('advisory_board_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('advisory_board_id')->references('id')->on((new AdvisoryBoard())->getTable())->onDelete('cascade');
        });

        Schema::create((new AdvisoryBoardNpoTranslation())->getTable(), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedBigInteger('advisory_board_npo_id');
            $table->unique(['advisory_board_npo_id', 'locale'], 'unique_advisory_board_npo_translations');
            $table->foreign('advisory_board_npo_id')
                ->references('id')
                ->on((new AdvisoryBoardNpo())->getTable())
                ->onDelete('cascade');

            $table->string('name')->nullable();
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
        Schema::dropIfExists((new AdvisoryBoardNpo())->getTable());
        Schema::dropIfExists((new AdvisoryBoardNpoTranslation())->getTable());
    }
};
