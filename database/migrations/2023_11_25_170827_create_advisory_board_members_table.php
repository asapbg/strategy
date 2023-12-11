<?php

use App\Enums\AdvisoryTypeEnum;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardMember;
use App\Models\AdvisoryBoardMemberTranslation;
use App\Models\AdvisoryChairmanType;
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
        Schema::create((new AdvisoryBoardMember())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('advisory_board_id');
            $table->enum('advisory_type_id', AdvisoryTypeEnum::values())->default(AdvisoryTypeEnum::MEMBER->value);
            $table->unsignedBigInteger('advisory_chairman_type_id');
            $table->string('email')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('advisory_board_id')->references('id')->on((new AdvisoryBoard())->getTable())->onDelete('cascade');
            $table->foreign('advisory_chairman_type_id')->references('id')->on((new AdvisoryChairmanType())->getTable())->onDelete('cascade');
        });

        Schema::create((new AdvisoryBoardMemberTranslation())->getTable(), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedInteger('advisory_board_member_id');
            $table->unique(['advisory_board_member_id', 'locale']);
            $table->foreign('advisory_board_member_id')
                ->references('id')
                ->on((new AdvisoryBoardMember())->getTable())
                ->onDelete('cascade');

            $table->string('member_name');
            $table->text('member_job')->nullable();
            $table->longText('member_notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists((new AdvisoryBoardMember())->getTable());
        Schema::dropIfExists((new AdvisoryBoardMemberTranslation())->getTable());
    }
};
