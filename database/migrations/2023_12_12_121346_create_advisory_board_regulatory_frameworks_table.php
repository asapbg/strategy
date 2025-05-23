<?php

use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardOrganizationRule;
use App\Models\AdvisoryBoardOrganizationRuleTranslation;
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
        Schema::create((new AdvisoryBoardOrganizationRule())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('advisory_board_id');
            $table->foreign('advisory_board_id')->references('id')->on((new AdvisoryBoard())->getTable())->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create((new AdvisoryBoardOrganizationRuleTranslation())->getTable(), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedBigInteger('advisory_board_organization_rule_id');
            $table->unique(['advisory_board_organization_rule_id', 'locale'], 'unique_advisory_board_organization_rule_translations');
            $table->foreign('advisory_board_organization_rule_id')
                ->references('id')
                ->on((new AdvisoryBoardOrganizationRule())->getTable())
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
        Schema::dropIfExists((new AdvisoryBoardOrganizationRule())->getTable());
        Schema::dropIfExists((new AdvisoryBoardOrganizationRuleTranslation())->getTable());
    }
};
