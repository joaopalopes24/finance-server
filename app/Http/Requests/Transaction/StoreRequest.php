<?php

namespace App\Http\Requests\Transaction;

use App\Enum\OperationEnum;
use App\Enum\StatusEnum;
use App\Models\AccountPlan;
use App\Models\CostCenter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'cost_center_id' => [
                'required',
                'integer',
                Rule::exists(CostCenter::class, 'id'),
            ],
            'account_plan_id' => [
                'required',
                'integer',
                Rule::exists(AccountPlan::class, 'id'),
            ],
            'description' => [
                'required',
                'string',
                'max:255',
            ],
            'amount' => [
                'required',
                'numeric',
                'min:0',
            ],
            'date' => [
                'required',
                'date',
            ],
            'operation' => [
                'required',
                'integer',
                Rule::enum(OperationEnum::class),
            ],
            'status' => [
                'required',
                'integer',
                Rule::enum(StatusEnum::class),
            ],
        ];
    }

    /**
     * Handle a passed validation attempt.
     */
    protected function passedValidation(): void
    {
        $date = $this->date('date');

        $this->offsetSet('date', $date->format('Y-m-d'));
    }
}
