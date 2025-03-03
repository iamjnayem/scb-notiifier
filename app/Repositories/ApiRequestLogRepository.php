<?php

namespace App\Repositories;

use App\Models\ApiRequestLog;
use Exception;
use Illuminate\Support\Facades\Log;

class ApiRequestLogRepository
{
    /**
     * $apiRequestLog variable
     *
     * @var object
     */
    protected $apiRequestLog;

    /**
     * __construct function
     *
     * @param ApiRequestLog $apiRequestLog
     */
    public function __construct(ApiRequestLog $apiRequestLog)
    {
        $this->apiRequestLog = $apiRequestLog;
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
        $logTag = 'Api Request Log Repository ';
        $logLabel = 'Store ';
        try {
            $modelData = new $this->apiRequestLog;

            // $modelData->user_id = $data['user_id'];
            $modelData->ip_address = isset($data['ip_address']) ? $data['ip_address'] : null;
            $modelData->input_virtual_account_id = isset($data['input_virtual_account_id']) ? $data['input_virtual_account_id'] : null;
            $modelData->request_id = $data['request_id'];
            $modelData->request_for = isset($data['request_for']) ? $data['request_for'] : null;
            $modelData->header = isset($data['header']) ? $data['header'] : null;
            $modelData->request_body = isset($data['request_body']) ? $data['request_body'] : null;
            $modelData->response = isset($data['response']) ? $data['response'] : null;
            $modelData->response_code = isset($data['response_code']) ? $data['response_code'] : null;
            $modelData->meta = isset($data['meta']) ? $data['meta'] : null;
            $modelData->save();

            return $modelData;

        } catch (Exception $e) {
            Log::error(__formatDebugLog($logTag, $logLabel . 'exception', __METHOD__ . ' ' . $e->getLine() . ' ' . $e->getMessage() . ' - Data: ' . print_r($data, true)));
        }


    }
}
