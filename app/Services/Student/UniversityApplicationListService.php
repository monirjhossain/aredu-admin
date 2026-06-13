<?php

namespace App\Services\Student;

use App\Http\Requests\Student\UniversityApplicationListFilterRequest;
use App\Models\Post\Post;
use App\Models\Subject;
use App\Models\UniversityStatus;
use App\Queries\UniversityApplicationListQuery;
use Illuminate\Database\Eloquent\Builder;
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
     *     subject_filter: int|null,
     *     subjects: Collection,
     *     filtered_status_name: string|null,
     *     filtered_subject_name: string|null,
     *     list_route_query: array<string, int|string>,
     *     reset_url: string,
     *     export_url: string,
     *     title: string,
     *     type: string,
     *     url: string
     * }
     */
    public function listViewData(UniversityApplicationListFilterRequest $filterRequest): array
    {
        $university = $this->university($filterRequest->universityId());
        $statusId = $filterRequest->applicationStatusId();
        $subjectId = $filterRequest->subjectId();
        $statusName = $this->resolveStatusName($statusId);
        $subjectName = $this->resolveSubjectName($subjectId);

        return [
            'university' => $university,
            'students' => $this->studentsQuery($filterRequest)
                ->with(array_merge(
                    ['studentCscaExams.cscaExam'],
                    UniversityApplicationListQuery::listEagerLoads($university->id)
                ))
                ->get(),
            'application_status_filter' => $statusId,
            'subject_filter' => $subjectId,
            'subjects' => UniversityApplicationListQuery::subjectsForUniversity($university->id),
            'filtered_status_name' => $statusName,
            'filtered_subject_name' => $subjectName,
            'list_route_query' => $filterRequest->routeQuery(),
            'reset_url' => route('students.index', $filterRequest->baseRouteQuery()),
            'export_url' => route('students.index', array_merge(
                $filterRequest->routeQuery(),
                ['export' => 'yes']
            )),
            'title' => UniversityApplicationListQuery::pageTitle($university, $statusName, $subjectName),
            'type' => 'report',
            'url' => 'students',
        ];
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function exportRows(UniversityApplicationListFilterRequest $filterRequest): array
    {
        $university = $this->university($filterRequest->universityId());

        $students = $this->studentsQuery($filterRequest)
            ->with(UniversityApplicationListQuery::exportEagerLoads($university->id))
            ->get();

        return UniversityApplicationListQuery::toExportRows($students, $university);
    }

    public function exportFilename(UniversityApplicationListFilterRequest $filterRequest): string
    {
        $university = $this->university($filterRequest->universityId());

        return UniversityApplicationListQuery::exportFilename(
            $university,
            $this->resolveStatusName($filterRequest->applicationStatusId()),
            $this->resolveSubjectName($filterRequest->subjectId())
        );
    }

    public function resolveFilterRequest(Request $request): UniversityApplicationListFilterRequest
    {
        /** @var UniversityApplicationListFilterRequest $filterRequest */
        $filterRequest = UniversityApplicationListFilterRequest::createFrom($request);
        $filterRequest->setContainer(app())->setRedirector(app('redirect'))->validateResolved();

        return $filterRequest;
    }

    private function studentsQuery(UniversityApplicationListFilterRequest $filterRequest): Builder
    {
        return UniversityApplicationListQuery::make(
            $filterRequest->universityId(),
            $filterRequest->applicationStatusId(),
            $this->branchIdForCurrentUser(),
            $filterRequest->subjectId()
        );
    }

    private function university(int $id): Post
    {
        return Post::findOrFail($id);
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

    private function resolveSubjectName(?int $subjectId): ?string
    {
        if ($subjectId === null) {
            return null;
        }

        return Subject::whereKey($subjectId)->value('name');
    }
}
