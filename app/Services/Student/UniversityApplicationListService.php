<?php

namespace App\Services\Student;

use App\Http\Requests\Student\UniversityApplicationListFilterRequest;
use App\Models\Post\Post;
use App\Models\Student\Student;
use App\Models\UniversityStatus;
use App\Queries\UniversityApplicationListQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class UniversityApplicationListService
{
    /**
     * @return array{
     *     university: Post,
     *     students: Collection,
     *     application_status_filter: int|null,
     *     filtered_status_name: string|null,
     *     title: string,
     *     type: string,
     *     url: string
     * }
     */
    public function listViewData(UniversityApplicationListFilterRequest $filterRequest): array
    {
        $universityId = $filterRequest->universityId();
        $applicationStatusId = $filterRequest->applicationStatusId();
        $university = Post::findOrFail($universityId);

        $students = UniversityApplicationListQuery::make(
            $universityId,
            $applicationStatusId,
            $this->branchIdForCurrentUser()
        )
            ->with(array_merge(
                ['studentCscaExams.cscaExam'],
                UniversityApplicationListQuery::listEagerLoads($universityId)
            ))
            ->get();

        $filteredStatusName = $this->resolveStatusName($applicationStatusId);

        return [
            'university' => $university,
            'students' => $students,
            'application_status_filter' => $applicationStatusId,
            'filtered_status_name' => $filteredStatusName,
            'title' => UniversityApplicationListQuery::pageTitle($university, $filteredStatusName),
            'type' => 'report',
            'url' => 'students',
        ];
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function exportRows(UniversityApplicationListFilterRequest $filterRequest): array
    {
        $universityId = $filterRequest->universityId();
        $applicationStatusId = $filterRequest->applicationStatusId();
        $university = Post::findOrFail($universityId);

        $students = UniversityApplicationListQuery::make(
            $universityId,
            $applicationStatusId,
            $this->branchIdForCurrentUser()
        )
            ->with(UniversityApplicationListQuery::exportEagerLoads($universityId))
            ->get();

        return UniversityApplicationListQuery::toExportRows($students, $university);
    }

    public function exportFilename(UniversityApplicationListFilterRequest $filterRequest): string
    {
        $university = Post::findOrFail($filterRequest->universityId());
        $statusName = $this->resolveStatusName($filterRequest->applicationStatusId());

        return UniversityApplicationListQuery::exportFilename($university, $statusName);
    }

    public function resolveFilterRequest(Request $request): UniversityApplicationListFilterRequest
    {
        /** @var UniversityApplicationListFilterRequest $filterRequest */
        $filterRequest = UniversityApplicationListFilterRequest::createFrom($request);
        $filterRequest->setContainer(app())->setRedirector(app('redirect'))->validateResolved();

        return $filterRequest;
    }

    private function branchIdForCurrentUser(): ?int
    {
        $user = Auth::user();
        if (!$user) {
            return null;
        }

        $roleType = optional($user->role)->user_type;
        if (in_array($roleType, ['Admin', 'Super Admin'], true)) {
            return null;
        }

        return (int) $user->branch ?: null;
    }

    private function resolveStatusName(?int $statusId): ?string
    {
        if ($statusId === null) {
            return null;
        }

        return UniversityStatus::whereKey($statusId)->value('name');
    }
}
