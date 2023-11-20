<?php

use App\Enums\LegislativeInitiativeStatusesEnum;
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

        Schema::create('legislative_initiative', function (Blueprint $table) {
            $statuses = [
                LegislativeInitiativeStatusesEnum::STATUS_ACTIVE->value,
                LegislativeInitiativeStatusesEnum::STATUS_SEND->value,
                LegislativeInitiativeStatusesEnum::STATUS_CLOSED->value,
            ];

            $table->bigIncrements('id');
            $table->bigInteger('regulatory_act_id');
            $table->enum('status', $statuses)->default(LegislativeInitiativeStatusesEnum::STATUS_ACTIVE->value);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('legislative_initiative_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedInteger('legislative_initiative_id');
            $table->unique(['legislative_initiative_id', 'locale']);
            $table->foreign('legislative_initiative_id')
                ->references('id')
                ->on('legislative_initiative');

            $table->longText('description');
            $table->unsignedBigInteger('author_id');
            $table->foreign('author_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('legislative_initiative_translations');
        Schema::dropIfExists('legislative_initiative');
    }
};
