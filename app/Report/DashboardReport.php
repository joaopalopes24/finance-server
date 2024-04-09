<?php

namespace App\Report;

use App\Enum\StatusEnum;
use App\Models\AccountPlan;
use App\Models\Transaction;
use App\Support\Action;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class DashboardReport extends Action
{
    /**
     * The data.
     */
    private array $data;

    /**
     * Create a new report instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Handle the dashboard report.
     */
    public function execute(): Collection
    {
        $endDate = data_get($this->data, 'end_date');
        $startDate = data_get($this->data, 'start_date');

        $endDate = empty($endDate) ? Carbon::now() : Carbon::parse($endDate);
        $startDate = empty($startDate) ? Carbon::now()->subMonth() : Carbon::parse($startDate);

        $transactions = $this->getTransactions($startDate, $endDate);

        $transactions = $this->groupByOperation($transactions);

        return $this->formatReport($transactions);
    }

    /**
     * Get the transactions.
     */
    private function getTransactions(Carbon $startDate, Carbon $endDate): Collection
    {
        $transactions = Transaction::query()
            ->whereBetween('date', [$startDate, $endDate])
            ->where('status', StatusEnum::COMPLETED)
            ->get()->toArray();

        return collect($transactions)->groupBy('account_plan_id');
    }

    /**
     * Group the transactions by operation.
     */
    private function groupByOperation(Collection $transactions): Collection
    {
        return $transactions->map(fn ($item) => $item->groupBy('operation'));
    }

    /**
     * Format the report.
     */
    private function formatReport(Collection $transactions): Collection
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
