<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Models\Post\Post;
use Illuminate\Http\Request;

class PostController extends BaseController
{
    /**
     * Public post list for website (university, blog, event, scholarship, etc.).
     */
    public function index(Request $request)
    {
        $postType = $request->input('post_type');
        $perPage = (int) $request->input('per_page', 20);
        $perPage = $perPage > 0 && $perPage <= 100 ? $perPage : 20;

        $posts = Post::query()
            ->with([
                'categories:id,name,slug',
                'tags:id,name,slug',
                'subjects:id,name',
                'postMetas',
            ])
            ->orderByDesc('id');

        if ($postType) {
            $posts->where('post_type', $postType);
        }

        // Optional publish filter — only when client asks (?status=1)
        if ($request->filled('status')) {
            $posts->where('status', $request->input('status'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $posts->where(function ($q) use ($search) {
                $q->where('post_title', 'like', '%' . $search . '%')
                    ->orWhere('post_content', 'like', '%' . $search . '%')
                    ->orWhere('post_name', 'like', '%' . $search . '%');
            });
        }

        // Filter universities by city/category slug or id
        $category = $request->input('category', $request->input('city'));
        if ($category) {
            $posts->whereHas('categories', function ($q) use ($category) {
                if (ctype_digit((string) $category)) {
                    $q->where('categories.id', $category);
                } else {
                    $q->where('categories.slug', $category)
                        ->orWhere('categories.name', $category);
                }
            });
        }

        // Filter by major/subject id or name
        $subject = $request->input('subject', $request->input('major'));
        if ($subject) {
            $posts->whereHas('subjects', function ($q) use ($subject) {
                if (ctype_digit((string) $subject)) {
                    $q->where('subjects.id', $subject);
                } else {
                    $q->where('subjects.name', 'like', '%' . $subject . '%');
                }
            });
        }

        // Filter by tag slug/name
        if ($request->filled('tag')) {
            $tag = $request->input('tag');
            $posts->whereHas('tags', function ($q) use ($tag) {
                if (ctype_digit((string) $tag)) {
                    $q->where('tags.id', $tag);
                } else {
                    $q->where('tags.slug', $tag)
                        ->orWhere('tags.name', 'like', '%' . $tag . '%');
                }
            });
        }

        // Universities that appear to offer scholarships
        if ($request->boolean('scholarship')) {
            $posts->where(function ($q) {
                $q->where('post_title', 'like', '%scholarship%')
                    ->orWhere('post_content', 'like', '%scholarship%')
                    ->orWhereHas('tags', function ($tq) {
                        $tq->where('name', 'like', '%scholarship%');
                    });
            });
        }

        $data['posts'] = $posts->paginate($perPage);

        return $this->sendResponse($data, 'successfully.');
    }

    /**
     * Single post by numeric id or "id-slug" / slug.
     */
    public function show($id)
    {
        $post = $this->resolvePost($id);

        if (!$post) {
            return $this->sendError('Post not found.', ['error' => 'Post not found.'], 404);
        }

        $post->load([
            'categories:id,name,slug',
            'tags:id,name,slug',
            'subjects:id,name',
            'postMetas',
        ]);

        return $this->sendResponse(['post' => $post], 'successfully.');
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

        // Frontend routes use "{id}-{post_name}"
        if (preg_match('/^(\d+)/', $raw, $m)) {
            $post = Post::find($m[1]);
            if ($post) {
                return $post;
            }
        }

        return Post::where('post_name', $raw)->first();
    }
}
