<?php

namespace App\Queries;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OutboxQuery
{
    /**
     * Build a performant, sargable query. Ensure indexed where-clauses.
     */
    public static function build(array $filters, array $columns)
    {
        $q = DB::table('outbox as o');

        Log::channel('exports')->info('Type -> ' . $filters['type']);
        if(!empty($filters['type'])) {
            if($filters['type'] == 'archive_failed' || $filters['type'] == 'archive') {
                $q = DB::table('outbox_history as o');

            }
        }

        $q->select(
            'o.id',
            'o.user_id',
            'o.destmn',
            'o.mask',
            'o.message',
            'o.write_time',
            'o.smscount',
            'o.sms_cost',
            'o.dlr_status_code'
        )
        ->whereBetween('o.write_time', [$filters['date_from'], $filters['date_to']]);

        $campaignId = $filters['campaign_id'] ?? null;
        if ($campaignId) {
            // If no campaign_id provided, return empty query
            Log::channel('exports')->info('Outbox export query'.$campaignId);

            $q->join('sentmessages as s', 'o.reference_id', '=', 's.id')
              ->whereNotNull('s.campaign_id')
              ->where('s.campaign_id', $campaignId);
        }

        if (!empty($filters['user_id'])) {
            $q->where('o.user_id', $filters['user_id'] ?? $filters['user']['id']);
        } else {
            if ($filters['user']['id_user_group'] == 2) {
                $userIds = DB::table('users')->where('created_by', $filters['user']['id'])->pluck('id')->toArray();
                $userIds[] = $filters['user']['id'];

                $q->whereIn('o.user_id', $userIds);
            } else if ($filters['user']['id_user_group'] == 3) {
                $q->where('o.user_id', $filters['user']['id']);
            }
        }

        if (!empty($filters['mask'])) {
            $q->where('o.mask', $filters['mask']);
        }
        if (!empty($filters['operator_prefix'])) {
            $q->where('o.operator_prefix', $filters['operator_prefix']);
        }
        if (isset($filters['status']) && $filters['status'] !== '') {
            $q->where('o.status', $filters['status']);
        }
        if (!empty($filters['destmn'])) {
            $q->where('o.destmn', $filters['destmn']);
        }

        if(!empty($filters['type'])) {
            if($filters['type'] == 'archive_failed' || $filters['type'] == 'normal_failed') {
                $q->where('o.dlr_status_code', '!=', "Delivered")->orWhereNull('o.dlr_status_code');
            }
        }

        Log::channel('exports')->info('Outbox export query', ['query' => $q->toSql(), 'bindings' => $q->getBindings()]);

        return $q->orderBy('o.id'); // stable ordering for chunking
    }

}
