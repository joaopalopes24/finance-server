<?php

namespace App\Http\Requests\AccountPlan;

use App\Models\AccountPlan;
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
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(AccountPlan::class, 'name'),
            ],
            'status' => [
                'required',
                'boolean',
            ],
        ];
    }
}
