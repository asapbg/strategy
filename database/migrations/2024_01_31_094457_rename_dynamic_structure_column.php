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
        DB::statement('update dynamic_structure_column_translations set label = \'Пореден номер в програмата\' where locale = \'bg\' and dynamic_structure_column_id = '.config('lp_op_programs.lp_ds_col_number_id'));
        DB::statement('update dynamic_structure_column_translations set label = \'Пореден номер в програмата\' where locale = \'bg\' and dynamic_structure_column_id = '.config('lp_op_programs.op_ds_col_number_id'));
        DB::statement('update dynamic_structure_column_translations set label = \'Serial number in the program\' where locale = \'en\' and dynamic_structure_column_id = '.config('lp_op_programs.lp_ds_col_number_id'));
        DB::statement('update dynamic_structure_column_translations set label = \'Serial number in the program\' where locale = \'en\' and dynamic_structure_column_id = '.config('lp_op_programs.op_ds_col_number_id'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
