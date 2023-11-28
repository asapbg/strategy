<?php

use App\Models\File;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardFunctionFile;
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
        Schema::create((new AdvisoryBoardFunctionFile())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('advisory_board_id');
            $table->unsignedBigInteger('file_id')->nullable();
            $table->string('file_name');
            $table->string('file_description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('advisory_board_id')->references('id')->on((new AdvisoryBoard())->getTable())->onDelete('cascade');
            $table->foreign('file_id')->references('id')->on((new File())->getTable())->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists((new AdvisoryBoardFunctionFile())->getTable());
    }
};
