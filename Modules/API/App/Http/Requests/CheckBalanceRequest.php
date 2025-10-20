<?php

namespace Modules\API\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckBalanceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
          'username' => 'required',
          'password' => 'required',
          'clienttransid' => 'required|alpha_num',
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
