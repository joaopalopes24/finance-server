<?php

namespace App\Http\Resources\Transaction;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'cost_center_id' => $this->cost_center_id,
            'account_plan_id' => $this->account_plan_id,
            'description' => $this->description,
            'amount' => $this->amount,
            'date' => $this->date,
            'operation' => $this->operation,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    /**
     * Get any additional data that should be returned with the resource array.
     */
    public function with(Request $request): array
    {
        return ['message' => trans('messages.transaction.store')];
    }
}
