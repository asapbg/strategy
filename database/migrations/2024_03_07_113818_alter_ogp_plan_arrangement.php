<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        DB::statement('ALTER TABLE ogp_plan_arrangement_translations ALTER content drop not null');
        DB::statement('ALTER TABLE ogp_plan_arrangement_translations ALTER COLUMN npo_partner TYPE TEXT');
        DB::statement('ALTER TABLE ogp_plan_arrangement_translations ALTER COLUMN content TYPE TEXT');
        DB::statement('ALTER TABLE ogp_plan_arrangement_translations ALTER COLUMN responsible_administration TYPE TEXT');
        DB::statement('ALTER TABLE ogp_plan_arrangement_translations ALTER COLUMN name TYPE character varying(2000)');

        Schema::table('ogp_plan_arrangement_translations', function (Blueprint $table){
            $table->text('problem')->nullable();
            $table->text('solving_problem')->nullable();
            $table->text('values_initiative')->nullable();
            $table->text('extra_info')->nullable();
            $table->text('interested_org')->nullable();
            $table->text('contact_names')->nullable();
            $table->text('contact_positions')->nullable();
            $table->text('contact_phone_email')->nullable();
            $table->text('evaluation')->nullable();
            $table->string('evaluation_status', 2000)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ogp_plan_arrangement_translations', function (Blueprint $table){
            $table->dropColumn('problem');
            $table->dropColumn('solving_problem');
            $table->dropColumn('values_initiative');
            $table->dropColumn('extra_info');
            $table->dropColumn('interested_org');
            $table->dropColumn('contact_names');
            $table->dropColumn('contact_positions');
            $table->dropColumn('contact_phone_email');
            $table->dropColumn('evaluation');
            $table->dropColumn('evaluation_status');
        });
    }
};
