<?php

use App\Models\LegislativeInitiative;
use App\Models\LegislativeInitiativeComment;
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
        Schema::create((new LegislativeInitiativeComment())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('legislative_initiative_id');
            $table->longText('description');
            $table->foreign('user_id')->references('id')->on((new User())->getTable());
            $table->foreign('legislative_initiative_id')->references('id')->on((new LegislativeInitiative())->getTable());
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
        Schema::dropIfExists('legislative_initiative_comments');
    }
};
