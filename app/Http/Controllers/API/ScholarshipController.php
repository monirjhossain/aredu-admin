<?php

namespace App\Http\Controllers\API;

use App\Models\Post\Post;
use App\Support\ApiCache;
use App\Support\PublicContent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ScholarshipController extends BaseController
{
    /**
     * Scholarship posts (post_type=scholarship).
     */
    public function index(Request $request)
    {
        $payload = ApiCache::remember(ApiCache::requestKey($request, 'scholarships.index'), function () use ($request) {
            $perPage = (int) $request->input('per_page', 50);
            $perPage = $perPage > 0 && $perPage <= 100 ? $perPage : 50;

            $query = Post::query()
                ->with(['postMetas', 'tags:id,name,slug'])
                ->where('post_type', 'scholarship')
                ->orderBy('post_title');

            PublicContent::scopeActive($query);

            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('post_title', 'like', '%' . $search . '%')
                        ->orWhere('post_content', 'like', '%' . $search . '%')
                        ->orWhere('post_name', 'like', '%' . $search . '%');
                });
            }

            if ($request->filled('status')) {
                $query->where('status', $request->input('status'));
            }

            if ($request->filled('degree')) {
                $degree = $request->input('degree');
                $query->whereHas('postMetas', function ($q) use ($degree) {
                    $q->where('meta_key', 'degree')->where('meta_value', 'like', '%' . $degree . '%');
                });
            }

            if ($request->filled('scholarship_type')) {
                $type = $request->input('scholarship_type');
                $query->whereHas('postMetas', function ($q) use ($type) {
                    $q->where('meta_key', 'scholarship_type')->where('meta_value', 'like', '%' . $type . '%');
                });
            }

            $paginator = $query->paginate($perPage);
            $items = collect($paginator->items())->map(function ($post) {
                return $this->mapScholarship(PublicContent::sanitizePost($post));
            });

            return [
                'scholarships' => [
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
     * Single scholarship by id or slug.
     */
    public function show($id)
    {
        $payload = ApiCache::remember('scholarships.show:' . md5((string) $id), function () use ($id) {
            $post = $this->resolvePost($id);

            if (!$post || $post->post_type !== 'scholarship' || !PublicContent::isActive($post)) {
                return null;
            }

            $post->load(['postMetas', 'tags:id,name,slug']);
            PublicContent::sanitizePost($post);

            $universities = $this->relatedUniversities($post);

            return [
                'scholarship' => $this->mapScholarship($post),
                'universities' => PublicContent::sanitizePosts($universities)->values(),
            ];
        });

        if (!$payload) {
            return $this->sendError('Scholarship not found.', ['error' => 'Scholarship not found.'], 404);
        }

        return $this->sendResponse($payload, 'successfully.', 60);
    }

    /**
     * Prefer city-matched universities; fall back to scholarship-tagged ones.
     *
     * @param  Post  $post
     * @return \Illuminate\Support\Collection
     */
    private function relatedUniversities(Post $post)
    {
        $city = trim((string) $post->getMeta('city', ''));
        $base = Post::query()
            ->with(['categories:id,name,slug', 'tags:id,name,slug', 'subjects:id,name', 'postMetas'])
            ->where('post_type', 'university');
        PublicContent::scopeActive($base);

        if ($city !== '') {
            $slug = Str::slug($city);
            $byCity = (clone $base)
                ->whereHas('categories', function ($q) use ($city, $slug) {
                    $q->where('categories.name', $city)
                        ->orWhere('categories.slug', $slug)
                        ->orWhere('categories.name', 'like', '%' . $city . '%');
                })
                ->orderByDesc('id')
                ->limit(12)
                ->get();

            if ($byCity->count()) {
                return $byCity;
            }
        }

        return $base
            ->where(function ($q) {
                $q->whereHas('postMetas', function ($mq) {
                    $mq->where('meta_key', 'scholarship')
                        ->whereIn('meta_value', ['1', 'yes', 'true', 'Yes', 'True']);
                })
                    ->orWhereHas('tags', function ($tq) {
                        $tq->where('name', 'like', '%scholarship%');
                    })
                    ->orWhere('post_title', 'like', '%scholarship%');
            })
            ->orderByDesc('id')
            ->limit(12)
            ->get();
    }

    /**
     * @param  string|int  $id
     * @return Post|null
     */
    private function resolvePost($id)
    {
        $raw = (string) $id;

        if (ctype_digit($raw)) {
            return Post::find($raw);
        }

        if (preg_match('/^(\d+)/', $raw, $m)) {
            $post = Post::find($m[1]);
            if ($post) {
                return $post;
            }
        }

        return Post::where('post_name', $raw)->first();
    }

    /**
     * @param  Post  $post
     * @return array
     */
    private function mapScholarship(Post $post)
    {
        $coverage = $post->getMeta('coverage', 'Varies');
        $uniCount = (int) $post->getMeta('universities', 0);
        $desc = $post->post_excerpt ?: strip_tags((string) $post->post_content);

        return [
            'id' => $post->id,
            'slug' => $post->post_name,
            'name' => $post->post_title,
            'coverage' => $coverage,
            'universities' => $uniCount,
            'desc' => $desc,
            'content' => $post->post_content,
            'image' => $post->image_url,
            'status' => $post->status,
            'city' => $post->getMeta('city', ''),
            'program_id' => $post->getMeta('program_id', ''),
            'degree' => $post->getMeta('degree', ''),
            'teaching_language' => $post->getMeta('teaching_language', ''),
            'intake' => $post->getMeta('intake', ''),
            'scholarship_type' => $post->getMeta('scholarship_type', ''),
            'deadline' => $post->getMeta('deadline', ''),
        ];
    }
}
