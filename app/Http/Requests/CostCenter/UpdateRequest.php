<?php

namespace App\Http\Requests\CostCenter;

use App\Models\CostCenter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
                Rule::unique(CostCenter::class, 'name')->ignore($this->costCenter),
            ],
            'status' => [
                'required',
                'boolean',
            ],
        ];
    }
}
