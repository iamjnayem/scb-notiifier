<?php

use App\Models\StatusCode;
use App\Utilities\Enum\ActiveInactiveEnum;


function getMockResponse($filePath, $position)
{
    $jsonContent = file_get_contents($filePath);
    $jsonArray = json_decode($jsonContent, true);
    return json_encode($jsonArray[$position]);
}

/**
 * unique_id function
 *
 * @param integer $limit
 * @return string
 */
function unique_id($limit)
{
    return substr(base_convert(sha1(uniqid(mt_rand())), 20, 20), 0, $limit);
}

/**
 * getUid function
 *
 * @param mixed $prefix
 * @return string
 */
function getUid($prefix = null)
{
    return $prefix . uniqid() . str_replace(".", "", microtime(true));
}

/**
 * getResponseStatus function
 *
 * @param integer $code
 * @param mixed $data
 * @param array $errors
 * @param string $type
 * @param string $scope
 * @return array
 */
function getResponseStatus($code = 500, $data = null, $errors = [], $type = 'en', $scope = 'self', $options = [])
{
    $statusCodeData = statusCodeDetails($code, $scope, $type);

    $response = [
        'status' => $statusCodeData['code'], //status
        'status_title' => $statusCodeData['title'], //message
        'errors' => $errors,
        'timestamp' => time(),
        'request_id' => isset(request()->request_id) ? request()->request_id : null,
        'data' => $data,
    ];

    return $response;
}

/**
 * statusCodeDetails function
 *
 * @param string $code
 * @param string $scope
 * @param string $type
 * @return array
 */
function statusCodeDetails($code, $scope, $type)
{
    $response = [
        'code' => null,
        'title' => null,
    ];

    $status = StatusCode::where([
        'code' => $code,
        'scope' => $scope,
        'status' => ActiveInactiveEnum::Active,
    ])->first();

    if (empty($status)) {
        return $response;
    }

    if ($type == 'en') {
        $response = [
            'code' => $status->code,
            'title' => $status->title,
        ];
    } else if ($type == 'bn') {
        $response = [
            'code' => $status->code,
            'title' => $status->title_bn,
        ];
    }

    return $response;
}

/**
 * Generalize the debug log format.
 */
if (!function_exists('__formatDebugLog')) {
    /**
     * @param string $tag
     * @param string $label
     * @param mixed $data
     * @return string
     */
    function __formatDebugLog($tag = '', $label = '', $data = null)
    {
        $tag = empty($tag) ? '' : "[{$tag}]";
        $label = empty($label) ? '' : "[{$label}]";

        if ($data != null) {
            $data = is_string($data) ? $data : json_encode($data);
        }

        return $tag . $label . '=> ' . json_encode($data);
    }
}


/**
 * getClientDynamicIP function
 *
 * @return string
 */
function getClientDynamicIP()
{
    if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
        return $_SERVER["HTTP_X_FORWARDED_FOR"];
    } else if (array_key_exists('REMOTE_ADDR', $_SERVER)) {
        return $_SERVER["REMOTE_ADDR"];
    } else if (array_key_exists('HTTP_CLIENT_IP', $_SERVER)) {
        return $_SERVER["HTTP_CLIENT_IP"];
    }

    return '';
}


/**
 * Undocumented function
 *
 * @param string $url
 * @param string $method
 * @param string $fields
 * @param string $token
 * @return mixed
 */
function callCurl($url, $method)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => $method,
        // CURLOPT_POSTFIELDS => $fields,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            // 'Authorization: Bearer ' . $token
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    return $response;
}

function masking($number, $maskingCharacter = '*')
{
    return substr($number, 0, 2) . str_repeat($maskingCharacter, strlen($number) - 4) . substr($number, -2);
}

function encryption($plaintext)
{
    $password = config('app.encryption_decryption_password');
    $method = config('app.encryption_decryption_method');
    $key = hash('sha256', $password, true);
    $iv = openssl_random_pseudo_bytes(16);

    $ciphertext = openssl_encrypt($plaintext, $method, $key, OPENSSL_RAW_DATA, $iv);
    $hash = hash_hmac('sha256', $ciphertext . $iv, $key, true);

    return base64_encode($iv . $hash . $ciphertext);
}

function decryption($ivHashCiphertext)
{
    $password = config('app.encryption_decryption_password');
    $method = config('app.encryption_decryption_method');
    $ivHashCiphertext = base64_decode($ivHashCiphertext);
    $iv = substr($ivHashCiphertext, 0, 16);
    $hash = substr($ivHashCiphertext, 16, 32);
    $ciphertext = substr($ivHashCiphertext, 48);
    $key = hash('sha256', $password, true);

    if (!hash_equals(hash_hmac('sha256', $ciphertext . $iv, $key, true), $hash))
        return null;

    return openssl_decrypt($ciphertext, $method, $key, OPENSSL_RAW_DATA, $iv);
}

function decryptBase64Attachments($file)
{
    $result = [
        'raw_file' => null,
        'extension' => null,
    ];
    try {
        $extension = explode('/', explode(':', substr($file, 0, strpos($file, ';')))[1])[1];   // .jpg .png .pdf
        $replace = substr($file, 0, strpos($file, ',') + 1);

        // find substring fro replace here eg: data:image/png;base64,
        $rawFile = str_replace($replace, '', $file);
        $rawFile = str_replace(' ', '+', $rawFile);
        $result['raw_file'] = base64_decode($rawFile);
        $result['extension'] = $extension;

        return $result;
    } catch (Exception $e) {
        return $result;
    }
}

function misMatchData($modelData, $requestData)
{
    $data = [];

    $current = array_diff_assoc($requestData, $modelData);
    $previous = [];
    if (!empty($current)) {
        foreach ($current as $key => $value) {
            $previous[$key] = isset($modelData[$key]) ? $modelData[$key] : null;
        }
    }

    $data['previous'] = $previous;
    $data['current'] = $current;
    return $data;
}


function makeHttpRequest($url, $method, $options = [])
{
    $return = [
        'success' => false,
        'message' => 'Something went wrong.',
        'response' => null,
    ];

    $method = strtolower($method);

    if (!in_array($method, ['get', 'post', 'put'])) {
        $return['message'] = 'Unsupported request method.';
        return $return;
    }

    try {
        $ch = curl_init();


        // set default curl options.
        $curlopt = [
            CURLOPT_URL => $url,
            CURLOPT_TIMEOUT => 180,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_ENCODING => "",
            CURLOPT_AUTOREFERER => true, //has understanding issue
            CURLOPT_HEADER => false,
            // CURLOPT_SSL_VERIFYHOST => false,
            // CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        ];


        // http headers
        $headers = [];

        if ($method === 'post' || $method === 'put') {
            $method_opt = $method === 'post' ? CURLOPT_POST : CURLOPT_PUT;


            $curlopt[$method_opt] = true;
            $curlopt[CURLOPT_CUSTOMREQUEST] = strtoupper($method);

            if (!empty($options['data'])) {
                $curlopt[CURLOPT_POSTFIELDS] = $options['data'];
            } else if (!empty($options['json'])) {
                $json = is_string($options['json']) ? trim($options['json']) : json_encode($options['json']);

                $curlopt[CURLOPT_POSTFIELDS] = $json;

                $headers['Accept'] = 'application/json';
                $headers['Content-Type'] = 'application/json';
            }
        } // endif post || put

        // merge user specified curl options
        if (isset($options['curl'])) {
            $curlopt = $options['curl'] + $curlopt; // priority to user defined $options['curl']
        }

        // merge all headers
        if (!empty($options['headers'])) {
            $headers = $options['headers'] + $headers;
        }
        if (!empty($options['curl'][CURLOPT_HTTPHEADER])) {
            $headers = $options['curl'][CURLOPT_HTTPHEADER] + $headers;
        }

        // set headers
        array_walk($headers, function (&$v, $k) {
            $v = "{$k}: $v";
        }); // transform ['key' => 'val'] to ['key: val'].
        $curlopt[CURLOPT_HTTPHEADER] = array_values($headers);

        curl_setopt_array($ch, $curlopt);

        $response = curl_exec($ch);



        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);


        if ($error = curl_error($ch)) {

            $error_no = curl_errno($ch);
            curl_close($ch);
            $return['message'] = "cURL error [{$error_no}]: $error";
            return $return;
        }

        curl_close($ch);

        if (!empty($options['callbacks']['response'])) {
            $fn = $options['callbacks']['response'];
            $response = $fn($response);
        }
        $return['success'] = true;
        $return['message'] = "Success";
        $return['response'] = $response;

        $return['code'] = $httpcode;
    } catch (\Exception $e) {
        // Log::error("[ " . request()->requestId . " ] " . " [ curl error for ] " . $url);
        $return['message'] = "Exception: " . $e->getMessage();
    }

    return $return;
}
