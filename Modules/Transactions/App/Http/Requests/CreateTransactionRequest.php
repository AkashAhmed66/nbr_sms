<?php

namespace Modules\Transactions\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTransactionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
          'available_balance' => 'required|numeric',
          'non_masking_balance' => 'required',
          'masking_balance' => 'required',
          'email_balance' => 'required'
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
