<?php

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
        Schema::dropIfExists('advisory_board_function_files');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::create('advisory_board_function_files', function (Blueprint $table) {
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
};
