<?php

namespace Modules\Phonebook\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateDndRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'phone' => 'required|string|max:255',
            'status' => 'required|string|max:255'
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
