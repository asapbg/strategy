<?php

namespace App\Notifications;

use App\Models\LegislativeInitiative;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SendLegislativeInitiative extends Notification
{
    use Queueable;

    protected $legislativeInitiative;
    protected $ssevProfile;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(LegislativeInitiative $item, int $ssevProfile)
    {
        $this->legislativeInitiative = $item;
        $this->ssevProfile = $ssevProfile;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [CustomDbChannel::class];

    }


    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable): array
    {
        $communicationData = [
//            'message' => 'Уважаеми госпожи и господа,'
//                . PHP_EOL . 'На Портала за обществени консултации е създадено предложение за усъвършенстване на законодателството (наречена законодателна инициатива) във връзка с чл. 18 от Закона за нормативните актове. Инициативата е събрала определената от системата на Портала за обществени консултации подкрепа. Това съобщение е изпратено автоматично и следва да се разглежда като предложение по реда на Глава осма от Административнопроцесуалния кодекс. Информация за законодателната инициатива:'
//                . PHP_EOL . 'Наименование на нормативния акт, който се предлага да бъде променен: ' . $this->legislativeInitiative->law?->name
//                . PHP_EOL . 'Описание на предложената промяна: ' . $this->legislativeInitiative->description
//                . PHP_EOL . 'Автор на предложението: ' . $this->legislativeInitiative->user->fullName()
//                . PHP_EOL . 'Институция, отговорна за нормативния акт: ' . ($this->legislativeInitiative->law && $this->legislativeInitiative->law->institutions->count() ? join(';', $this->legislativeInitiative->law->institutions->pluck('name')->toArray()) : '---')
//                . PHP_EOL . 'Линк към законодателната инициатива: ' . route('legislative_initiatives.view', $this->legislativeInitiative)
//                . PHP_EOL . 'Този имейл е автоматично генериран, моля не отговаряйте. В случай, че имейлът не е предназначен за Вас, моля да го игнорирате. Ако прецените, че е необходимо, може да се свържете с администратора на Портала за обществени консултации, чрез секция „Контакти“.',
            'message' => 'Уважаеми госпожи и господа,'
                . PHP_EOL . 'Това предложение за промени в '. $this->legislativeInitiative->law?->name . ' е направено от '. $this->legislativeInitiative->user->fullName() .'като е подписано с квалифициран електронен подпис.'
                . PHP_EOL . 'Предложението е направено на основание чл. 18 от Закона за нормативните актове чрез Портала за обществени консултации.'
                . PHP_EOL . 'В случай че Вашата институция не е компетентна по направеното предложение, то следва да бъде препратено до компетентната такава с копие до неговия автор.“'
                . PHP_EOL . 'Линк към законодателната инициатива: '
                . PHP_EOL . route('legislative_initiatives.view', $this->legislativeInitiative),
            'subject' => 'Известие',
            'object_id' => $this->legislativeInitiative->id,
            'files' => [],
            'type_channel' => env('E_DELIVERY_METHOD', 2)
        ];

        if ($this->ssevProfile) {
            $eDeliveryConfig = config('e_delivery');
            if (config('app.env') != 'production') {
                $communicationData['ssev_profile_id'] = config('e_delivery.local_ssev_profile_id');
            } else {
//                $communicationData['to_group'] = $notifiable->eik == '175370880' ? $eDeliveryConfig['group_ids']['company'] : $eDeliveryConfig['group_ids']['egov'];
//                $communicationData['to_identity'] = $notifiable->eik;
                $communicationData['ssev_profile_id'] = $this->ssevProfile;
            }
        } else {
            //email
            $communicationData['from_name'] = config('mail.from.name');
            $communicationData['from_email'] = config('mail.from.address');
            $communicationData['to_name'] = $notifiable->name;
            $communicationData['to_email'] = $notifiable->email;
        }
        return $communicationData;
    }
}
