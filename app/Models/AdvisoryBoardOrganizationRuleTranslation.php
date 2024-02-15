<?php

namespace App\Models;

/**
 * @property string $locale
 * @property int    $advisory_board_organization_rule_id
 * @property string $description
 */
class AdvisoryBoardOrganizationRuleTranslation extends ModelTranslatableActivityExtend
{

    public $timestamps = false;

    protected string $logName = "advisory_board_organization_rule_translations";
}
