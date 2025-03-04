<?php
namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Http\Requests\ScbNotifyPostRequest;
use App\Models\ExternalServiceConfiguration;
use App\Repositories\NotificationRequestLogRepository;
use App\Repositories\ExternalServiceConfigurationRepository;
use App\Services\CashbabaService;
use Exception;

class NotifierService
{
    private NotificationRequestLogRepository $notificationRequestLogRepository;
    private ExternalServiceConfigurationRepository $externalServiceConfigurationRepository;
    private CashbabaService $cashbabaService;

    public function __construct(
        NotificationRequestLogRepository $notificationRequestLogRepository,
        CashbabaService $cashbabaService,
        ExternalServiceConfigurationRepository $externalServiceConfigurationRepository
    ) {
        $this->notificationRequestLogRepository = $notificationRequestLogRepository;
        $this->cashbabaService = $cashbabaService;
        $this->externalServiceConfigurationRepository = $externalServiceConfigurationRepository;
    }

    public function notify($request)
    {
        $logTag = $request->request_id;
        $logLabel = 'NotifierService';

        try {
            Log::info(__formatDebugLog($logTag, "{$logLabel} request params ", $request->all()));
            $lastUrlSegment = strtolower($request->segment(count($request->segments())));

            $externalServiceConfiguration = $this->externalServiceConfigurationRepository->getOne([
                'name' => $lastUrlSegment,
                'status' => 1,
            ]);

            if (empty($externalServiceConfiguration)) {
                $errorResponse = getResponseStatus('404');
                Log::info(__formatDebugLog($logTag, "{$logLabel} response From NotifierService ", $errorResponse));
                return $errorResponse;
            }

            if ($externalServiceConfiguration->name == "cashbaba") {
                $response = $this->cashbabaService->notify($request->all());
            } else {
                $response = getResponseStatus('404', null, ["no wallet is configured for this service"]);
            }

            Log::info(__formatDebugLog($logTag, "{$logLabel} response From NotifierService ", $response));
            return $response;
        } catch (Exception $e) {
            Log::error(__formatDebugLog($logTag, "{$logLabel} exception ", __METHOD__ . ' ' . $e->getLine() . ' ' . $e->getMessage()));
            $errorResponse = getResponseStatus('500');
            Log::info(__formatDebugLog($logTag, "{$logLabel} response From NotifierService ", $errorResponse));
            return $errorResponse;
        }
    }


}
