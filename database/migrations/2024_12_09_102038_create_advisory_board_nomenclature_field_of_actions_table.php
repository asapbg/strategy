<?php

use App\Models\AdvisoryBoard\AdvisoryBoardNomenclatureFieldOfAction;
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
        Schema::create((new AdvisoryBoardNomenclatureFieldOfAction())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('active')->default(1);
            $table->string('icon_class')->default('fas fa-certificate');
            $table->timestamps();
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
        Schema::dropIfExists((new AdvisoryBoardNomenclatureFieldOfAction())->getTable());
    }
};
