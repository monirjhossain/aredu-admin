<?php

namespace App\Http\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class InvoiceIndexFilterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'student' => ['nullable', 'integer', 'exists:students,id'],
            'search' => ['nullable', 'string', 'max:255'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'branch_id' => ['nullable', 'integer', 'exists:branches,id'],
            'perpage' => ['nullable', 'integer', 'in:5,10,15,20,25,50,100,200,500'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $dateFrom = $this->input('date_from', $this->input('start_date'));
        $dateTo = $this->input('date_to', $this->input('end_date'));

        $this->merge([
            'date_from' => $this->normalizeDate($dateFrom),
            'date_to' => $this->normalizeDate($dateTo),
        ]);
    }

    private function normalizeDate($value): ?string
    {
        if (!$value) {
            return null;
        }

        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }
}
