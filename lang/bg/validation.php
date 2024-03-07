<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages.
    |
    */

    'username_exists' => 'Потребител с потребителско име :Username вече съществува!',
    'email_exists'    => 'Потребител с email :email вече съществува!',
    'accepted'        => 'Трябва да приемете :attribute.',
    'active_url'      => 'Полето :attribute не е валиден URL адрес.',
    'after'           => 'Полето :attribute трябва да бъде дата след :date.',
    'after_or_equal'  => 'Полето :attribute трябва да бъде дата след или равна на :date.',
    'alpha'           => 'Полето :attribute трябва да съдържа само букви.',
    'alpha_dash'      => 'Полето :attribute трябва да съдържа само букви, цифри, долна черта и тире.',
    'alpha_num'       => 'Полето :attribute трябва да съдържа само букви и цифри.',
    'array'           => 'Полето :attribute трябва да бъде масив.',
    'before'          => 'Полето :attribute трябва да бъде дата преди :date.',
    'before_or_equal' => 'Полето :attribute трябва да бъде дата преди или равна на :date.',
    'between'         => [
        'numeric' => 'Полето :attribute трябва да бъде между :min и :max.',
        'file'    => 'Полето :attribute трябва да бъде между :min и :max килобайта.',
        'string'  => 'Полето :attribute трябва да бъде между :min и :max знака.',
        'array'   => 'Полето :attribute трябва да има между :min - :max елемента.',
    ],
    'boolean'        => 'Полето :attribute трябва да съдържа Да или Не',
    'confirmed'      => 'Полето :attribute не е потвърдено.',
    'date'           => 'Полето :attribute не е валидна дата.',
    'date_equals'    => ':attribute трябва да бъде дата, еднаква с :date.',
    'date_format'    => 'Полето :attribute не е във формат :format.',
    'different'      => 'Полетата :attribute и :other трябва да са различни.',
    'digits'         => 'Полето :attribute трябва да има :digits цифри.',
    'digits_between' => 'Полето :attribute трябва да има между :min и :max цифри.',
    'dimensions'     => 'Невалидни размери за снимка :attribute.',
    'distinct'       => 'Данните в полето :attribute се дублират.',
    'email'          => 'Полето :attribute е в невалиден формат.',
    'ends_with'      => 'The :attribute must end with one of the following: :values.',
    'exists'         => 'Избранато поле :attribute вече съществува.',
    'file'           => 'Полето :attribute трябва да бъде файл.',
    'filled'         => 'Полето :attribute е задължително.',
    'gt'             => [
        'numeric' => ':attribute трябва да бъде по-голямa от :value.',
        'file'    => ':attribute трябва да бъде по-голяма от :valueкилобайта.',
        'string'  => ':attribute трябва да бъде по-голяма от :value знака.',
        'array'   => ':attribute трябва да разполага с повече от :value елемента.',
    ],
    'gte' => [
        'numeric' => ':attribute трябва да бъде по-голяма от или равна на :value.',
        'file'    => ':attribute трябва да бъде по-голяма от или равна на :value килобайта.',
        'string'  => ':attribute трябва да бъде по-голяма от или равна на :valueзнака.',
        'array'   => ':attribute трябва да разполага с :value елемента или повече.',
    ],
    'image'    => 'Полето :attribute трябва да бъде изображение.',
    'in'       => 'Избраното поле :attribute е невалидно.',
    'in_array' => 'Полето :attribute не съществува в :other.',
    'integer'  => 'Полето :attribute трябва да бъде цяло число.',
    'ip'       => 'Полето :attribute трябва да бъде IP адрес.',
    'ipv4'     => 'Полето :attribute трябва да бъде IPv4 адрес.',
    'ipv6'     => 'Полето :attribute трябва да бъде IPv6 адрес.',
    'json'     => 'Полето :attribute трябва да бъде JSON низ.',
    'lt'       => [
        'numeric' => ':attribute трябва да бъде по-малка от :value.',
        'file'    => ':attribute трябва да бъде по-малка от :value килобайта.',
        'string'  => ':attribute трябва да бъде по-малка от :value знака.',
        'array'   => ':attribute трябва да разполага с по-малко от :value елемента.',
    ],
    'lte' => [
        'numeric' => ':attribute трябва да бъде по-малка от или равна на :value.',
        'file'    => ':attribute трябва да бъде по-малка от или равна на :value килобайта.',
        'string'  => ':attribute трябва да бъде по-малка от или равна на :value знака.',
        'array'   => ':attribute не трябва да разполага с повече от :value елемента.',
    ],
    'max' => [
        'numeric' => 'Полето :attribute трябва да бъде по-малко от :max.',
        'file'    => 'Полето :attribute трябва да бъде по-малко от :max килобайта.',
        'string'  => 'Полето :attribute трябва да бъде по-малко от :max знака.',
        'array'   => 'Полето :attribute трябва да има по-малко от :max елемента.',
    ],
    'mimes'     => 'Полето :attribute трябва да бъде файл от тип: :values.',
    'mimetypes' => 'Полето :attribute трябва да бъде файл от тип: :values.',
    'min'       => [
        'numeric' => 'Полето :attribute трябва да бъде минимум :min.',
        'file'    => 'Полето :attribute трябва да бъде минимум :min килобайта.',
        'string'  => 'Полето :attribute трябва да бъде минимум :min знака.',
        'array'   => 'Полето :attribute трябва има минимум :min елемента.',
    ],
    'not_in'               => 'Избраното поле :attribute е невалидно.',
    'not_regex'            => 'Форматът на :attribute е невалиден.',
    'numeric'              => 'Полето :attribute трябва да бъде число.',
    'present'              => 'Полето :attribute трябва да съествува.',
    'regex'                => 'Полето :attribute е в невалиден формат.',
    'required'             => 'Полето :attribute е задължително.',
    'required_if'          => 'Полето :attribute се изисква, когато :other е :value.',
    'required_unless'      => 'Полето :attribute се изисква, освен ако :other не е в :values.',
    'required_with'        => 'Полето :attribute се изисква, когато :values има стойност.',
    'required_with_all'    => 'Полето :attribute е задължително, когато :values имат стойност.',
    'required_without'     => 'Полето :attribute се изисква, когато :values няма стойност.',
    'required_without_all' => 'Полето :attribute се изисква, когато никое от полетата :values няма стойност.',
    'same'                 => 'Полетата :attribute и :other трябва да съвпадат.',
    'size'                 => [
        'numeric' => 'Полето :attribute трябва да бъде :size.',
        'file'    => 'Полето :attribute трябва да бъде :size килобайта.',
        'string'  => 'Полето :attribute трябва да бъде :size знака.',
        'array'   => 'Полето :attribute трябва да има :size елемента.',
    ],
    'starts_with' => ':attribute трябва да започва с едно от следните: :values.',
    'string'      => 'Полето :attribute трябва да бъде знаков низ.',
    'timezone'    => 'Полето :attribute трябва да съдържа валидна часова зона.',
    'unique'      => 'Полето :attribute вече съществува.',
    'uploaded'    => 'Неуспешно качване на :attribute.',
    'url'         => 'Полето :attribute е в невалиден формат.',
    'uuid'        => ':attribute трябва да бъде валиден UUID.',
    'date_cross_program'        => 'В периода :start - :end има друга активна програма',
    'program_valid_period'        => 'Периодът на програмата не трябва да е отминал',
    'unique_year_work_program'        => 'Вече съществува работна програма за посочената година',

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
            'required' => 'Трябва да изберете поне една роля',
        ],
        'contractor_name.bg.storage_location_id' => [
            'required_with' => 'Когато сте избрали сектор трябва да изберете и склад',
        ],
        'password' => [
            'Illuminate\Validation\Rules\Password' => 'Паролата трябва да съдържа',
        ],
        'col.*' => [
            'required' => 'Полето е задължително'
        ],
        'val.*' => [
            'required' => 'Полето е задължително',
             'array' => 'Изберете поне един елемент',
        ],
        'new_val_col.*' => [
            'required' => 'Полето е задължително',
            'array' => 'Изберете поне един елемент',
        ],

        'new_val.*' => [
            'required' => 'Полето е задължително',
        ],
        'name.*' => [
            'required' => 'Полето е задължително'
        ],
        'email.*' => [
            'required' => 'Полето е задължително'
        ],
        'pris_unique_doc_num' => 'Вече съществува документ с тази категория и номер в посочената година.',
        'connectIds' => [
            'required' => 'Изберете поне един документ',
        ],
        'a' => [
            'required' => 'Изберете поне един отговор'
        ],
        'problem_to_solve.*' => [
            'required' => 'Полето е задължително'
        ],
        'goals.*' => [
            'required' => 'Полето е задължително'
        ],
        'interested_parties.*' => [
            'required' => 'Полето е задължително'
        ],
        'variants.*.*.description' => [
            'required' => 'Полето е задължително'
        ],
        'variants.*.*.positive_impact' => [
            'required' => 'Полето е задължително'
        ],
        'variants.*.*.negative_impact' => [
            'required' => 'Полето е задължително'
        ],
        'variants.*.*.small_mid_impact' => [
            'required' => 'Полето е задължително'
        ],
        'variants.*.*.admin_weight' => [
            'required' => 'Полето е задължително'
        ],
        'comparison.*.*.*.*' => [
            'required' => 'Полето е задължително'
        ],
        'chosen_variants.*' => [
            'required' => 'Полето е задължително'
        ],
        'change_admin_weight_text' => [
            'required_if' => 'Полето е задължително'
        ],
        'affects_regulatory_acts_text' => [
            'required_if' => 'Полето е задължително'
        ],
        'affects_registry_text' => [
            'required_if' => 'Полето е задължително'
        ],
        'not_conducted_consultations_text' => [
            'required_if' => 'Полето е задължително'
        ],
        'is from eu text ' => [
            'required_if' => 'Полето е задължително'
        ],
        'potential_risks' => [
            'required' => 'Полето е задължително'
        ],
        'fields.*' => [
            'required' => 'Полето е задължително'
        ],
        'variant_simple.*.*' => [
            'required' => 'Полето е задължително'
        ],
        'variants.*.*.risks' => [
            'required' => 'Полето е задължително'
        ],
        'expenses.*.*.expenses' => [
            'required' => 'Полето е задължително'
        ],
        'expenses.*.*.benefits' => [
            'required' => 'Полето е задължително'
        ],
        'variants.*.*.economic_impacts' => [
            'required' => 'Полето е задължително'
        ],
        'variants.*.*.social_impact' => [
            'required' => 'Полето е задължително'
        ],
        'variants.*.*.ecologic_impact' => [
            'required' => 'Полето е задължително'
        ],
        'variants.*.*.specific_impact_1' => [
            'required' => 'Полето е задължително'
        ],
        'variants.*.*.specific_impact_2' => [
            'required' => 'Полето е задължително'
        ],
        'changes.*.problem_to_solve.*' => [
            'required' => 'Полето е задължително'
        ],
        'changes.*.resources_used' => [
            'required' => 'Полето е задължително'
        ],
        'changes.*.achieved_results' => [
            'required' => 'Полето е задължително'
        ],
        'changes.*.direct_effects' => [
            'required' => 'Полето е задължително'
        ],
        'changes.*.impacts' => [
            'required' => 'Полето е задължително'
        ],
        'changes.*.links_regulatory_acts' => [
            'required' => 'Полето е задължително'
        ],
        'changes.*.main_highlights' => [
            'required' => 'Полето е задължително'
        ],
        'changes.*.results_court' => [
            'required' => 'Полето е задължително'
        ],
        'changes.*.criteria1' => [
            'required' => 'Полето е задължително'
        ],
        'changes.*.criteria2' => [
            'required' => 'Полето е задължително'
        ],
        'changes.*.criteria3' => [
            'required' => 'Полето е задължително'
        ],
        'changes.*.criteria4' => [
            'required' => 'Полето е задължително'
        ],
        'changes.*.criteria5' => [
            'required' => 'Полето е задължително'
        ],
        'changes.*.questions.*' => [
            'required' => 'Полето е задължително'
        ],
        'collected_data.*.interested_parties' => [
            'required' => 'Полето е задължително'
        ],
        'collected_data.*.consultations' => [
            'required' => 'Полето е задължително'
        ],
        'collected_data.*.sources' => [
            'required' => 'Полето е задължително'
        ],
        'analysis.*.answers_info' => [
            'required' => 'Полето е задължително'
        ],
        'impact.*.conclusion' => [
            'required' => 'Полето е задължително'
        ],
        'is_from_eu_text' => [
            'required_if' => 'Полето е задължително когато приемането на нормативния акт произтича от правото на Европейския съюз',
        ],
        'items.*' => [
            'required' => 'Полето е задължително',
            'min' => 'Полето е задължително',
        ],
        'hours.*' => [
            'required' => 'Полето е задължително',
            'min' => 'Минималната стойност е 1',
        ],
        'firms.*' => [
            'required' => 'Полето е задължително',
            'min' => 'Минималната стойност е 1',
        ],
        'per_year.*' => [
            'required' => 'Полето е задължително',
            'min' => 'Минималната стойност е 1',
        ],
        'salary.*' => [
            'required' => 'Полето е задължително',
            'gt' => 'Невалидна стойност',
        ],
        'incoming.*' => [
            'required' => 'Невалидна стойност (въведете 0 или по-голяма от 0)',
            'min' => 'Невалидна стойност (въведете 0 или по-голяма от 0)',
        ],
        'costs.*' => [
            'required' => 'Невалидна стойност (въведете 0 или по-голяма от 0)',
            'min' => 'Невалидна стойност (въведете 0 или по-голяма от 0)',
        ],
        'investment_costs' => [
            'required' => 'Невалидна стойност (въведете 0 или по-голяма от 0)',
            'min' => 'Невалидна стойност (въведете 0 или по-голяма от 0)',
        ],
        'diskont' => [
            'required' => 'Невалидна стойност (въведете стойност по-голяма от 0)',
            'gt' => 'Невалидна стойност (въведете стойност по-голяма от 0)',
        ],
        'weight.*' => [
            'required' => 'Невалидна стойност (въведете стойност по-голяма от 0)',
            'gt' => 'Невалидна стойност (въведете стойност по-голяма от 0)',
            'numeric' => 'Полето трябва да бъде число',
        ],
        'evaluation.*' => [
            'required' => 'Полето е задължително'
        ],
        'evaluation.*.*' => [
            'gte' => 'Полето трябва да бъде по-голямо или равно на :value.',
            'lte' => 'Полето трябва да бъде по-малко или равно на :value.',
        ],
        'criteria.*' => [
            'required' => 'Полето е задължително'
        ],
        'variants.*' => [
            'required' => 'Полето е задължително'
        ],
        'member_ord.*' => [
            'required' => 'Полето е задължително',
            'gt' => 'Невалидна стойност (въведете стойност по-голяма от 0)',
        ],
        'document_date_expiring' => [
            'required_without' => 'Полето е задължително, когато датата на изтичане не е неограничена.'
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

        'name_organization_names'       => 'Имена/Наименование на организация',
        'email_address'                 => 'Имейл адрес',
        'legislative_initiative_name'   => 'Наименование на законодателна инициатива',

        // end site part

        'analysis_types_text'      => 'Вид анализ',
        'complexity'               => 'Комплексност',
        'roles'                    => 'Роля',
        'groups'                   => 'Група',
        'status'                   => 'Статус',
        'alias'                    => 'Псевдоним',
        'name'                     => 'Наименование',
        'display_name'             => 'Наименование',
        'contractor_name_bg'       => 'Наименование на възложител',
        'executor_name_bg'         => 'Наименование на изпълнител',
        'contract_subject_bg'      => 'Предмет на договора',
        'services_description_bg'  => 'Описание на извършените услуги',
        'username'                 => 'Потребител',
        'email'                    => 'Ел. поща',
        'first_name'               => 'Име',
        'middle_name'              => 'Презиме',
        'last_name'                => 'Фамилия',
        'victim_egn'               => 'ЕГН',
        'password'                 => 'Парола',
        'password_confirm'         => 'Потвърди Парола',
        'password_confirmation'    => 'Потвърди Парола',
        'must_change_password'     => 'Да се изпрати автоматичен email до потребителя с линк за да въведе паролата си сам',
        'investigation_number'     => 'Преписка №',
        'investigation_year'       => 'Преписка година',
        'assignor_number'          => 'Възложител писмо №',
        'assignor_date'            => 'Възложител писмо дата',
        'city_id'                  => 'Град',
        'court_id'                 => 'Съд',
        'police_office_id'         => 'МВР',
        'prosecutor_office_id'     => 'Прокуратура',
        'other_id'                 => 'Друг Възложител',
        'city'                     => 'Град',
        'country'                  => 'Държава',
        'address'                  => 'Адрес',
        'phone'                    => 'Телефон',
        'channels'                 => 'Комуникационни ресурси',
        'age'                      => 'Възраст',
        'sex'                      => 'Пол',
        'gender'                   => 'Пол',
        'day'                      => 'Ден',
        'month'                    => 'Месец',
        'year'                     => 'Година',
        'hour'                     => 'Час',
        'minute'                   => 'Минута',
        'second'                   => 'Секунда',
        'title'                    => 'Заглавие',
        'title_bg'                    => 'Заглавие (BG)',
        'title_en'                    => 'Заглавие (EN)',
        'content'                  => 'Съдържание',
        'description'              => 'Описание',
        'description_bg'              => 'Описание  (BG)',
        'description_en'              => 'Описание (EN)',
        'excerpt'                  => 'Откъс',
        'date'                     => 'Дата',
        'time'                     => 'Време',
        'available'                => 'Достъпен',
        'size'                     => 'Размер',
        'contractor_name.bg'       => 'Наименование на възложител',
        'executor_name.bg'          => 'Наименование на изпълнител',
        'contract_subject.bg'       => 'Предмет на договора',
        'services_description.bg'    => 'Описание на извършените услуги',
        'price'                     => 'Цена на договора',
        'recaptcha_response_field' => 'Рекапча',
        'subject'                  => 'Заглавие',
        'message'                  => 'Съобщение',
        'rememberme'               => 'Запомни ме',
        'role'                     => 'Роля',
        'created_at'               => 'Дата на създаване',
        'updated_at'               => 'Последна промяна',
        'is_approved'              => 'Одобрен',
        'type'                     => 'Тип',
        'visibility'               => 'Видимост',
        'image'                    => 'Изображение',
        'notes'                    => 'Бележки',
        'color'                     => 'Цвят',
        'number'                    => 'Номер',
        'count'                     => 'Брой',
        'start_time'                => 'Начално време',
        'text'                      => 'Текст',
        'begin_date'                => 'дата на започване',
        'model'                     => 'Модел',
        'open_from'                 => 'Дата на откриване',
        'open_to'                   => 'Дата на приключване',
        'short_term_reason_bg'           => 'Причина за кратък срок (BG)',
        'short_term_reason_en'           => 'Причина за кратък срок (EN)',
        'responsible_unit_bg'           => 'Отговорно звено  (BG)',
        'responsible_unit_en'           => 'Отговорно звено  (EN)',
        'active'                    => 'Активна',
        'deleted'                   => 'Изтрита',
        'effective_from'            => 'В сила от',
        'effective_to'              => 'В сила до',
        'category'                  => 'Категория',
        'event_date'                => 'Дата на събитие',
        'external_link'             => 'Външна връзка',
        'external_link_url'         => 'URL на външна връзка',
        'highlighted_news'          => 'Водеща новина',
        'show_news_home'            => 'Показване на начална страница',
        'is_archived'               => 'Записът е архивиран',
        'start'                     => 'Начало',
        'end'                       => 'Край',
        'highlighted_publication'   => 'Водеща публикация',
        'element_type'              => 'Тип на елемента',
        'estimation_type'           => 'Тип на самооценка',
        'entity_type'               => 'Вид лице',
        'eik'                       => 'ЕИК',
        'contract_date'             => 'Дата на договор',
        'contractor'                => 'Възложител',
        'payment'                   => 'Възнаграждение за извършените услуги в лева',
        'vat'                       => 'ДДС',
        'service_description'       => 'Описание на извършените услуги',
        'reason_denied'             => 'Причина за отказ',
        'denied'                    => 'Отказана',
        'internet_address'          => 'Интернет адрес',
        'link_category'             => 'Категория връзки',
        'show_main_menu'            => 'Покажи в основното меню',
        'specialised_page'          => 'Специализирана страница',
        'css_class'                 => 'CSS клас',
        'subtitle'                  => 'Под-заглавие',
        'url'                       => 'URL',
        'date_from'                 => 'От дата',
        'from_date'                 => 'От дата',
        'date_to'                   => 'До дата',
        'to_date'                   => 'До дата',
        'objective'                 => 'Предмет на договора',
        'executor'                  => 'Изпълнител',
        'institution'               => 'Институция',
        'author'                    => 'Автор',
        'author_bg'                 => 'Автор (BG)',
        'author_en'                 => 'Автор (EN)',
        'highlighted_page'          => 'Водеща страница',
        'is_org'                    => 'Вид профил',
        'org_name'                  => 'Име на организацията',
        'value'                     => 'Стойност',
        'institution_id'            => 'Институция',
        'confirm_password'          => 'Потвърждение на парола',
        'user_type'                 => 'Тип потребител',
        'slug'                      => 'Slug',
        'meta_title'                => 'Заглавие (meta)',
        'meta_description'          => 'Описание (meta)',
        'meta_keyword'              => 'Ключови думи (meta)',
        'short_content'             => 'Кратко описание',
        'main_img'                  => 'Основна снимка',
        'published_at'              => 'Публикуване (дата)',
        //settings admin
        'system_email'              => 'Ел. поща (системни известия)',
        'assessment'                => 'Оценка на въздействието',
        'opinion'                   => 'Становище',
        'proposal_ways_bg'          => 'Начини на предоставяне на предложения и становища (BG)',
        'proposal_ways_en'          => 'Начини на предоставяне на предложения и становища (EN)',
        //Обществени консултации
        'consultation_type_id'      => 'Тип обществена консултация',
        'consultation_level_id'      => 'Категория (ниво) обществени консултации',
        'act_type_id'                  => 'Вид акт',
        //polls
        'once'                 => 'Еднократно попълване',
        'only_registered'      => 'Само за регистрирани потребители',
        'start_date'            => 'Начална дата',
        'end_date'            => 'Крайна дата',
        'new_question_name'            => 'Въпрос',
        'new_answers.*'            => 'Отговор',
        'order_idx'                 => 'Порденост',
        'meta_title_bg'                => 'Заглавие (meta) BG',
        'meta_title_en'                => 'Заглавие (meta) EN',
        'meta_description_bg'          => 'Описание (meta) BG',
        'meta_description_en'          => 'Описание (meta) EN',
        'meta_keyword_bg'              => 'Ключови думи (meta) BG',
        'meta_keyword_en'              => 'Ключови думи (meta) EN',
        'short_content_bg'              => 'Кратко описание BG',
        'short_content_en'              => 'Кратко описание EN',
        'content_bg'                       => 'Съдържание (BG)',
        'content_en'                       => 'Съдържание (EN)',
        'name_bg'                       => 'Наименование (BG)',
        'name_en'                       => 'Наименование (EN)',
        'member_name_bg'                => 'Име (BG) на член',
        'member_name_en'                => 'Име (EN) на член',
        'in_footer_menu'                => 'Включено в меню (footer)',
        'strategic_act_number'                => 'Номер на акта',
        'strategic_act_link'                => 'Връзка към акта',
        'link_to_monitorstat'                => 'Връзка към Мониторстат',
        'display_name_bg'                => 'Публично име (BG)',
        'display_name_en'                => 'Публично име (EN)',
        'display_name_main_bg'                => 'Публично име (BG)',
        'display_name_main_en'                => 'Публично име (EN)',
        'display_name_file_edit_bg'     => 'Публично име (BG)',
        'display_name_file_edit_en'     => 'Публично име (EN)',
        'file_info_bg'                => 'Допълнителна информация (BG)',
        'file_info_en'                => 'Допълнителна информация (EN)',
        'file_info_main_bg'                => 'Допълнителна информация (BG)',
        'file_info_main_en'                => 'Допълнителна информация (EN)',
        'file_info_file_edit_bg'                => 'Допълнителна информация (BG)',
        'file_info_file_edit_en'                => 'Допълнителна информация (EN)',
        'public_consultation_id'      => 'Обществена консултация',
        'label_bg'              => 'Наименование (BG)',
        'label_en'              => 'Наименование (EN)',
        'in_pris'              => 'Включен в категории ПРИС',
        'label'              => 'Наименование',
        'doc_num'              => 'Номер',
        'doc_date'              => 'Дата',
        'protocol'              => 'Протокол',
        'newspaper_number'     => 'Обн. ДВ. (номер)',
        'newspaper_year'     => 'ДВ. (година)',
        'newspaper'     => 'Обн. ДВ.',
        'about_bg'              => 'Заглавие/Относно (BG)',
        'about_en'              => 'Заглавие/Относно (EN)',
        'legal_reason_bg'     => 'Правно основание (BG)',
        'legal_reason_en'     => 'Правно основание (EN)',
        'importer_bg'     => 'Вносител (BG)',
        'importer_en'     => 'Вносител (EN)',
        'file_bg'           => 'Файл (BG)',
        'file_en'           => 'Файл (EN)',
        'connect_type'           => 'Тип на изменение',
        'new_name'           => 'Наименование',
        'new_email'           => 'Електронна поща',
        'file_1_bg'           => 'Оценка на въздействие (BG)',
        'file_1_en'           => 'Оценка на въздействие (EN)',
        'file_2_bg'           => 'Становище на администарцията на Министерски съвет по оценката (BG)',
        'file_2_en'           => 'Становище на администарцията на Министерски съвет по оценката (EN)',
        'file_3_bg'           => 'Доклад (BG)',
        'file_3_en'           => 'Доклад (EN)',
        'file_4_bg'           => 'Проект на акт (BG)',
        'file_4_en'           => 'Проект на акт (EN)',
        'file_5_bg'           => 'Мотиви (BG)',
        'file_5_en'           => 'Мотиви (EN)',
        'file_6_bg'           => 'Консолидирана версия (BG)',
        'file_6_en'           => 'Консолидирана версия (EN)',
        'file_7_bg'           => 'Други документи (BG)',
        'file_7_en'           => 'Други документи (EN)',
        'file_8_bg'           => 'Справка за получените предложения (BG)',
        'file_8_en'           => 'Справка за получените предложения (EN)',
        'monitorstat'         => 'Връзка към документа в Мониторстат',
        'nomenclature_level'  => 'Ниво (номенклатура)',
        'legislative_program_id'  => 'Законодателна програма',
        'operational_program_id'  => 'Оперативна програма',
        'user'  => 'Потребител',
        'field_of_action'           => 'Област на политика|Област на политики',
        'target_group'              => 'Целева група|Целеви групи',
        'document_accepted_with'    => 'Приет с Решение №',
        'file_strategic_documents_bg'  => 'Файл (BG)',
        'file_strategic_documents_en'  => 'Файл (EN)',
        'advisory_name' => 'Име на съвет',
        'authority_id' => 'Вид орган, към който е създаден съветът',
        'specific_name' => 'Конкретно наименование',
        'act_of_creation' => 'Акт на създаване',
        'meetings_per_year' => 'Регламентиран брой заседания на година',
        'report_at' => 'Отчет на дейност пред',
        'field_of_actions_id' => 'Област на политика',
        'icon_class' => 'Икона (клас)',
        'advisory_chairman_type_id' => 'Вид председател',
        'advisory_type_id' => 'Тип',
        'advisory_act_type_id' => 'Акт на създаване',
        'report_time' => 'Час',
        'vice_chairman' => 'Заместник-председател',
        'council_members' => 'Членове на съвета',
        'job' => 'Длъжност',
        'member_notes' => 'Бележки и кратка информация',
        'next_meeting' => 'Следващо заседание',
        'strategic_document_level_id' => 'Ниво на стратегически документ',
        'policy_area_id' => 'Област на политика',
        'strategic_document_type_id' => 'Вид стратегически документ',
        'strategic_act_type_id' => 'Вид акт, с който се одобряват стратегически документи',
        'accept_act_institution_type_id' => 'Орган, приел стратегически документ',
        'document_date_accepted'  => 'Дата на приемане',
        'document_date_expiring'  => 'Дата на валидност',
        'date_of_meeting' => 'Дата на заседанието',
        'agenda' => 'Дневен ред',
        'decisions' => 'Решения',
        'suggestions' => 'Предложения',
        'other' => 'Други',
        'resolution_council_matters' => 'Постановление на Министерски съвет',
        'state_newspaper' => 'Държавен вестник',
        'effective_at' => 'В сила от',
        'redirect_to_iisda' => 'Препратка към Интегрираната информационна система на държавната администрация (ИИСДА)',
        'user_id' => 'потребител',
        'legislative_program_row_id' => 'Законопроект',
        'institutions' => 'Институции',
        'framework_description_bg' => 'Описание',
        'framework_description_en' => 'Описание',
        'npo_presenter' => 'НПО представител',
        'contact_person' => 'Лице за контакт',
        'regulatory_act' => 'Нормативен акт',
        'applications' => 'Приложения',
        'info_sources' => 'Информационни източници',
        'status_id' => 'Статус',
        'commitment_name' => 'Ангажимент',
        'commitment_id' => 'Съществуващ ангажимент',
        'arrangement_name' => 'Мярка',
        'arrangement_id' => 'Съществуваща мярка',
        'period_assessment' => 'Период на извършване на оценката',
        'consultations' => 'Проведени консултации',
        'resume' => 'Резюме',
        'introduction' => 'Увод',
        'conclusions' => 'Заключения',
        'distribution' => 'Начини на разпространение',
        'recommendations' => 'Препоръки за последващи действия',
        'sources' => 'Източници',
        'ogp_area' => 'Област',
        'npo_partner_bg'              => 'Партньори за изпълнението на мярката (BG)',
        'npo_partner_en'              => 'Партньори за изпълнението на мярката (EN)',
        'responsible_administration_bg'              => 'Водеща институция/организация (BG)',
        'responsible_administration_en'              => 'Водеща институция/организация (EN)',
        'responsibility' => 'Отговорна институция',
        'activity_name' => 'Наименование на дейността',
        'investment_costs' => 'Инвестиционни разходи',
        'diskont' => 'Годишна социална норма на дисконт (%)',
        'file' => 'Файл',
        'adv_board' => 'Консултативен съвет',
        'working_year' => 'Година',
        'member_job_bg' => 'Длъжност (BG)',
        'member_job_en' => 'Длъжност (EN)',
        'member_notes_bg' => 'Бележки и кратка информация (BG)',
        'member_notes_en' => 'Бележки и кратка информация (EN)',
        'file_name_bg' => 'Наименование (BG)',
        'file_name_en' => 'Наименование (EN)',
        'decisions_bg' => 'Решения (BG)',
        'decisions_en' => 'Решения (EN)',
        'suggestions_bg' => 'Предложения (BG)',
        'suggestions_en' => 'Предложения (EN)',
        'other_bg' => 'Други (BG)',
        'other_en' => 'Други (EN)',
        'recipient' => 'Изпрати до',
        'establishment_description_bg' => 'Описание (BG)',
        'establishment_description_en' => 'Описание (EN)',
        'body_bg' => 'Съдържание (BG)',
        'body_en' => 'Съдържание (EN)',
        'problem_bg' => 'Какъв е общественият проблем, който се решава с мярката? (BG)',
        'problem_en' => 'Какъв е общественият проблем, който се решава с мярката? (EN)',
        'from_date_develop' => 'Приемане на предложения (от)',
        'to_date_develop' => 'Приемане на предложения (до)',
        'solving_problem_bg' => 'Как изпълнението на мярката ще допринесе за решаването на проблема? (BG)',
        'solving_problem_en' => 'Как изпълнението на мярката ще допринесе за решаването на проблема? (EN)',
        'values_initiative_bg' => 'Как мярката е относима към ценностите на инициативата? (BG)',
        'values_initiative_en' => 'Как мярката е относима към ценностите на инициативата? (EN)',
        'extra_info_bg' => 'Допълнителна информация (в т.ч. бюджет за изпълнение на мярката) (BG)',
        'extra_info_en' => 'Допълнителна информация (в т.ч. бюджет за изпълнение на мярката) (EN)',
        'interested_org_bg' => 'Заинтересовани организации (BG)',
        'interested_org_en' => 'Заинтересовани организации (EN)',
        'contact_names_bg' => 'Име на лицето/ата за контакт от водещата институция/организация (BG)',
        'contact_names_en' => 'Име на лицето/ата за контакт от водещата институция/организация (EN)',
        'contact_positions_bg' => 'Длъжност/позиция (BG)',
        'contact_positions_en' => 'Длъжност/позиция (EN)',
        'contact_phone_email_bg' => 'Електронна поща и телефон за контакт (BG)',
        'contact_phone_email_en' => 'Електронна поща и телефон за контакт (EN)',
        'evaluation_bg' => 'Оценка (BG)',
        'evaluation_en' => 'Оценка (EN)',
        'evaluation_status_bg' => 'Оценка (статус) (BG)',
        'evaluation_status_en' => 'Оценка (статус) (EN)',
        'new_from_date' => 'Начална дата',
        'new_to_date' => 'Дата на приключване на дейността',
        'new_name_bg' => 'Наименование (BG)',
        'new_name_en' => 'Наименование (EN)',
    ],
];
