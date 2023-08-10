<?php
return [
    'form1' => [
        'step1' => [
            'institution' => 'required',
            'regulatory_act' => 'required',
            'included_in_program' => 'required',
            'contact_person' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'problem_to_solve.*' => 'required',
            'goals.*' => 'required',
            'interested_parties.*.text' => 'required',
            'interested_parties.*.number' => 'required',
        ],
        'step2' => [
            'variants.*.*.description' => 'required',
            'variants.*.*.positive_impact' => 'required',
            'variants.*.*.negative_impact' => 'required',
            'variants.*.*.small_mid_impact' => 'required',
            'variants.*.*.admin_weight' => 'required',
            'comparison.*.*.*.*' => 'required',
        ],
    ]
];