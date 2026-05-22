<?php

namespace App\Http\Requests\Student;

use App\Queries\UniversityApplicationListQuery;
use Illuminate\Foundation\Http\FormRequest;

class UniversityApplicationListFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'universitylist' => ['required', 'in:1'],
            'applicationlist' => ['required', 'integer', 'exists:posts,id'],
            'application_status' => ['nullable', 'integer', 'exists:university_statuses,id'],
            'export' => ['nullable', 'in:yes'],
        ];
    }

    public function universityId(): int
    {
        return (int) $this->input('applicationlist');
    }

    public function applicationStatusId(): ?int
    {
        return UniversityApplicationListQuery::resolveApplicationStatusId(
            $this->input('application_status')
        );
    }
}
