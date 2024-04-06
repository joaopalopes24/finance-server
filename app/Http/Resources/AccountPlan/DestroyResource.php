<?php

namespace App\Http\Resources\AccountPlan;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DestroyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
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
        return ['message' => trans('messages.account_plan.destroy')];
    }
}
