<?php

use App\Models\FieldOfAction;
use App\Models\FieldOfActionTranslation;
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
        Schema::create((new FieldOfActionTranslation())->getTable(), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedInteger('field_of_action_id');
            $table->unique(['field_of_action_id', 'locale']);
            $table->foreign('field_of_action_id')
                ->references('id')
                ->on((new FieldOfAction())->getTable());

            $table->string('name', 200);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists((new FieldOfActionTranslation())->getTable());
    }
};
