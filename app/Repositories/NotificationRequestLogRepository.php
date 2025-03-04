<?php

namespace App\Repositories;

use App\Models\NotificationRequestLog;
use Exception;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class NotificationRequestLogRepository
{
    /**
     * $apiRequestLog variable
     *
     * @var object
     */
    protected NotificationRequestLog $notificationRequestLog;

    /**
     * __construct function
     *
     * @param NotificationRequestLog $notificationRequestLog
     */
    public function __construct(NotificationRequestLog $notificationRequestLog)
    {
        $this->notificationRequestLog = $notificationRequestLog;
    }


    /**
     * store function
     *
     * @param array $data
     * @return object
     */
    public function store($data)
    {
        // add try catch block
        $logTag = 'Sender Correspondent Request Log Repository ';
        $logLabel = 'Store ';
        try {
            $modelData = new $this->notificationRequestLog;

            $modelData->request_id                 = isset($data['request_id']) ? $data['request_id'] : null;
            $modelData->external_service_config_id = isset($data['external_service_config_id']) ? $data['external_service_config_id'] : null;
            $modelData->external_service_name      = isset($data['external_service_name']) ? $data['external_service_name'] : null;
            $modelData->transaction_id             = isset($data['transaction_id']) ? $data['transaction_id'] : null;
            $modelData->msisdn                     = isset($data['msisdn']) ? $data['msisdn'] : null;
            $modelData->input_virtual_account_id   = isset($data['input_virtual_account_id']) ? $data['input_virtual_account_id'] : null;
            $modelData->request_for                = isset($data['request_for']) ? $data['request_for'] : null;
            $modelData->header                     = isset($data['header']) ? $data['header'] : null;
            $modelData->request_for                = isset($data['request_for']) ? $data['request_for'] : null;
            $modelData->header                     = isset($data['header']) ? $data['header'] : null;
            $modelData->request_body               = isset($data['request_body']) ? $data['request_body'] : null;
            $modelData->response                   = isset($data['response']) ? $data['response'] : null;
            $modelData->metadata                   = isset($data['metadata']) ? $data['metadata'] : null;

            $modelData->save();

            return $modelData;

        } catch (Exception $e) {
            Log::error(__formatDebugLog($logTag, $logLabel . 'exception', __METHOD__ . ' ' . $e->getLine() . ' ' . $e->getMessage() . ' - Data: ' . print_r($data, true)));
        }


    }
}
