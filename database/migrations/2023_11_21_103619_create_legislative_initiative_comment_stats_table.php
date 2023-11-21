<?php

use App\Models\LegislativeInitiativeComment;
use App\Models\LegislativeInitiativeCommentStat;
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
        Schema::create((new LegislativeInitiativeCommentStat())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('comment_id');
            $table->unsignedBigInteger('user_id');
            $table->boolean('is_like');
            $table->timestamps();

            $table->foreign('comment_id')->references('id')->on((new LegislativeInitiativeComment())->getTable());
            $table->foreign('user_id')->references('id')->on((new User())->getTable());
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists((new LegislativeInitiativeCommentStat())->getTable());
    }
};
