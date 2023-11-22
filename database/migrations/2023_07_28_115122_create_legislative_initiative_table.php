<?php

use App\Enums\LegislativeInitiativeStatusesEnum;
use App\Models\LegislativeInitiative;
use App\Models\User;
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

        Schema::create((new LegislativeInitiative())->getTable(), function (Blueprint $table) {
            $statuses = [
                LegislativeInitiativeStatusesEnum::STATUS_ACTIVE->value,
                LegislativeInitiativeStatusesEnum::STATUS_SEND->value,
                LegislativeInitiativeStatusesEnum::STATUS_CLOSED->value,
            ];

            $table->bigIncrements('id');
            $table->bigInteger('operational_program_id');
            $table->bigInteger('author_id');
            $table->integer('votes')->default(0);
            $table->integer('cap')->default(0);
            $table->enum('status', $statuses)->default(LegislativeInitiativeStatusesEnum::STATUS_ACTIVE->value);
            $table->longText('description');
            $table->foreign('author_id')
                ->references('id')
                ->on((new User())->getTable())
                ->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });

        // Since we moved this to the web part, we no longer need translations.

//        Schema::create('legislative_initiative_translations', function (Blueprint $table) {
//            $table->bigIncrements('id');
//            $table->string('locale')->index();
//            $table->unsignedInteger('legislative_initiative_id');
//            $table->unique(['legislative_initiative_id', 'locale']);
//            $table->foreign('legislative_initiative_id')
//                ->references('id')
//                ->on('legislative_initiative');
//
//            $table->longText('description');
//            $table->unsignedBigInteger('author_id');
//            $table->foreign('author_id')
//                ->references('id')
//                ->on('users')
//                ->onDelete('cascade');
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists((new LegislativeInitiative())->getTable());
    }
};
