<?php

namespace App\Http\Requests\Campaign;

use App\Models\Campaign;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CampaignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'status' => ['required', Rule::in([Campaign::STATUS_ACTIVE, Campaign::STATUS_CLOSED])],
            'note' => ['nullable', 'string', 'max:2000'],
            'offers' => ['required', 'array', 'min:1'],
            'offers.*.id' => ['nullable', 'integer'],
            'offers.*.label' => ['nullable', 'string', 'max:80'],
            'offers.*.discount_amount' => ['required', 'integer', 'min:1', 'max:1000000'],
            'offers.*.max_claims' => ['required', 'integer', 'min:1', 'max:100000'],
            'offers.*.is_active' => ['nullable', 'boolean'],
        ];
    }
}
