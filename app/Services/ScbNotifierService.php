<?php
namespace App\Services;

use Illuminate\Session\Store;
use App\Http\Requests\ScbNotifyPostRequest;
use App\Models\SenderCorrespondentRequestLog;
use App\Repositories\SenderRequestLogRepository;


class ScbNotifierService
{
    private SenderRequestLogRepository $senderRequestLogRepository;
    public function __construct(SenderRequestLogRepository $senderRequestLogRepository)
    {
        $this->senderRequestLogRepository = $senderRequestLogRepository;
    }

    public function notify(ScbNotifyPostRequest $request)
    {
        dd('here');
        $payload = $request;
        $this->senderRequestLogRepository->store($payload);
    }
}

