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
            'subject' => ['nullable', 'integer', 'exists:subjects,id'],
            'export' => ['nullable', 'in:yes'],
        ];
    }

    public function universityId(): int
    {
        return (int) $this->input('applicationlist');
    }

    public function applicationStatusId(): ?int
    {
        return UniversityApplicationListQuery::resolveOptionalId(
            $this->input('application_status')
        );
    }

    public function subjectId(): ?int
    {
        return UniversityApplicationListQuery::resolveOptionalId(
            $this->input('subject')
        );
    }

    /**
     * @return array<string, int|string>
     */
    public function baseRouteQuery(): array
    {
        return [
            'universitylist' => 1,
            'applicationlist' => $this->universityId(),
        ];
    }

    /**
     * @return array<string, int|string>
     */
    public function routeQuery(): array
    {
        return array_merge(
            $this->baseRouteQuery(),
            array_filter([
                'application_status' => $this->applicationStatusId(),
                'subject' => $this->subjectId(),
            ], static fn ($value) => $value !== null)
        );
    }
}
