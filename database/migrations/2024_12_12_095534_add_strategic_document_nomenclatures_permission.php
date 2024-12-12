<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Permission::where('name', 'manage.strategic-documents.nomenclatures')->exists()) {
            $permission = Permission::create([
                'name' => 'manage.strategic-documents.nomenclatures',
                'display_name' => 'Права за управление на номенклатури на „Стратегически документи“',
                'guard_name' => 'web'
            ]);

            Role::whereName('moderator-strategics')->first()?->givePermissionTo($permission->name);
        }
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
