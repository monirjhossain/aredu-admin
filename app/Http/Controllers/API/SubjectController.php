<?php

namespace App\Http\Controllers\API;

use App\Models\Post\Post;
use App\Models\Subject;
use App\Support\ApiCache;
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

            $subject->loadCount([
                'posts as universities_count' => function ($q) {
                    $q->where('post_type', 'university');
                },
            ]);

            $universities = Post::query()
                ->with(['categories:id,name,slug', 'tags:id,name,slug', 'subjects:id,name'])
                ->where('post_type', 'university')
                ->whereHas('subjects', function ($q) use ($subject) {
                    $q->where('subjects.id', $subject->id);
                })
                ->orderByDesc('id')
                ->limit(40)
                ->get();

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
        $field = $this->guessField($subject->name, $index);

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

    /**
     * @param  string  $name
     * @param  int  $index
     * @return string
     */
    private function guessField($name, $index = 0)
    {
        $lower = strtolower($name);
        $groups = [
            'Engineering & Technology',
            'Computer Science & IT',
            'Business & Economics',
            'Agriculture & Life Sciences',
            'Chinese Language & Preparatory',
            'Education',
            'Social Sciences & Law',
            'Arts, Design & Media',
            'Natural Sciences',
            'Architecture & Urban Planning',
            'Humanities & Languages',
        ];

        if (preg_match('/engineer|technolog|mechanical|civil|electrical|automat/', $lower)) {
            return 'Engineering & Technology';
        }
        if (preg_match('/computer|software|data|artificial|iot|network/', $lower)) {
            return 'Computer Science & IT';
        }
        if (preg_match('/business|econom|trade|financ|account|mba|management/', $lower)) {
            return 'Business & Economics';
        }
        if (preg_match('/medic|pharm|nurs|dental|clinical|biolog|chemistry/', $lower)) {
            return 'Agriculture & Life Sciences';
        }
        if (preg_match('/chinese|language|hsk/', $lower)) {
            return 'Chinese Language & Preparatory';
        }
        if (preg_match('/education|teach/', $lower)) {
            return 'Education';
        }
        if (preg_match('/law|social|politic/', $lower)) {
            return 'Social Sciences & Law';
        }
        if (preg_match('/art|design|media|music/', $lower)) {
            return 'Arts, Design & Media';
        }

        return $groups[$index % count($groups)];
    }
}
