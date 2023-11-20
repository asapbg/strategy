<?php

use App\Models\Report;
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
        Schema::create((new Report())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->string('name_bg');
            $table->string('name_en');
            $table->tinyInteger('consultation_type_id');
            $table->tinyInteger('field_of_action_id');
            $table->timestamp('from_date');
            $table->timestamp('to_date');
            $table->tinyInteger('active')->default(1);
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
        Schema::dropIfExists((new Report())->getTable());
    }
};
