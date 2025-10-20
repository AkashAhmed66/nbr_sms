<?php

namespace Modules\Phonebook\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePhonebookRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'group_id' => 'required|numeric|max:255',
            'name_en' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|string|max:255',
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
