<?php

namespace App\Jobs;

use App\Repositories\ApiRequestLogRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ApiRequestLogJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * $data variable
     *
     * @var array
     */
    protected $data;

    /**
     * $apiRequestLogIdLimit variable
     *
     * @var integer
     */
    public $apiRequestLogIdLimit;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ApiRequestLogRepository $apiRequestLogRepository)
    {

        try {
            if (config('app.enable_request_logger')) {
                $inputs = isset($this->data['request_body']) ? $this->data['request_body'] : [];
                $responseDetails = isset($this->data['response']) ? json_decode($this->data['response']) : null;
                $data = [
                    'ip_address' => isset($this->data['ip_address']) ? $this->data['ip_address'] : null,
                    'request_id' => isset($inputs['request_id']) ? $inputs['request_id'] : null,
                    'input_virtual_account_id' => isset($inputs['input_virtual_account_id']) ? $inputs['input_virtual_account_id'] : null,
                    'request_for' => isset($this->data['request_for']) ? $this->data['request_for'] : null,
                    'header' => isset($this->data['header']) ? json_encode($this->data['header']) : null,
                    'request_body' => json_encode($inputs),
                    'response' => isset($this->data['response']) ? $this->data['response'] : null,
                    'response_code' => isset($responseDetails->status) ? $responseDetails->status : null,
                    // 'user_id' => isset($this->data['user_id']) ? $this->data['user_id'] : null,
                    'meta' => null,
                ];

                $apiRequestLogRepository->store($data);
            }

        } catch (\Exception $e) {

            Log::error("Exception during ApiRequestLogJob: " . $e->getMessage() . ' Line - ' . $e->getLine() . ' - Data: ' . print_r($this->data, true));
        }
    }
}
