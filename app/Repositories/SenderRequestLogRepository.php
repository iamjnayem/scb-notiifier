<?php

namespace App\Repositories;

use App\Models\SenderCorrespondentRequestLog;
use Exception;
use Illuminate\Support\Facades\Log;

class SenderRequestLogRepository
{
    /**
     * $apiRequestLog variable
     *
     * @var object
     */
    protected $senderCorrespondentRequestLog;

    /**
     * __construct function
     *
     * @param SenderCorrespondentRequestLog $senderCorrespondentRequestLog
     */
    public function __construct(SenderCorrespondentRequestLog $senderCorrespondentRequestLog)
    {
        $this->senderCorrespondentRequestLog = $senderCorrespondentRequestLog;
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
            $modelData = new $this->senderCorrespondentRequestLog;


            $modelData->request_id = isset($data['request_id']) ? $data['request_id'] : null;
            $modelData->msisdn = isset($data['msisdn']) ? $data['msisdn'] : null;
            $modelData->input_virtual_account_id = isset($data['input_virtual_account_id']) ? $data['input_virtual_account_id'] : null;
            $modelData->request_for = isset($data['request_for']) ? $data['request_for'] : null;
            $modelData->header = isset($data['header']) ? $data['header'] : null;
            $modelData->request_for = isset($data['request_for']) ? $data['request_for'] : null;
            $modelData->header = isset($data['header']) ? $data['header'] : null;
            $modelData->request_body = isset($data['request_body']) ? $data['request_body'] : null;
            $modelData->response = isset($data['response']) ? $data['response'] : null;
            $modelData->metadata= isset($data['metadata']) ? $data['metadata'] : null;

            $modelData->save();

            return $modelData;

        } catch (Exception $e) {
            Log::error(__formatDebugLog($logTag, $logLabel . 'exception', __METHOD__ . ' ' . $e->getLine() . ' ' . $e->getMessage() . ' - Data: ' . print_r($data, true)));
        }


    }
}
