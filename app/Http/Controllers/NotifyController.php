<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScbNotifyPostRequest;
use App\Services\NotifierService;
use App\Services\ScbNotifyServices;
use Illuminate\Http\Request;

class NotifyController extends Controller
{
    private NotifierService $notifierService;

    public function __construct(NotifierService $notifierService)
    {
        $this->notifierServices = $notifierService;
    }


    public function notify(ScbNotifyPostRequest $request)
    {
        $this->notifierService->notify($request);
    }
}
