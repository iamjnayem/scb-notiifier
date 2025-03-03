<?php
namespace App\Services;


class NotifierService
{
    private ScbNotifierService $scbNotifierService;
    public function __construct(ScbNotifierService $scbNotifierService)
    {
        $this->scbNotifierService= $scbNotifierService;
    }

    public function notify()
    {
        $this->scbNotifierService->notify();
    }
}

