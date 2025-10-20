<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ChartsController extends Controller
{
    public function lineChart(): JsonResponse
    {
        $startDate = now('Asia/Dhaka')->startOfMonth();
        $endDate = now('Asia/Dhaka')->endOfDay();

        $outboxData = DB::table('outbox')
            ->selectRaw('DATE(created_at) as date, SUM(smscount) as total')
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->pluck('total', 'date');

        $historyData = DB::table('outbox_history')
            ->selectRaw('DATE(created_at) as date, SUM(smscount) as total')
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->pluck('total', 'date');


        if(Auth::user()->id_user_group != 1){
            $users = DB::table('users')->where('created_by', Auth::user()->id)->pluck('id')->toArray();
            $users[] = Auth::user()->id;

            $outboxData = DB::table('outbox')
                ->selectRaw('DATE(created_at) as date, SUM(smscount) as total')
                ->where('created_at', '>=', $startDate)
                ->where('created_at', '<=', $endDate)
                ->whereIn('user_id', $users)
                ->groupBy(DB::raw('DATE(created_at)'))
                ->pluck('total', 'date');

            $historyData = DB::table('outbox_history')
                ->selectRaw('DATE(created_at) as date, SUM(smscount) as total')
                ->where('created_at', '>=', $startDate)
                ->where('created_at', '<=', $endDate)
                ->whereIn('user_id', $users)
                ->groupBy(DB::raw('DATE(created_at)'))
                ->pluck('total', 'date');
        }

        // dd(1);

        // Merge both datasets
        $merged = [];

        foreach ($outboxData as $date => $total) {
            $merged[$date] = ($merged[$date] ?? 0) + $total;
        }

        foreach ($historyData as $date => $total) {
            $merged[$date] = ($merged[$date] ?? 0) + $total;
        }

        // Fill missing days and prepare data
        $period = new \DatePeriod(
            new \DateTime($startDate),
            new \DateInterval('P1D'),
            (new \DateTime($endDate))->modify('+1 day')
        );

        $chartData = [];
        $categories = [];

        foreach ($period as $date) {
            $formatted = $date->format('Y-m-d');
            $chartData[] = $merged[$formatted] ?? 0;
            $categories[] = $date->format('M j'); // Nov 1, Oct 1, etc.
        }

        return response()->json([
            'data' => $chartData,
            'categories' => $categories,
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
        ]);
    }
}
