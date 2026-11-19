<?php

namespace App\Support;

use App\Models\Post\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class PublicContent
{
    const SENSITIVE_META_KEYS = [
        'user',
        'password',
        'username',
        'pass',
        'portal_user',
        'portal_password',
        'login',
        'credential',
        'credentials',
    ];

    /**
     * Active/public posts (status 1 or legacy null).
     *
     * @param  Builder  $query
     * @return Builder
     */
    public static function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->where('status', 1)->orWhereNull('status');
        });
    }

    /**
     * Whether a post should be publicly visible.
     *
     * @param  Post|null  $post
     * @return bool
     */
    public static function isActive($post)
    {
        if (!$post) {
            return false;
        }
        $status = $post->status;

        return $status === null || (int) $status === 1;
    }

    /**
     * Strip sensitive metas before returning a Post over the public API.
     *
     * @param  Post  $post
     * @return Post
     */
    public static function sanitizePost(Post $post)
    {
        if ($post->relationLoaded('postMetas')) {
            $safe = $post->postMetas->filter(function ($meta) {
                $key = strtolower((string) ($meta->meta_key ?? ''));
                foreach (self::SENSITIVE_META_KEYS as $blocked) {
                    if ($key === $blocked || strpos($key, $blocked) !== false) {
                        return false;
                    }
                }
                return true;
            })->values();
            $post->setRelation('postMetas', $safe);
        }

        return $post;
    }

    /**
     * @param  Collection|array  $posts
     * @return Collection
     */
    public static function sanitizePosts($posts)
    {
        return collect($posts)->map(function ($post) {
            return $post instanceof Post ? self::sanitizePost($post) : $post;
        });
    }

    public static function forgetCaches()
    {
        ApiCache::bump();
    }
}
