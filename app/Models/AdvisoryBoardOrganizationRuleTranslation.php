<?php

namespace App\Models;

/**
 * @property string $locale
 * @property int    $advisory_board_organization_rule_id
 * @property string $description
 */
class AdvisoryBoardOrganizationRuleTranslation extends ModelTranslatableActivityExtend
{

    const MODULE_NAME = ('custom.adv_board_organization_rules_translations');
    public $timestamps = false;

    protected string $logName = "advisory_board_organization_rule_translations";

    public function getModelName()
    {
        return $this->name;
    }
}
