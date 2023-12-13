<?php

use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardCustom;
use App\Models\AdvisoryBoardCustomTranslation;
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
        Schema::create((new AdvisoryBoardCustom())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('advisory_board_id');
            $table->foreign('advisory_board_id')->references('id')->on((new AdvisoryBoard())->getTable());
            $table->smallInteger('order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create((new AdvisoryBoardCustomTranslation())->getTable(), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedBigInteger('advisory_board_custom_id');
            $table->unique(['advisory_board_custom_id', 'locale'], 'unique_advisory_board_custom_translations');
            $table->foreign('advisory_board_custom_id')
                ->references('id')
                ->on((new AdvisoryBoardCustom())->getTable())
                ->onDelete('cascade');

            $table->string('title');
            $table->longText('body')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists((new AdvisoryBoardCustom())->getTable());
        Schema::dropIfExists((new AdvisoryBoardCustomTranslation())->getTable());
    }
};
