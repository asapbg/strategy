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
        if(\App\Models\AuthorityAcceptingStrategic::get()->count()) {
            DB::statement('update authority_accepting_strategic set nomenclature_level_id = '.\App\Enums\InstitutionCategoryLevelEnum::CENTRAL->value.' where id in (1,2)');
            DB::statement('update authority_accepting_strategic set nomenclature_level_id = '.\App\Enums\InstitutionCategoryLevelEnum::AREA->value.' where id in (3)');
            DB::statement('update authority_accepting_strategic set nomenclature_level_id = '.\App\Enums\InstitutionCategoryLevelEnum::MUNICIPAL->value.' where id in (4)');
        }

        if(\App\Models\StrategicDocument::get()->count()) {
            DB::statement('update strategic_document set accept_act_institution_type_id = 217 where id in (246)');
            DB::statement('update strategic_document set accept_act_institution_type_id = 216 where id in (220)');
            DB::statement('update strategic_document set accept_act_institution_type_id = 228 where id in (236)');
            DB::statement('update strategic_document set accept_act_institution_type_id = 253 where id in (270,280,283,305)');
            DB::statement('update strategic_document set accept_act_institution_type_id = 249 where id in (262,263,279,309)');
            DB::statement('update strategic_document set accept_act_institution_type_id = 232 where id in (310)');
            DB::statement('update strategic_document set accept_act_institution_type_id = 212 where id in (213,214,215,227,229,231,234,235,238,239,240,241,242,243,244,245,250,251,252,256,257,258,260,261,264,265,266,267,268,269,271,272,273,274,275,276,277,278,281,282,284,285,286,287,288,289,290,291,292,293,294,295,296,297,298,299,300.301,302,303,304,306,311,312)');
            DB::statement('update strategic_document set accept_act_institution_type_id = 254 where id in (308)');
            DB::statement('update strategic_document set accept_act_institution_type_id = 230 where id in (237,248,307)');
            DB::statement('update strategic_document set accept_act_institution_type_id = 221 where id in (222, 223,224,255,226)');

        }

        if(\App\Models\AuthorityAcceptingStrategic::get()->count()) {
            //delete duplicated nomenclatures
            DB::statement('update authority_accepting_strategic set deleted_at = \''.date('Y-m-d H:s:i').'\' where id in (220, 236,270,280,283,305, 262,263,279,309, 310, 213,214,215,227,229,231,234,235,238,239,240,241,242,243,244,245,250,251,252,256,257,258,260,261,264,265,266,267,268,269,271,272,273,274,275,276,277,278,281,282,284,285,286,287,288,289,290,291,292,293,294,295,296,297,298,299,300.301,302,303,304,306,311,312,308, 237,248,307,222, 223,224,255,226)');
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
