<?php

namespace Modules\Smsconfig\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSenderIdRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
          'senderID' => 'required|min:13|max:13|unique:senderid,senderID',
          'count' => 'required|numeric',
          'user_id' => 'required|exists:users,id',
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
