<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $exclude_tables = [
        'pris_change_pris',
        'user_poll_option',
        'role_has_permissions',
        'model_has_permissions',
        'model_has_roles',
        'activity_log',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();

        foreach ($tables as $table_name) {
            if (in_array($table_name, $this->exclude_tables)) {
                continue;
            }
            $indexes = DB::connection()->getDoctrineSchemaManager()->listTableIndexes($table_name);
            if (!is_array($indexes)) {
                //dump("No indexes: $table_name");
                continue;
            }
            if (!isset($indexes['primary'])) {
                //dump("No primary key: $table_name");
                continue;
            }
            $columns = $indexes[ 'primary' ]->getColumns();
            if (!in_array('id', $columns)) {
                //dump("No primary id: $table_name");
                continue;
            }
            Schema::table($table_name, function (Blueprint $table) use ($table_name) {
                if (!Schema::hasColumn($table_name, 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
};
