<?php

namespace Modules\Smsconfig\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOperatorRequest extends FormRequest
{
  /**
   * Get the validation rules that apply to the request.
   */
  public function rules(): array
  {
    return [
      'full_name' => 'string|required|max:50|unique:operator',
      'short_name' => 'string|required|max:20|unique:operator',
      'prefix' => 'string|required',
      'country_id' => 'required|numeric',
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
