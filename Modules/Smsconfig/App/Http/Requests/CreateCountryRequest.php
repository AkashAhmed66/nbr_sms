<?php

namespace Modules\Smsconfig\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCountryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
          'name' => 'required',
          'nickname' => 'required',
          'phonecode' => 'required',
          'iso' => 'nullable',
          'iso3' => 'nullable',
          'numcode' => 'nullable'
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
