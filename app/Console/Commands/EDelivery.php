<?php

namespace App\Console\Commands;

use App\Api\Ssev\EDeliveryAuth;
use App\Api\Ssev\EDeliveryService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use PDO;
use PDOException;

class EDelivery extends Command
{
    const MAX_SEND_TRY = 10;

    static function saveError($pdoConn, $msgId, $msg) {

        $query = $pdoConn->prepare("insert into notification_error (notification_id, content, created_at) values (?, ?, ?)");
        $query->execute([$msgId, $msg, date('Y-m-d H:i:s')]);
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
        if ( !$deliveryMethodId ) {
            echo 'Delivery method ID not specified'.PHP_EOL;
            exit;
        }

        $dbHost = env('DB_HOST');
        $dbPort = env('DB_PORT');
        $dbName = env('DB_DATABASE');
        $dbUser = env('DB_USERNAME');
        $dbPass = env('DB_PASSWORD');

        try {

            $dsn = "pgsql:host=".$dbHost.";port=".$dbPort.";dbname=".$dbName.";";
            // make a database connection
            $pdoConn = new PDO($dsn, $dbUser, $dbPass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

            if ($pdoConn) {
                echo "Connected to the ".$dbName." database successfully!".PHP_EOL;
            }

        } catch (PDOException $e) {

            Log::error($e->getMessage());
            exit;

        }

        //get all notification for eDelivery
        $query = $pdoConn->prepare("
            SELECT id, data, cnt_send, type
              FROM notifications
            WHERE cnt_send <= ?
              AND type_channel = ?
              AND is_send = ?
              AND (updated_at <= ? OR created_at >= ?)
            LIMIT 10
        ");
        $timestamp = date('Y-m-d H:i:s', (strtotime(date('Y-m-d H:i:s')) - (60 * 60)));
        $query->execute([self::MAX_SEND_TRY, $deliveryMethodId, 0, $timestamp, $timestamp]);
        $notifications = $query->fetchAll();

        if ( sizeof($notifications) ) {
            $eDeliveryAuthService = new EDeliveryAuth('/ed2*');
            $token = $eDeliveryAuthService->getToken();
            if ( is_null($token) ) {
                echo 'Can\'t authenticate to SSEV';exit;
            }

            $eDeliveryService = new EDeliveryService($token, 'ed2');
            foreach ($notifications as $item) {
                $msgData = json_decode($item['data'], true);
                if ( $msgData ) {

                    $recipientProfileIds = 0;
                    //check if already have profile id
                    if ( isset($msgData['ssev_profile_id']) && (int)$msgData['ssev_profile_id'] > 0 ) {
                        $recipientProfileIds = (int)$msgData['ssev_profile_id'];
                    }

                    if ( !($recipientProfileIds > 0) ) {
                        self::saveError($pdoConn, $item['id'], 'SSEV (eDelivery) missing ssev profile id');
                        continue;
                    }

                    $uploadedFiles = [];
                    if ( isset($msgData['files']) && sizeof($msgData['files']) ) {
                        foreach ($msgData['files'] as $f) {
                            $uploadFileResponse = $eDeliveryService->uploadFile($f);
                            if ( is_array($uploadFileResponse) && isset($uploadFileResponse['error']) ) {
                                self::saveError($pdoConn, $item['id'], 'SSEV (eDelivery) upload file(ID '.$f['id'].') request error: '. $uploadFileResponse['message']);
                                continue;
                            }
                            $fileResponse = json_decode($uploadFileResponse, true);
                            if ( !$fileResponse || !isset($fileResponse['blobId']) ) {
                                self::saveError($pdoConn, $item['id'], 'SSEV (eDelivery) upload file(ID '.$f['id'].') response error missing file ID: '. $fileResponse);
                                continue;
                            }
                            $uploadedFiles[] = (int)$fileResponse['blobId'];
                        }
                    }

                    //if not all files are uploaded
                    if ( isset($msgData['files']) && sizeof($msgData['files']) != sizeof($uploadedFiles) ) {
                        $query = $pdoConn->prepare("update notifications set cnt_send = ?, updated_at = ? where id = ?");
                        $query->execute([($item['cnt_send'] + 1), date('Y-m-d H:i:s'), $item['id']]);
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

                    if ( is_array($responseJson) && isset($responseJson['error']) ) {
                        self::saveError($pdoConn, $item['id'], 'SSEV (eDelivery) send message error: '. $responseJson['message']);
                        $responseJson = json_encode($responseJson);
                    } else {
                        $success = (int)$responseJson;
                    }

                    if ( $success ) {
                        $query = $pdoConn->prepare("update notifications set msg_integration_id = ?, is_send = ?, cnt_send = ?, updated_at = ? where id = ?");
                        $query->execute([$success, 1, ($item['cnt_send'] + 1), date('Y-m-d H:i:s'), $item['id']]);

                    } else {
                        self::saveError($pdoConn, $item['id'], 'SSEV (eDelivery) send message response error (missing message id): '. $responseJson);
                        $query = $pdoConn->prepare("update notifications set cnt_send = ?, updated_at = ? where id = ?");
                        $query->execute([($item['cnt_send'] + 1), date('Y-m-d H:i:s'), $item['id']]);
                    }
                }
            }

        } else {
            echo 'no message to send'.PHP_EOL;
        }
        $pdoConn = null;
    }
}
