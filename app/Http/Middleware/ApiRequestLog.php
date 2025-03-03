<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Jobs\ApiRequestLogJob;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;


class ApiRequestLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $limit = 40;
        $request->merge(['request_id' => unique_id($limit)]);

        return $next($request);
    }

    /**
     * terminate function
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function terminate($request, $response)
    {

        $logTag = 'Api Request Log ';
        $logLabel = 'Terminate ';
        try {
            $data = [
                'ip_address' => getClientDynamicIP(),
                'request_id' => isset(request()->request_id) ? request()->request_id : null,
                'input_virtual_account_id' => isset(request()->input_virtual_account_id) ? request()->input_virtual_account_id : null,
                'request_for' => last(request()->segments()),
                'header' => $request->header(),
                'request_body' => $request->all(),
                'response' => $response->getContent(),
                'response_code' => $response->getStatusCode(),
                // 'user_id' => isset(auth()->user()->id) ? auth()->user()->id : null,
            ];

            ApiRequestLogJob::dispatch($data);

        } catch (Exception $e) {
            Log::error(__formatDebugLog($logTag, $logLabel . 'exception', __METHOD__ . ' ' . $e->getLine() . ' ' . $e->getMessage() . ' - Data: ' . print_r($data, true)));
        }
    }
}
