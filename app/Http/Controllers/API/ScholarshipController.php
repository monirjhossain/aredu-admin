<?php

namespace App\Http\Controllers\API;

use App\Models\Post\Post;
use App\Support\ApiCache;
use Illuminate\Http\Request;

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

            $paginator = $query->paginate($perPage);
            $items = collect($paginator->items())->map(function ($post) {
                return $this->mapScholarship($post);
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

            if (!$post || $post->post_type !== 'scholarship') {
                return null;
            }

            $post->load(['postMetas', 'tags:id,name,slug']);

            $universities = Post::query()
                ->with(['categories:id,name,slug', 'tags:id,name,slug'])
                ->where('post_type', 'university')
                ->where(function ($q) {
                    $q->where('post_title', 'like', '%scholarship%')
                        ->orWhere('post_content', 'like', '%scholarship%')
                        ->orWhereHas('tags', function ($tq) {
                            $tq->where('name', 'like', '%scholarship%');
                        });
                })
                ->orderByDesc('id')
                ->limit(12)
                ->get();

            return [
                'scholarship' => $this->mapScholarship($post),
                'universities' => $universities,
            ];
        });

        if (!$payload) {
            return $this->sendError('Scholarship not found.', ['error' => 'Scholarship not found.'], 404);
        }

        return $this->sendResponse($payload, 'successfully.', 60);
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
