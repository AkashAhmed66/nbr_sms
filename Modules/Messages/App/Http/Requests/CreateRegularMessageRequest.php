<?php

namespace Modules\Messages\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateRegularMessageRequest extends FormRequest
{
  /**
   * Get the validation rules that apply to the request.
   */
  public function rules(): array
  {
    return [
      'sender_id' => 'required',
      'content_type' => 'required',
      'recipient_number' => 'required',
      'message_text' => 'required',
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
