<?php

namespace Modules\Smsconfig\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateRouteRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
          'operator_prefix'=>'required',
          'channel_id'=>'required',
          'has_mask'=>'required',
          'default_mask'=>'required',
          'cost'=>'required',
          'success_rate'=>'required'
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
