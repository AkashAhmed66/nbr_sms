<?php
namespace Modules\API\App\Http\Controllers\v1;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Modules\Messages\App\Models\Message;
use Modules\Users\App\Models\User;
use Modules\Messages\App\Models\SMSRecord;

class SmsStatusControlle extends Controller
{
    public function getSmsStatus(Request $request)
    {

        $messageId = $request->query('message_id');
        $userKey = $request->query('api_key');

        //get user id by API key
        $userInfo1 = User::where('APIKEY', $userKey)->first();
        $userInfo2 = Message::with('outboxMessage')->where('orderid', $messageId)->first();

        if (!$userInfo1 || !$userInfo2) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid API Key or Message ID.', 
            ], 404); // or 400 depending on your logic
        }
        if($userInfo1->id != $userInfo2->user_id){
            return response()->json([
                'status' => 'error',
                'message' => 'You are not permitted to see message status.',
            ], 400);
        }

        if (!$messageId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Message ID is required.',
            ], 400);
        }

        try {
            // dd($userInfo2->outboxMessage);

            $newArray = [];
            if(!empty($userInfo2->outboxMessage)){
                foreach ($userInfo2->outboxMessage as $msg) {
                    $newArray[] = [
                        'phone'     => $msg->destmn,
                        'status' => $msg->dlr_status ?? 'Message Submitted',
                        'date time' => $msg->updated_at->format('Y-m-d H:i:s'),
                    ];
                }
            }


            // $response = Http::get(env('SMS_STATUS_API_URL'), [
            //     'apikey' => $userInfo1->user_reve_api_key,
            //     'secretkey' => $userInfo1->user_reve_secret_key,
            //     'messageid' => $messageId,
            // ]);

            return response()->json([
                'status' => 'success',
                'message_id' => $messageId,
                'data' => $newArray,
            ]);
            // if ($response->successful()) {
            // } else {
            //     return response()->json([
            //         'status' => 'error',
            //         'message' => 'Failed to retrieve SMS status.',
            //         'details' => $response->body(),
            //     ], $response->status());
            // }
        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching the SMS status.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
