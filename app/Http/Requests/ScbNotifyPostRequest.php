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
            'groupId' => ['required', 'string'],
            'accountIdentifier.currencyCode.isoCode' => ['required', 'string', 'size:3'],
            'accountIdentifier.bankCode' => ['required', 'string'],
            'transactionIdentifier.type' => ['required', 'string'],
            'transactionIdentifier.identifier' => ['required', 'string'],
            'adviceType' => ['required', 'string', Rule::in(['Credit', 'Debit'])],
            'virtualAccountId' => ['nullable', 'string'],
            'transactionAmount.currencyCode' => ['required', 'string', 'size:3'],
            'transactionAmount.amount' => ['required', 'numeric', 'gt:0'],
            'clientIdentifier.type' => ['required', 'string'],
            'clientIdentifier.identifier' => ['nullable', 'string'],
            'payerDetails.account.id' => ['required', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'groupId.required' => 'The groupId field is required.',
            'accountIdentifier.currencyCode.isoCode.required' => 'The currency code is required.',
            'accountIdentifier.currencyCode.isoCode.size' => 'The currency code must be exactly 3 characters.',
            'accountIdentifier.bankCode.required' => 'The bank code is required.',
            'transactionIdentifier.type.required' => 'The transaction identifier type is required.',
            'transactionIdentifier.identifier.required' => 'The transaction identifier is required.',
            'adviceType.required' => 'The advice type is required.',
            'adviceType.in' => 'The advice type must be either Credit or Debit.',
            'virtualAccountId.string' => 'The virtual account ID must be a string.',
            'transactionAmount.currencyCode.required' => 'The transaction currency code is required.',
            'transactionAmount.currencyCode.size' => 'The transaction currency code must be exactly 3 characters.',
            'transactionAmount.amount.required' => 'The transaction amount is required.',
            'transactionAmount.amount.numeric' => 'The transaction amount must be a number.',
            'transactionAmount.amount.gt' => 'The transaction amount must be greater than 0.',
            'clientIdentifier.type.required' => 'The client identifier type is required.',
            'payerDetails.account.id.required' => 'The payer account ID is required.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @return void
     * @throws HttpResponseException
     */
    public function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();
        Log::info('Scb virtual card notifier required params are missing.', $errors);

        throw new HttpResponseException(response()->json([
            'status' => 400,
            'message' => 'Validation failed.',
            'errors' => $errors,
        ], 400));
    }
}
