<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (!Permission::where('name', 'manage.advisory-boards.nomenclatures')->exists()) {
            $permission = Permission::create([
                'name' => 'manage.advisory-boards.nomenclatures',
                'display_name' => 'Права за управление на номенклатури на „Консултативни съвети“',
                'guard_name' => 'web'
            ]);

            Role::whereName('moderator-advisory-boards')->first()?->givePermissionTo($permission->name);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
    }
};
