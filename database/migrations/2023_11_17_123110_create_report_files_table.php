<?php

use App\Models\Report;
use App\Models\ReportFile;
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
        Schema::create((new ReportFile())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('report_id');
            $table->string('origin_name');
            $table->string('type');
            $table->longText('path');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('report_id')->references('id')->on((new Report())->getTable())->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists((new ReportFile())->getTable());
    }
};
