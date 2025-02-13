<?php

namespace App\Console\Commands;

use App\Api\Ssev\EDeliveryAuth;
use App\Api\Ssev\EDeliveryService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class EDelivery extends Command
{
    private int $max_send_retry = 10;

    static function saveError($msgId, $msg): void
    {
        DB::statement('insert into notification_error (notification_id, content, created_at) values (?, ?, ?)', [$msgId, $msg, date('Y-m-d H:i:s')]);
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'e:delivery';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for notifications that are not send yet and send them';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //Get application eDelivery method id //get from application delivery channels configuration in your application
        $deliveryMethodId = env('E_DELIVERY_METHOD', 2);
        if (!$deliveryMethodId) {
            $this->error("Delivery method ID not specified");
            return Command::FAILURE;
        }

        $one_month_ago = date('Y-m-d H:i:s', strtotime('-1 MONTH'));
        $notifications = DB::select("
            SELECT id, data, cnt_send, type
              FROM notifications
             WHERE cnt_send <= $this->max_send_retry
               AND type_channel = $deliveryMethodId
               AND is_send = 0
               --AND created_at <= '$one_month_ago'
             LIMIT 10
        ");

        if (!count($notifications)) {
            $this->info("No notifications found to be send to SSEV");
            return Command::SUCCESS;
        }

        $eDeliveryAuthService = new EDeliveryAuth('/ed2*');
        $token = $eDeliveryAuthService->getToken();
        if (is_null($token)) {
            $this->error("Can't authenticate to SSEV");
            return Command::FAILURE;
        }

        $eDeliveryService = new EDeliveryService($token, 'ed2');
        foreach ($notifications as $item) {
            DB::beginTransaction();
            try {
                $msgData = json_decode($item->data, true);
                if (!$msgData) {
                    continue;
                }
                $recipientProfileIds = 0;
                //check if already have profile id
                if (isset($msgData['ssev_profile_id']) && (int)$msgData['ssev_profile_id'] > 0) {
                    $recipientProfileIds = (int)$msgData['ssev_profile_id'];
                }

                if (!($recipientProfileIds > 0)) {
                    self::saveError($item->id, 'SSEV (eDelivery) missing ssev profile id');
                    continue;
                }

                $uploadedFiles = [];
                if (isset($msgData['files']) && sizeof($msgData['files'])) {
                    foreach ($msgData['files'] as $f) {
                        $uploadFileResponse = $eDeliveryService->uploadFile($f);
                        if (is_array($uploadFileResponse) && isset($uploadFileResponse['error'])) {
                            self::saveError($item->id, 'SSEV (eDelivery) upload file(ID ' . $f['id'] . ') request error: ' . $uploadFileResponse['message']);
                            continue;
                        }
                        $fileResponse = json_decode($uploadFileResponse, true);
                        if (!$fileResponse || !isset($fileResponse['blobId'])) {
                            self::saveError($item->id, 'SSEV (eDelivery) upload file(ID ' . $f['id'] . ') response error missing file ID: ' . $fileResponse);
                            continue;
                        }
                        $uploadedFiles[] = (int)$fileResponse['blobId'];
                    }
                }

                //if not all files are uploaded
                if (isset($msgData['files']) && count($msgData['files']) != count($uploadedFiles)) {
                    DB::statement('update notifications set cnt_send = ?, updated_at = ? where id = ?', [($item->cnt_send + 1), date('Y-m-d H:i:s'), $item->id]);
                    continue;
                }

                $postData = array(
                    'recipientProfileIds' => [$recipientProfileIds],
                    'subject' => $msgData['subject'] ?? '',
                    'rnu' => null,
                    'templateId' => 1, //Писмо
                    'fields' => [
                        '179ea4dc-7879-43ad-8073-72b263915656' => strip_tags(html_entity_decode($msgData['message'])) ?? '',
                        'e2135802-5e34-4c60-b36e-c86d910a571a' => $uploadedFiles, //files
                    ],
                );

                $success = 0;
                $responseJson = $eDeliveryService->sendMessage($postData);

                if (is_array($responseJson) && isset($responseJson['error'])) {
                    self::saveError($item->id, 'SSEV (eDelivery) send message error: ' . $responseJson['message']);
                    $responseJson = json_encode($responseJson);
                } else {
                    $success = (int)$responseJson;
                }

                if ($success) {
                    DB::statement(
                        'update notifications set msg_integration_id = ?, is_send = ?, cnt_send = ?, updated_at = ? where id = ?',
                        [$success, 1, ($item->cnt_send + 1), date('Y-m-d H:i:s'), $item->id]
                    );

                    if ($item->type == 'App\Notifications\SendLegislativeInitiative' && isset($msgData['object_id']) && isset($item->notifiable_id)) {
                        DB::statement(
                            'insert into legislative_initiative_receiver (legislative_initiative_id, institution_id) values (?,?)',
                            [$msgData['object_id'], $item->notifiable_id]
                        );
                    }

                } else {
                    self::saveError($item->id, 'SSEV (eDelivery) send message response error (missing message id): ' . $responseJson);
                    DB::statement(
                        'update notifications set cnt_send = ?, updated_at = ? where id = ?', [($item->cnt_send + 1), date('Y-m-d H:i:s'), $item->id]
                    );
                }

                $this->info("Message was send successfully");
                DB::commit();

            } catch (\Exception $e) {
                DB::rollBack();
                self::saveError($item->id, 'SSEV (eDelivery) error : ' . $e);
            }
        }

        return Command::SUCCESS;
    }
}
