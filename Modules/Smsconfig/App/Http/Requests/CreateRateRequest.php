<?php

namespace Modules\Smsconfig\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CreateRateRequest extends FormRequest
{
  /**
   * Get the validation rules that apply to the request.
   */
  public function rules(): array
  {
    return [
      'rate_name' => [
        'required',
        Rule::unique('rates')->where(fn($query) => $query->where('reseller_id', Auth::user()->reseller_id))
      ],
      //'rate_type' => 'required',
      'masking_rate' => 'required',
      'nonmasking_rate' => 'required'
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
