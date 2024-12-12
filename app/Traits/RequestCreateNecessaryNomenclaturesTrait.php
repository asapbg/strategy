<?php

namespace App\Traits;

use App\Models\AdvisoryActType;
use App\Models\AdvisoryChairmanType;
use App\Models\AuthorityAdvisoryBoard;
use App\Services\Nomenclatures\AdvisoryActTypeBoardService;
use App\Services\Nomenclatures\AdvisoryChairmanTypeBoardService;
use App\Services\Nomenclatures\AuthorityAdvisoryBoardService;

trait RequestCreateNecessaryNomenclaturesTrait
{

    private function createNecessaryNomenclatures(): void
    {
        $selected_authority_id = $this->input('authority_id');

        // if other authority is selected, create nomenclature
        if ($selected_authority_id == AuthorityAdvisoryBoard::getOtherAuthorityId()) {
            $authorityService = new AuthorityAdvisoryBoardService();
            $authority = $authorityService->create($this->request->get('other_authority_name_bg'), $this->request->get('other_authority_name_en'));

            // Update the request with the newly created authority's ID
            $this->merge([
                'authority_id' => $authority->id,
            ]);
        }

        $selected_act_type_id = $this->input('advisory_act_type_id');

        if ($selected_act_type_id == AdvisoryActType::getOtherId()) {
            $actTypeService = new AdvisoryActTypeBoardService();
            $actType = $actTypeService->create($this->request->get('other_act_type_name_bg'), $this->request->get('other_act_type_name_en'));

            // Update the request with the newly created authority's ID
            $this->merge([
                'advisory_act_type_id' => $actType->id,
            ]);
        }

        $selected_chairman_type_id = $this->input('advisory_chairman_type_id');

        if ($selected_chairman_type_id == AdvisoryChairmanType::getOtherId()) {
            $chairmanTypeService = new AdvisoryChairmanTypeBoardService();
            $chairmanType = $chairmanTypeService->create($this->request->get('other_chairman_type_name_bg'), $this->request->get('other_chairman_type_name_en'));

            // Update the request with the newly created authority's ID
            $this->merge([
                'advisory_chairman_type_id' => $chairmanType->id,
            ]);
        }
    }
}
