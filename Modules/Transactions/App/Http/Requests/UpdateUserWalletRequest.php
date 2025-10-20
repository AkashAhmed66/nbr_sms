<?php

namespace Modules\Transactions\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserWalletRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required',
            'balance' => 'required',
            'balance_type' => 'required'
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
