<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ScbNotifyPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            

        ];
    }


    public function messages()
    {
        return [
        ];
    }

    /**
     * failedValidation function
     *
     * @param Validator $validator
     * @return void
     */
    public function failedValidation(Validator $validator)
    {
        $errors = [];
        if ($validator->fails()) {
            $errors = $validator->messages()->all();
            Log::info(__formatDebugLog(isset(request()->request_id) ? request()->request_id : null, 'Scb virtual card notifier required params are missing.', $errors));

            throw new HttpResponseException(response()->json(getResponseStatus('400', null, $errors)));
        }
    }
}
