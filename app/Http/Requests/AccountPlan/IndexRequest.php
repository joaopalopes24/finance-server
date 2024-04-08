<?php

namespace App\Http\Requests\AccountPlan;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'search' => [
                'nullable',
                'string',
            ],
            'page' => [
                'nullable',
                'integer',
                'min:1',
            ],
            'limit' => [
                'nullable',
                'integer',
                Rule::in([10, 25, 50, 100]),
            ],
            'field' => [
                'nullable',
                'string',
                Rule::in(['name']),
            ],
            'sort' => [
                'nullable',
                'string',
                Rule::in(['asc', 'desc']),
            ],
        ];
    }

    /**
     * Get the validated data from the request.
     */
    public function values(): array
    {
        $search = $this->input('search');

        $sort = $this->input('sort', 'desc');

        $field = $this->input('field', 'created_at');

        return [$sort, $field, $search];
    }
}
