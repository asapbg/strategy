@component('mail::message')

{{ __('Respected') }} {{ $user->fullName() }},

Срокът на създадената от Вас обществена консултация изтече.
Напомняме Ви задължението съгласно чл.26, ал. 5 от Закона за нормативните актове след приключването на обществената консултация, да бъде публикувана справка за постъпилите предложения и становища заедно с обосновка за неприетите предложения. Публикуването се осъществява едновременно на интернет страницата на Вашата институция и на Портала за обществени консултации.
Ако не са постъпили становища и предложения по наличните и обявените канали се публикува съобщение за непостъпили становища и предложения.

Образци на Справка за отразяване на постъпилите предложения и становища от обществените консултации и на Съобщение за непостъпили предложения и становища от обществените консултации са одобрени от Съвета за административната реформа и публикувани на Портала за обществени консултации на следния адрес.
@component('mail::button', ['url' => $url])
    {{ __('See the consultation') }}
@endcomponent

Съобразно вътрешната организация във Вашата институция, препратете този мейл на водещото звено, отговорно за изработване на акта.

{{ __('custom.consultation_number') }}: {{ $pc->reg_num }}<br>
{{ __('custom.name') }}: {{ $pc->getModelName() }}<br>
{{ trans_choice('custom.field_of_actions', 1) }}: {{ $pc->fieldOfAction?->translation?->name }}<br>
{{ trans_choice('custom.institutions', 1) }}: {{ $pc->responsibleInstitution?->translation?->name }}<br>
{{ __('Consultation period') }} : {{ displayDate($pc->open_from) }} - {{ displayDate($pc->open_to) }} г.<br>
{{ __('Link to the consultation on the Public Consultation Portal') }}:

{{ __('Autogenerated email') }}

@endcomponent