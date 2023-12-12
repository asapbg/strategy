<?php

use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardRegulatoryFramework;
use App\Models\AdvisoryBoardRegulatoryFrameworkTranslation;
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
        Schema::create((new AdvisoryBoardRegulatoryFramework())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('advisory_board_id');
            $table->foreign('advisory_board_id')->references('id')->on((new AdvisoryBoard())->getTable())->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create((new AdvisoryBoardRegulatoryFrameworkTranslation())->getTable(), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedBigInteger('advisory_board_regulatory_framework_id');
            $table->unique(['advisory_board_regulatory_framework_id', 'locale'], 'unique_advisory_board_regulatory_framework_translations');
            $table->foreign('advisory_board_regulatory_framework_id')
                ->references('id')
                ->on((new AdvisoryBoardRegulatoryFramework())->getTable())
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
        Schema::dropIfExists((new AdvisoryBoardRegulatoryFramework())->getTable());
        Schema::dropIfExists((new AdvisoryBoardRegulatoryFrameworkTranslation())->getTable());
    }
};
