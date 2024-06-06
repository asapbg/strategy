<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'The :attribute must be accepted.',
    'accepted_if' => 'The :attribute must be accepted when :other is :value.',
    'active_url' => 'The :attribute is not a valid URL.',
    'after' => 'The :attribute must be a date after :date.',
    'after_or_equal' => 'The :attribute must be a date after or equal to :date.',
    'alpha' => 'The :attribute must only contain letters.',
    'alpha_dash' => 'The :attribute must only contain letters, numbers, dashes and underscores.',
    'alpha_num' => 'The :attribute must only contain letters and numbers.',
    'array' => 'The :attribute must be an array.',
    'before' => 'The :attribute must be a date before :date.',
    'before_or_equal' => 'The :attribute must be a date before or equal to :date.',
    'between' => [
        'array' => 'The :attribute must have between :min and :max items.',
        'file' => 'The :attribute must be between :min and :max kilobytes.',
        'numeric' => 'The :attribute must be between :min and :max.',
        'string' => 'The :attribute must be between :min and :max characters.',
    ],
    'boolean' => 'The :attribute field must be true or false.',
    'confirmed' => 'The :attribute confirmation does not match.',
    'current_password' => 'The password is incorrect.',
    'date' => 'The :attribute is not a valid date.',
    'date_equals' => 'The :attribute must be a date equal to :date.',
    'date_format' => 'The :attribute does not match the format :format.',
    'declined' => 'The :attribute must be declined.',
    'declined_if' => 'The :attribute must be declined when :other is :value.',
    'different' => 'The :attribute and :other must be different.',
    'digits' => 'The :attribute must be :digits digits.',
    'digits_between' => 'The :attribute must be between :min and :max digits.',
    'dimensions' => 'The :attribute has invalid image dimensions.',
    'distinct' => 'The :attribute field has a duplicate value.',
    'email' => 'The :attribute must be a valid email address.',
    'ends_with' => 'The :attribute must end with one of the following: :values.',
    'enum' => 'The selected :attribute is invalid.',
    'exists' => 'The selected :attribute is invalid.',
    'file' => 'The :attribute must be a file.',
    'filled' => 'The :attribute field must have a value.',
    'gt' => [
        'array' => 'The :attribute must have more than :value items.',
        'file' => 'The :attribute must be greater than :value kilobytes.',
        'numeric' => 'The :attribute must be greater than :value.',
        'string' => 'The :attribute must be greater than :value characters.',
    ],
    'gte' => [
        'array' => 'The :attribute must have :value items or more.',
        'file' => 'The :attribute must be greater than or equal to :value kilobytes.',
        'numeric' => 'The :attribute must be greater than or equal to :value.',
        'string' => 'The :attribute must be greater than or equal to :value characters.',
    ],
    'image' => 'The :attribute must be an image.',
    'in' => 'The selected :attribute is invalid.',
    'in_array' => 'The :attribute field does not exist in :other.',
    'integer' => 'The :attribute must be an integer.',
    'ip' => 'The :attribute must be a valid IP address.',
    'ipv4' => 'The :attribute must be a valid IPv4 address.',
    'ipv6' => 'The :attribute must be a valid IPv6 address.',
    'json' => 'The :attribute must be a valid JSON string.',
    'lt' => [
        'array' => 'The :attribute must have less than :value items.',
        'file' => 'The :attribute must be less than :value kilobytes.',
        'numeric' => 'The :attribute must be less than :value.',
        'string' => 'The :attribute must be less than :value characters.',
    ],
    'lte' => [
        'array' => 'The :attribute must not have more than :value items.',
        'file' => 'The :attribute must be less than or equal to :value kilobytes.',
        'numeric' => 'The :attribute must be less than or equal to :value.',
        'string' => 'The :attribute must be less than or equal to :value characters.',
    ],
    'mac_address' => 'The :attribute must be a valid MAC address.',
    'max' => [
        'array' => 'The :attribute must not have more than :max items.',
        'file' => 'The :attribute must not be greater than :max kilobytes.',
        'numeric' => 'The :attribute must not be greater than :max.',
        'string' => 'The :attribute must not be greater than :max characters.',
    ],
    'mimes' => 'The :attribute must be a file of type: :values.',
    'mimetypes' => 'The :attribute must be a file of type: :values.',
    'min' => [
        'array' => 'The :attribute must have at least :min items.',
        'file' => 'The :attribute must be at least :min kilobytes.',
        'numeric' => 'The :attribute must be at least :min.',
        'string' => 'The :attribute must be at least :min characters.',
    ],
    'multiple_of' => 'The :attribute must be a multiple of :value.',
    'not_in' => 'The selected :attribute is invalid.',
    'not_regex' => 'The :attribute format is invalid.',
    'numeric' => 'The :attribute must be a number.',
    'password' => 'The password is incorrect.',
    'present' => 'The :attribute field must be present.',
    'prohibited' => 'The :attribute field is prohibited.',
    'prohibited_if' => 'The :attribute field is prohibited when :other is :value.',
    'prohibited_unless' => 'The :attribute field is prohibited unless :other is in :values.',
    'prohibits' => 'The :attribute field prohibits :other from being present.',
    'regex' => 'The :attribute format is invalid.',
    'required' => 'The :attribute field is required.',
    'required_array_keys' => 'The :attribute field must contain entries for: :values.',
    'required_if' => 'The :attribute field is required when :other is :value.',
    'required_unless' => 'The :attribute field is required unless :other is in :values.',
    'required_with' => 'The :attribute field is required when :values is present.',
    'required_with_all' => 'The :attribute field is required when :values are present.',
    'required_without' => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same' => 'The :attribute and :other must match.',
    'size' => [
        'array' => 'The :attribute must contain :size items.',
        'file' => 'The :attribute must be :size kilobytes.',
        'numeric' => 'The :attribute must be :size.',
        'string' => 'The :attribute must be :size characters.',
    ],
    'starts_with' => 'The :attribute must start with one of the following: :values.',
    'string' => 'The :attribute must be a string.',
    'timezone' => 'The :attribute must be a valid timezone.',
    'unique' => 'The :attribute has already been taken.',
    'uploaded' => 'The :attribute failed to upload.',
    'url' => 'The :attribute must be a valid URL.',
    'uuid' => 'The :attribute must be a valid UUID.',
    'date_cross_program'        => 'There is another active program in period :start - :end',
    'program_valid_period'        => 'The program period must not have passed',
    'unique_year_work_program'        => 'A work program for the specified year already exists',
    'multicriteria_weight_sum'        => 'The sum of the \'weights\' must be 100',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
        'roles' => [
            'required' => 'You must select at least one role',
        ],
        'contractor_name.bg.storage_location_id' => [
            'required_with' => 'When you have selected a sector you must also select a warehouse',
        ],
        'password' => [
            'Illuminate\Validation\Rules\Password' => 'Password must contain',
        ],
        'col.*' => [
            'required' => 'The field is required'
        ],
        'val.*' => [
            'required' => 'The field is required',
            'array' => 'Select at least one element',
        ],
        'new_val_col.*' => [
            'required' => 'The field is required',
            'array' => 'Select at least one element',
        ],

        'new_val.*' => [
            'required' => 'The field is required',
        ],
        'name.*' => [
            'required' => 'The field is required'
        ],
        'email.*' => [
            'required' => 'The field is required'
        ],
        'pris_unique_doc_num' => 'A document with this category and number already exists in the specified year.',
        'connectIds' => [
            'required' => 'Select at least one document',
        ],
        'a' => [
            'required' => 'Choose at least one answer'
        ],
        'problem_to_solve.*' => [
            'required' => 'The field is required'
        ],
        'goals.*' => [
            'required' => 'The field is required'
        ],
        'interested_parties.*' => [
            'required' => 'The field is required'
        ],
        'variants.*.*.description' => [
            'required' => 'The field is required'
        ],
        'variants.*.*.positive_impact' => [
            'required' => 'The field is required'
        ],
        'variants.*.*.negative_impact' => [
            'required' => 'The field is required'
        ],
        'variants.*.*.small_mid_impact' => [
            'required' => 'The field is required'
        ],
        'variants.*.*.admin_weight' => [
            'required' => 'The field is required'
        ],
        'comparison.*.*.*.*' => [
            'required' => 'The field is required'
        ],
        'chosen_variants.*' => [
            'required' => 'The field is required'
        ],
        'change_admin_weight_text' => [
            'required_if' => 'Field is required'
        ],
        'affects_regulatory_acts_text' => [
            'required_if' => 'Field is required'
        ],
        'affects_registry_text' => [
            'required_if' => 'Field is required'
        ],
        'not_conducted_consultations_text' => [
            'required_if' => 'Field is required'
        ],
        'is from eu text' => [
            'required_if' => 'Field is required'
        ],
        'potential_risks' => [
            'required' => 'The field is required'
        ],
        'fields.*' => [
            'required' => 'The field is required'
        ],
        'variant_simple.*.*' => [
            'required' => 'The field is required'
        ],
        'variants.*.*.risks' => [
            'required' => 'The field is required'
        ],
        'expenses.*.*.expenses' => [
            'required' => 'The field is required'
        ],
        'expenses.*.*.benefits' => [
            'required' => 'The field is required'
        ],
        'variants.*.*.economic_impacts' => [
            'required' => 'The field is required'
        ],
        'variants.*.*.social_impact' => [
            'required' => 'The field is required'
        ],
        'variants.*.*.ecologic_impact' => [
            'required' => 'The field is required'
        ],
        'variants.*.*.specific_impact_1' => [
            'required' => 'The field is required'
        ],
        'variants.*.*.specific_impact_2' => [
            'required' => 'The field is required'
        ],
        'changes.*.problem_to_solve.*' => [
            'required' => 'The field is required'
        ],
        'changes.*.resources_used' => [
            'required' => 'The field is required'
        ],
        'changes.*.achieved_results' => [
            'required' => 'The field is required'
        ],
        'changes.*.direct_effects' => [
            'required' => 'The field is required'
        ],
        'changes.*.impacts' => [
            'required' => 'The field is required'
        ],
        'changes.*.links_regulatory_acts' => [
            'required' => 'The field is required'
        ],
        'changes.*.main_highlights' => [
            'required' => 'The field is required'
        ],
        'changes.*.results_court' => [
            'required' => 'The field is required'
        ],
        'changes.*.criteria1' => [
            'required' => 'The field is required'
        ],
        'changes.*.criteria2' => [
            'required' => 'The field is required'
        ],
        'changes.*.criteria3' => [
            'required' => 'The field is required'
        ],
        'changes.*.criteria4' => [
            'required' => 'The field is required'
        ],
        'changes.*.criteria5' => [
            'required' => 'The field is required'
        ],
        'changes.*.questions.*' => [
            'required' => 'The field is required'
        ],
        'collected_data.*.interested_parties' => [
            'required' => 'The field is required'
        ],
        'collected_data.*.consultations' => [
            'required' => 'The field is required'
        ],
        'collected_data.*.sources' => [
            'required' => 'The field is required'
        ],
        'analysis.*.answers_info' => [
            'required' => 'The field is required'
        ],
        'impact.*.conclusion' => [
            'required' => 'The field is required'
        ],
        'is_from_eu_text' => [
            'required_if' => 'The field is mandatory when the adoption of the normative act derives from the law of the European Union',
        ],
        'items.*' => [
            'required' => 'The field is required',
            'min' => 'Field is required',
        ],
        'hours.*' => [
            'required' => 'The field is required',
            'min' => 'Minimum value is 1',
        ],
        'firms.*' => [
            'required' => 'The field is required',
            'min' => 'Minimum value is 1',
        ],
        'per_year.*' => [
            'required' => 'The field is required',
            'min' => 'Minimum value is 1',
        ],
        'salary.*' => [
            'required' => 'The field is required',
            'gt' => 'Invalid value',
        ],
        'incoming.*' => [
            'required' => 'Invalid value (enter 0 or greater than 0)',
            'min' => 'Invalid value (enter 0 or greater than 0)',
        ],
        'costs.*' => [
            'required' => 'Invalid value (enter 0 or greater than 0)',
            'min' => 'Invalid value (enter 0 or greater than 0)',
        ],
        'investment_costs' => [
            'required' => 'Invalid value (enter 0 or greater than 0)',
            'min' => 'Invalid value (enter 0 or greater than 0)',
        ],
        'diskont' => [
            'required' => 'Invalid value (enter value greater than 0)',
            'gt' => 'Invalid value (enter value greater than 0)',
        ],
        'weight.*' => [
            'required' => 'Invalid value (enter value greater than 0)',
            'gt' => 'Invalid value (enter value greater than 0)',
            'numeric' => 'Field must be a number',
        ],
        'evaluation.*' => [
            'required' => 'The field is required'
        ],
        'evaluation.*.*' => [
            'gte' => 'The field must be greater than or equal to :value.',
            'lte' => 'Field must be less than or equal to :value.',
        ],
        'criteria.*' => [
            'required' => 'The field is required'
        ],
        'variants.*' => [
            'required' => 'The field is required'
        ],
        'member_ord.*' => [
            'required' => 'The field is required',
            'gt' => 'Invalid value (enter value greater than 0)',
        ],
        'document_date_expiring' => [
            'required_without' => 'The field is required when the expiration date is not unlimited.'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        // site part

        'name_organization_names' => 'Names/Name of Organization',
        'email_address' => 'Email address',
        'legislative_initiative_name' => 'Legislative initiative name',

        // end site part

        'analysis_types_text' => 'Analysis Type',
        'complexity' => 'Complexity',
        'roles' => 'Role',
        'groups' => 'Group',
        'status' => 'Status',
        'alias' => 'Alias',
        'name' => 'Name',
        'display_name' => 'Name',
        'contractor_name_bg' => 'Name of contracting authority',
        'executor_name_bg' => 'Name of executor',
        'contract_subject_bg' => 'Subject of the contract',
        'services_description_bg' => 'Description of services provided',
        'username' => 'User',
        'email' => 'Email mail',
        'first_name' => 'First Name',
        'middle_name' => 'Surname',
        'last_name' => 'Last Name',
        'victim_egn' => 'EGN',
        'password' => 'Password',
        'password_confirm' => 'Confirm Password',
        'password_confirmation' => 'Confirm Password',
        'must_change_password' => 'Send an automatic email to the user with a link to enter their password themselves',
        'investigation_number' => 'Case No',
        'investigation_year' => 'Filing Year',
        'assignor_number' => 'Assignor letter no',
        'assignor_date' => 'Assignor letter date',
        'city_id' => 'City',
        'court_id' => 'Court',
        'police_office_id' => 'MIA',
        'prosecutor_office_id' => 'Prosecution Office',
        'other_id' => 'Other Employer',
        'city' => 'City',
        'country' => 'Country',
        'address' => 'Address',
        'phone' => 'Phone',
        'channels' => 'Communication Resources',
        'age' => 'Age',
        'sex' => 'Gender',
        'gender' => 'Sex',
        'day' => 'Day',
        'month' => 'Month',
        'year' => 'Year',
        'hour' => 'Hour',
        'minute' => 'Minute',
        'second' => 'Second',
        'title' => 'Title',
        'title_bg' => 'Title (BG)',
        'title_en' => 'Title (EN)',
        'content' => 'Content',
        'description' => 'Description',
        'description_bg' => 'Description (BG)',
        'description_en' => 'Description (EN)',
        'excerpt' => 'Excerpt',
        'date' => 'Date',
        'time' => 'Time',
        'available' => 'Available',
        'size' => 'Size',
        'contractor_name.bg' => 'Name of contracting authority',
        'executor_name.bg' => 'Name of executor',
        'contract_subject.bg' => 'Subject of the contract',
        'services_description.bg' => 'Description of the services provided',
        'price' => 'Contract Price',
        'recaptcha_response_field' => 'Recaptcha',
        'subject' => 'Title',
        'message' => 'Message',
        'rememberme' => 'Remember me',
        'role' => 'Role',
        'created_at' => 'Created Date',
        'updated_at' => 'Last Modified',
        'is_approved' => 'Approved',
        'type' => 'Type',
        'visibility' => 'Visibility',
        'image' => 'Image',
        'notes' => 'Notes',
        'color' => 'Color',
        'number' => 'Number',
        'count' => 'Number',
        'start_time' => 'Start Time',
        'text' => 'Text',
        'begin_date' => 'start date',
        'model' => 'Model',
        'open_from' => 'Open Date',
        'open_to' => 'Close Date',
        'short_term_reason_bg' => 'Short term reason (BG)',
        'short_term_reason_en' => 'Short term reason (EN)',
        'responsible_unit_bg' => 'Responsible unit (BG)',
        'responsible_unit_en' => 'Responsible Unit (EN)',
        'active' => 'Active',
        'deleted' => 'Deleted',
        'effective_from' => 'Effective from',
        'effective_to' => 'Effective until',
        'category' => 'Category',
        'event_date' => 'Event Date',
        'external_link' => 'External Link',
        'external_link_url' => 'External Link URL',
        'highlighted_news' => 'Highlighted News',
        'show_news_home' => 'Show Home',
        'is_archived' => 'Record is archived',
        'start' => 'Start',
        'end' => 'End',
        'highlighted_publication' => 'Highlighted Publication',
        'element_type' => 'Element Type',
        'estimation_type' => 'Type of self-estimation',
        'entity_type' => 'Type of Person',
        'eik' => 'EIK',
        'contract_date' => 'Contract Date',
        'contractor' => 'Appointer',
        'payment' => 'Compensation for services rendered in BGN',
        'vat' => 'VAT',
        'service_description' => 'Description of services provided',
        'reason_denied' => 'Reason for denial',
        'denied' => 'Denyed',
        'internet_address' => 'Internet Address',
        'link_category' => 'Link Category',
        'show_main_menu' => 'Show in main menu',
        'specialised_page' => 'Specialised Page',
        'css_class' => 'CSS Class',
        'subtitle' => 'Sub-title',
        'url' => 'URL',
        'date_from' => 'From date',
        'from_date' => 'From date',
        'date_to' => 'To date',
        'to_date' => 'To date',
        'objective' => 'Object of the contract',
        'executor' => 'Executor',
        'institution' => 'Institution',
        'author' => 'Author',
        'author_bg' => 'Author (BG)',
        'author_en' => 'Author (EN)',
        'highlighted_page' => 'Highlighted Page',
        'is_org' => 'Profile Type',
        'org_name' => 'Organization Name',
        'value' => 'Value',
        'institution_id' => 'Institution',
        'confirm_password' => 'Password confirmation',
        'user_type' => 'User Type',
        'slug' => 'Slug',
        'meta_title' => 'Title (meta)',
        'meta_description' => 'Description (meta)',
        'meta_keyword' => 'Keywords (meta)',
        'short_content' => 'Short Description',
        'main_img' => 'Main Image',
        'published_at' => 'Publish (date)',
        //settings admin
        'system_email' => 'Email mail (system notifications)',
        'assessment' => 'Impact Assessment',
        'opinion' => 'Opinion',
        'proposal_ways_bg' => 'Ways of providing proposals and opinions (BG)',
        'proposal_ways_en' => 'Ways of providing proposals and opinions (EN)',
        //Public consultations
        'consultation_type_id' => 'Public consultation type',
        'consultation_level_id' => 'Public consultation category (level)',
        'act_type_id' => 'Act Type',
        //polls
        'once' => 'One time filling',
        'only_registered' => 'For registered users only',
        'start_date' => 'Start Date',
        'end_date' => 'End date',
        'new_question_name' => 'Question',
        'new_answers.*' => 'Answer',
        'order_idx' => 'Order',
        'meta_title_bg' => 'Title (meta) BG',
        'meta_title_en' => 'Title (meta) EN',
        'meta_description_bg' => 'Description (meta) BG',
        'meta_description_en' => 'Description (meta) EN',
        'meta_keyword_bg' => 'Keywords (meta) BG',
        'meta_keyword_en' => 'Keywords (meta) EN',
        'short_content_bg' => 'Short description BG',
        'short_content_en' => 'Short description EN',
        'content_bg' => 'Content (BG)',
        'content_en' => 'Content (EN)',
        'name_bg' => 'Name (BG)',
        'name_en' => 'Name (EN)',
        'member_name_bg' => 'Name (BG) of member',
        'member_name_en' => 'Member Name (EN)',
        'in_footer_menu' => 'Included in menu (footer)',
        'strategic_act_number' => 'Act Number',
        'strategic_act_link' => 'Act Link',
        'link_to_monitorstat' => 'Link to Monitorstat',
        'display_name_bg' => 'Public name (BG)',
        'display_name_en' => 'Public Name (EN)',
        'display_name_main_bg' => 'Public name (BG)',
        'display_name_main_en' => 'Public Name (EN)',
        'display_name_file_edit_bg' => 'Public name (BG)',
        'display_name_file_edit_en' => 'Public Name (EN)',
        'file_info_bg' => 'Additional information (BG)',
        'file_info_en' => 'Additional information (EN)',
        'file_info_main_bg' => 'Additional information (BG)',
        'file_info_main_en' => 'Additional information (EN)',
        'file_info_file_edit_bg' => 'Additional information (BG)',
        'file_info_file_edit_en' => 'Additional information (EN)',
        'public_consultation_id' => 'Public consultation',
        'label_bg' => 'Name (BG)',
        'label_en' => 'Name (EN)',
        'in_pris' => 'Included in PRIS categories',
        'label' => 'Name',
        'doc_num' => 'Number',
        'doc_date' => 'Date',
        'protocol' => 'Protocol',
        'newspaper_number' => 'Rev. DV. (number)',
        'newspaper_year' => 'DV. (year)',
        'newspaper' => 'Rev. DV.',
        'about_bg' => 'Title/About (BG)',
        'about_en' => 'Title/About (EN)',
        'legal_reason_bg' => 'Legal reason (BG)',
        'legal_reason_en' => 'Legal Reason (EN)',
        'importer_bg' => 'Importer (BG)',
        'importer_en' => 'Importer (EN)',
        'file_bg' => 'File (BG)',
        'file_en' => 'File (EN)',
        'connect_type' => 'Change Type',
        'new_name' => 'Name',
        'new_email' => 'Email',
        'file_1_bg' => 'Impact assessment (BG)',
        'file_1_en' => 'Impact Assessment (EN)',
        'file_2_bg' => 'Opinion of the administration of the Ministerial Council on Evaluation (BG)',
        'file_2_en' => 'Opinion of the Administration of the Ministerial Council on Evaluation (EN)',
        'file_3_bg' => 'Report (BG)',
        'file_3_en' => 'Report (EN)',
        'file_4_bg' => 'Draft act (BG)',
        'file_4_en' => 'Draft Act (EN)',
        'file_5_bg' => 'Motives (BG)',
        'file_5_en' => 'Motives (EN)',
        'file_6_bg' => 'Consolidated version (BG)',
        'file_6_en' => 'Consolidated version (EN)',
        'file_7_bg' => 'Other documents (BG)',
        'file_7_en' => 'Other documents (EN)',
        'file_8_bg' => 'Reference on proposals received (BG)',
        'file_8_en' => 'Reference to Proposals Received (EN)',
        'monitorstat' => 'Link to document in Monitorstat',
        'nomenclature_level' => 'Level (nomenclature)',
        'legislative_program_id' => 'Legislative Program',
        'operational_program_id' => 'Operational Program',
        'user' => 'User',
        'field_of_action' => 'Policy Area|Policy Area',
        'target_group' => 'Target Group|Target Groups',
        'document_accepted_with' => 'Accepted with Decision No',
        'file_strategic_documents_bg' => 'File (BG)',
        'file_strategic_documents_en' => 'File (EN)',
        'advisory_name' => 'Advisory Name',
        'authority_id' => 'Type of authority to which the advice was created',
        'specific_name' => 'Specific Name',
        'act_of_creation' => 'Act of Creation',
        'meetings_per_year' => 'Regulated number of meetings per year',
        'report_at' => 'Report activity to',
        'field_of_actions_id' => 'Policy Area',
        'icon_class' => 'Icon (class)',
        'advisory_chairman_type_id' => 'Chairman Type',
        'advisory_type_id' => 'Type',
        'advisory_act_type_id' => 'Act of creation',
        'report_time' => 'Time',
        'vice_chairman' => 'Vice Chairman',
        'council_members' => 'Council Members',
        'job' => 'Position',
        'member_notes' => 'Notes and brief information',
        'next_meeting' => 'Next meeting',
        'strategic_document_level_id' => 'Strategic Document Level',
        'policy_area_id' => 'Policy Area',
        'strategic_document_type_id' => 'Strategic document type',
        'strategic_act_type_id' => 'Type of act approving strategic documents',
        'accept_act_institution_type_id' => 'Authority adopted strategic document',
        'document_date_accepted' => 'Accepted Date',
        'document_date_expiring' => 'Expiration Date',
        'date_of_meeting' => 'Date of meeting',
        'agenda' => 'Agenda',
        'decisions' => 'Decisions',
        'suggestions' => 'Suggestions',
        'other' => 'Other',
        'resolution_council_matters' => 'Resolution of the Council of Ministers',
        'state_newspaper' => 'State Newspaper',
        'effective_at' => 'Effective from',
        'redirect_to_iisda' => 'Redirect to the Integrated Information System of the State Administration (IISDA)',
        'user_id' => 'user',
        'legislative_program_row_id' => 'Bill',
        'institutions' => 'Institutions',
        'framework_description_bg' => 'Description',
        'framework_description_en' => 'Description',
        'npo_presenter' => 'NPO representative',
        'contact_person' => 'Contact Person',
        'regulatory_act' => 'Regulatory act',
        'applications' => 'Applications',
        'info_sources' => 'Information Sources',
        'status_id' => 'Status',
        'commitment_name' => 'Commitment',
        'commitment_id' => 'Existing Commitment',
        'arrangement_name' => 'Measure',
        'arrangement_id' => 'Existing Measure',
        'period_assessment' => 'Period of assessment',
        'consultations' => 'Consultations held',
        'resume' => 'Resume',
        'introduction' => 'Introduction',
        'conclusions' => 'Conclusions',
        'distribution' => 'Methods of distribution',
        'recommendations' => 'Recommendations for follow-up',
        'sources' => 'Sources',
        'ogp_area' => 'Area',
        'npo_partner_bg' => 'Partners for the implementation of the measure (BG)',
        'npo_partner_en' => 'Partners for the implementation of the measure (EN)',
        'responsible_administration_bg' => 'Lead institution/organization (BG)',
        'responsible_administration_en' => 'Lead institution/organisation (EN)',
        'responsibility' => 'Responsible Institution',
        'activity_name' => 'Activity Name',
        'investment_costs' => 'Investment costs (in BGN)',
        'diskont' => 'Annual social rate of discount (%)',
        'file' => 'File',
        'adv_board' => 'Advisory Board',
        'working_year' => 'Year',
        'member_job_bg' => 'Position (BG)',
        'member_job_en' => 'Job (EN)',
        'member_notes_bg' => 'Notes and brief information (BG)',
        'member_notes_en' => 'Notes and brief information (EN)',
        'file_name_bg' => 'Name (BG)',
        'file_name_en' => 'Name (EN)',
        'decisions_bg' => 'Decisions (BG)',
        'decisions_en' => 'Decisions (EN)',
        'suggestions_bg' => 'Suggestions (BG)',
        'suggestions_en' => 'Suggestions (EN)',
        'other_bg' => 'Other (BG)',
        'other_en' => 'Other (EN)',
        'recipient' => 'Send to',
        'establishment_description_bg' => 'Description (BG)',
        'establishment_description_en' => 'Description (EN)',
        'body_bg' => 'Content (BG)',
        'body_en' => 'Content (EN)',
        'problem_bg' => 'What is the public problem that is solved by the measure? (BG)',
        'problem_en' => 'What is the societal problem that the measure addresses? (EN)',
        'from_date_develop' => 'Accept proposals (from)',
        'to_date_develop' => 'Accept proposals (until)',
        'solving_problem_bg' => 'How will the implementation of the measure contribute to solving the problem? (BG)',
        'solving_problem_en' => 'How will the implementation of the measure contribute to solving the problem? (EN)',
        'values_initiative_bg' => 'How does the measure relate to the values of the initiative? (BG)',
        'values_initiative_en' => 'How does the measure relate to the values of the initiative? (EN)',
        'extra_info_bg' => 'Additional information (including budget for implementation of the measure) (BG)',
        'extra_info_en' => 'Additional information (including budget for the implementation of the measure) (EN)',
        'interested_org_bg' => 'Interested organizations (BG)',
        'interested_org_en' => 'Interested Organizations (EN)',
        'contact_names_bg' => 'Contact information (BG)',
        'contact_names_en' => 'Contact Information (EN)',
        'contact_positions_bg' => 'Position/Position (BG)',
        'contact_positions_en' => 'Position/Position (EN)',
        'contact_phone_email_bg' => 'Contact email and phone number (BG)',
        'contact_phone_email_en' => 'Contact email and phone (EN)',
        'evaluation_bg' => 'Self-evaluation of the administration (BG)',
        'evaluation_en' => 'Self-evaluation of the administration (EN)',
        'evaluation_status_bg' => 'Self-evaluation of the administration (status) (BG)',
        'evaluation_status_en' => 'Self-evaluation of the administration (status) (EN)',
        'new_from_date' => 'Start Date',
        'new_to_date' => 'Activity end date',
        'new_name_bg' => 'Name (BG)',
        'new_name_en' => 'Name (EN)',
        'report_title_bg' => 'Title (BG)',
        'report_title_en' => 'Title (EN)',
        'report_content_bg' => 'Content (BG)',
        'report_content_en' => 'Content (EN)',
        'required_likes' => 'Support Required',
        'ogp_arrangement_content_bg' => 'What does the measure consist of?',
        'ogp_arrangement_content_en' => 'What does the measure consist of?',
        'ord' => 'Order',
        'law_paragraph' => 'Article/paragraph/clause',
        'law_text' => 'Text of the provision',
        'legal_act_type_id' => 'Category Act',
        'a_description_bg' => 'Description (BG)',
        'a_description_en' => 'Description (EN)',
        'a_file_bg' => 'File (BG)',
        'a_file_en' => 'File (EN)',
        'notification_email' => 'Email (notifications)',
        'law_id' => 'Law',
        'act_type' => 'Type of act',
        'name_single_bg'           => 'Name in singular (BG)',
        'name_single_en'           => 'Name in singular (EN)',
        'file_description_bg'           => 'Description (BG)',
        'file_description_en'           => 'Description (EN)',
        'ord.*'           => 'Order',
        'protocol_point'           => 'Protocol point',
    ],
];
