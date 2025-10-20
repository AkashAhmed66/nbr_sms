<?php

namespace Modules\Menus\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateMenuRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:50|unique:menus',
            'route_name' => 'nullable|unique:menus',
            'active_route' => 'required',
            'order_no' => 'required|numeric',
            'status' => 'required|in:Active,Inactive',
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
