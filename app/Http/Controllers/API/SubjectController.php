<?php

namespace App\Http\Controllers\API;

use App\Models\Post\Post;
use App\Models\Subject;
use App\Support\ApiCache;
use App\Support\PublicContent;
use App\Support\SubjectFieldMap;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubjectController extends BaseController
{
    /**
     * Majors / subjects for the website.
     */
    public function index(Request $request)
    {
        $payload = ApiCache::remember(ApiCache::requestKey($request, 'subjects.index'), function () use ($request) {
            $perPage = (int) $request->input('per_page', 100);
            $perPage = $perPage > 0 && $perPage <= 200 ? $perPage : 100;

            $query = Subject::query()
                ->where(function ($q) {
                    $q->where('status', 1)->orWhereNull('status');
                })
                ->withCount([
                    'posts as universities_count' => function ($q) {
                        $q->where('post_type', 'university');
                    },
                ])
                ->orderBy('name');

            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where('name', 'like', '%' . $search . '%');
            }

            if ($request->boolean('with_universities')) {
                $query->whereHas('posts', function ($q) {
                    $q->where('post_type', 'university');
                });
            }

            $paginator = $query->paginate($perPage);
            $items = collect($paginator->items())->map(function ($subject, $index) {
                return $this->mapSubject($subject, $index);
            });

            return [
                'subjects' => [
                    'data' => $items,
                    'total' => $paginator->total(),
                    'current_page' => $paginator->currentPage(),
                    'per_page' => $paginator->perPage(),
                    'last_page' => $paginator->lastPage(),
                ],
            ];
        });

        return $this->sendResponse($payload, 'successfully.', 60);
    }

    /**
     * Single major by id or slug, with related universities.
     */
    public function show($id)
    {
        $payload = ApiCache::remember('subjects.show:' . md5((string) $id), function () use ($id) {
            $subject = $this->resolveSubject($id);

            if (!$subject) {
                return null;
            }

            $status = $subject->status;
            if (!($status === null || (int) $status === 1)) {
                return null;
            }

            $subject->loadCount([
                'posts as universities_count' => function ($q) {
                    $q->where('post_type', 'university');
                    PublicContent::scopeActive($q);
                },
            ]);

            $universities = Post::query()
                ->with(['categories:id,name,slug', 'tags:id,name,slug', 'subjects:id,name', 'postMetas'])
                ->where('post_type', 'university')
                ->whereHas('subjects', function ($q) use ($subject) {
                    $q->where('subjects.id', $subject->id);
                })
                ->orderByDesc('id')
                ->limit(40);
            PublicContent::scopeActive($universities);
            $universities = PublicContent::sanitizePosts($universities->get())->values();

            return [
                'subject' => $this->mapSubject($subject),
                'universities' => $universities,
            ];
        });

        if (!$payload) {
            return $this->sendError('Subject not found.', ['error' => 'Subject not found.'], 404);
        }

        return $this->sendResponse($payload, 'successfully.', 60);
    }

    /**
     * @param  string|int  $id
     * @return Subject|null
     */
    private function resolveSubject($id)
    {
        $raw = (string) $id;

        if (ctype_digit($raw)) {
            return Subject::find($raw);
        }

        $slug = Str::slug($raw);

        return Subject::all()->first(function ($subject) use ($slug, $raw) {
            return Str::slug($subject->name) === $slug
                || strcasecmp($subject->name, $raw) === 0;
        });
    }

    /**
     * @param  Subject  $subject
     * @param  int  $index
     * @return array
     */
    private function mapSubject(Subject $subject, $index = 0)
    {
        $slug = Str::slug($subject->name);
        $field = SubjectFieldMap::guess($subject->name, $index);

        return [
            'id' => $subject->id,
            'slug' => $slug,
            'name' => $subject->name,
            'field' => $field,
            'degree' => null,
            'universities' => (int) ($subject->universities_count ?? 0),
            'uni_count' => (int) ($subject->universities_count ?? 0),
            'parent_id' => $subject->parent_id,
            'status' => $subject->status,
        ];
    }
}
