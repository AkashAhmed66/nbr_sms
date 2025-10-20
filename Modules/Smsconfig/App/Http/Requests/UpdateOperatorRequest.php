<?php

namespace Modules\Smsconfig\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOperatorRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
			'full_name'=>'string|required|max:50',
			'short_name'=>'string|required|max:20',
			'prefix'=>'string|required',
			'country_id'=>'required|numeric',
			'ton' => 'nullable',
            'npi' => 'nullable'
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
