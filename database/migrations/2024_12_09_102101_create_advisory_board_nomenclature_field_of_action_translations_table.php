<?php

use App\Models\AdvisoryBoard\AdvisoryBoardNomenclatureFieldOfAction;
use App\Models\AdvisoryBoard\AdvisoryBoardNomenclatureFieldOfActionTranslation;
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
        Schema::create((new AdvisoryBoardNomenclatureFieldOfActionTranslation())->getTable(), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedInteger('advisory_board_nomenclature_field_of_action_id');
            $table->foreign('advisory_board_nomenclature_field_of_action_id')
                ->references('id')
                ->on((new AdvisoryBoardNomenclatureFieldOfAction())->getTable());

            $table->string('name', 200);
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
        Schema::dropIfExists((new AdvisoryBoardNomenclatureFieldOfActionTranslation())->getTable());
    }
};
