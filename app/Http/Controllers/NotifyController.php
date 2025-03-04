<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NotifierService;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ScbNotifyPostRequest;

class NotifyController extends Controller
{
    private NotifierService $notifierService;

    public function __construct(NotifierService $notifierService)
    {
        $this->notifierService = $notifierService;
    }


    /**
     * policyDetails function
     *
     * @param ScbNotifyPostRequest $request
     * @return array
     */

    public function notify(ScbNotifyPostRequest $request)
    {
        $logTag = $request->request_id;
        $logLabel = 'Scb Notify Controller';

        try {
            Log::info(__formatDebugLog($logTag, $logLabel . ' request params ', $request->all()));
            $response = $this->notifierService->notify($request);
            Log::info(__formatDebugLog($logTag, $logLabel . ' response From Controller ', $response));
            return $response;
        } catch (Exception $e) {

            Log::error(__formatDebugLog($logTag, $logLabel . ' exception ', __METHOD__ . ' ' . $e->getLine() . ' ' . $e->getMessage()));
            $errorResponse = getResponseStatus('500');
            Log::info(__formatDebugLog($logTag, $logLabel . ' response From Controller ', $errorResponse));
            return $errorResponse;
        }
    }
}
