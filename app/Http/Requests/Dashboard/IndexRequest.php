<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'start_date' => [
                'nullable',
                'date',
            ],
            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],
        ];
    }

    // /**
    //  * Handle a passed validation attempt.
    //  */
    // protected function passedValidation(): void
    // {
    //     $endDate = $this->date('end_date');
    //     $startDate = $this->date('start_date');

    //     $this->offsetSet('end_date', $endDate?->format('Y-m-d'));
    //     $this->offsetSet('start_date', $startDate?->format('Y-m-d'));
    // }
}
