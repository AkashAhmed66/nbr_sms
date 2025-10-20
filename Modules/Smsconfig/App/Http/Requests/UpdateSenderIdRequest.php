<?php

namespace Modules\Smsconfig\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSenderIdRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
          'senderID' => 'required|min:13|max:13',
          'count' => 'required|numeric'
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
