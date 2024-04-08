<?php

namespace App\Report;

use App\Models\AccountPlan;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class DashboardReport
{
    /**
     * Handle the dashboard report.
     */
    public static function handle(array $data): Collection
    {
        $endDate = data_get($data, 'end_date');
        $startDate = data_get($data, 'start_date');

        $endDate = empty($endDate) ? Carbon::now() : Carbon::parse($endDate);
        $startDate = empty($startDate) ? Carbon::now()->subMonth() : Carbon::parse($startDate);

        $transactions = self::getTransactions($startDate, $endDate);

        $transactions = self::groupByOperation($transactions);

        return self::formatReport($transactions);
    }

    /**
     * Get the transactions.
     */
    private static function getTransactions(Carbon $startDate, Carbon $endDate): Collection
    {
        $transactions = Transaction::query()
            ->whereBetween('date', [$startDate, $endDate])
            ->get()->toArray();

        return collect($transactions)->groupBy('account_plan_id');
    }

    /**
     * Group the transactions by operation.
     */
    private static function groupByOperation(Collection $transactions): Collection
    {
        return $transactions->map(fn ($item) => $item->groupBy('operation'));
    }

    /**
     * Format the report.
     */
    private static function formatReport(Collection $transactions): Collection
    {
        return $transactions->map(function ($item, $key) {
            $accountPlan = AccountPlan::find($key);

            return [
                'name' => $accountPlan->name,
                'transactions' => $item->map(fn ($item, $key) => [
                    'operation' => $key,
                    'amount' => $item->sum('amount'),
                ])->values()->toArray(),
            ];
        })->values();
    }
}
