<?php

namespace Modules\Transactions\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CreateUserWalletRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
          'user_id' => 'required',
          'balance' => 'required',
          'balance_type' => 'required'
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $loggedInUser = Auth::user();

            if ($loggedInUser->id_user_group == 2) {
                // Get logged-in user's available balance
                $availableBalance = $loggedInUser->available_balance;

                // Requested balance to add
                $requestedBalance = $this->input('balance');

                if ($requestedBalance > $availableBalance) {
                    $validator->errors()->add(
                        'balance',
                        'You cannot add more than your available balance.'
                    );
                }
            }
        });
    }
}
