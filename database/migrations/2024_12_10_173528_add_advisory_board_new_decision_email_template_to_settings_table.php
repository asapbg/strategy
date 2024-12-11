<?php

use App\Models\Setting;
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
    public function up(): void
    {
        if (Schema::hasTable((new Setting())->getTable()) && Setting::where('name', 'advisory_board_new_decision_email_template')->doesntExist()) {
            Setting::create([
                'section'       => 'advisory_board',
                'name'          => 'advisory_board_new_decision_email_template',
                'type'          => 'summernote',
                'editable'      => 1,
                'is_required'   => 1,
                'value'         => '&lt;p&gt;Уважаеми членове на ${name},&lt;/p&gt;&lt;p&gt;Във връзка със задълженията на ${name}, ви уведомяваме за предстоящо заседание:&lt;/p&gt;&lt;p&gt;Дата на заседанието: ${date}&lt;/p&gt;'
            ]);
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
