<?php

namespace App\Http\Controllers\API;

use App\Models\Category;
use App\Models\Post\Post;
use App\Models\Subject;
use App\Models\WebsiteSetting;
use App\Models\WebsiteVideo;
use App\Support\ChinaRegionMap;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Throwable;

class HomeController extends BaseController
{
    const CACHE_KEY = 'website_home_v1';
    const CACHE_TTL = 120;

    public function show()
    {
        $payload = Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return [
                'settings' => $this->safeSettings(),
                'universities' => $this->safeUniversities(12),
                'cities' => $this->safeCities(8),
                'scholarships' => $this->safeScholarships(6),
                'services' => $this->safeServices(6),
                'majors' => $this->safeMajors(60),
                'success_photos' => $this->safeSuccessPhotos(24),
                'videos' => $this->safeVideos(),
                'generated_at' => now()->toIso8601String(),
            ];
        });

        return response()
            ->json([
                'success' => true,
                'message' => 'successfully.',
                'err_res' => '',
                'status_code' => 200,
                'data' => $payload,
            ], 200)
            ->header('Cache-Control', 'public, max-age=60, stale-while-revalidate=120');
    }

    public static function forgetCache()
    {
        Cache::forget(self::CACHE_KEY);
    }

    private function safeSettings()
    {
        try {
            if (!Schema::hasTable('website_settings')) {
                return $this->defaultSettings();
            }
            return WebsiteSetting::publicPayload();
        } catch (Throwable $e) {
            return $this->defaultSettings();
        }
    }

    private function defaultSettings()
    {
        return [
            'brand' => 'AR Education',
            'phone' => '+8801756502004',
            'whatsapp' => 'https://wa.me/8801756502004',
            'email' => 'info@areducation.net',
            'address' => '10th floor, Tropical Tower, Bata Signal, Elephant Road, Dhaka, Bangladesh',
            'facebook' => 'https://www.facebook.com/AREducationbd',
            'youtube' => 'https://www.youtube.com/@areducationbd',
            'faqs' => [],
        ];
    }

    private function safeUniversities($limit)
    {
        try {
            return Post::query()
                ->with(['categories:id,name,slug', 'tags:id,name,slug'])
                ->where('post_type', 'university')
                ->orderByDesc('id')
                ->limit($limit)
                ->get()
                ->map(function ($post) {
                    return $this->mapUniversity($post);
                })
                ->values()
                ->all();
        } catch (Throwable $e) {
            return [];
        }
    }

    private function mapUniversity(Post $post)
    {
        $city = null;
        if ($post->relationLoaded('categories') && $post->categories->count()) {
            $city = $post->categories->first()->name;
        }

        return [
            'id' => $post->id,
            'post_title' => $post->post_title,
            'post_name' => $post->post_name,
            'post_content' => $post->post_content,
            'image' => $post->image,
            'image_url' => $post->image_url,
            'categories' => $post->categories,
            'tags' => $post->tags,
            'city' => $city,
        ];
    }

    private function safeCities($limit)
    {
        try {
            $exclude = [
                'root',
                'chinese-universities',
                'medical-university',
                'engineering-university',
                'engineering-uuniversity',
                'language-course',
                'language-courses',
            ];

            $rows = Category::query()
                ->whereNotIn('slug', $exclude)
                ->where('name', 'not like', '%universit%')
                ->withCount([
                    'posts as universities_count' => function ($q) {
                        $q->where('post_type', 'university');
                    },
                ])
                ->whereHas('posts', function ($q) {
                    $q->where('post_type', 'university');
                })
                ->orderBy('name')
                ->limit(80)
                ->get()
                ->filter(function ($category) {
                    return ChinaRegionMap::isLocationCategory($category->slug, $category->name);
                })
                ->sortByDesc('universities_count')
                ->take($limit)
                ->values();

            return $rows->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'region' => ChinaRegionMap::regionFor($category->slug, $category->name),
                    'universities' => (int) $category->universities_count,
                    'uni_count' => (int) $category->universities_count,
                    'image' => null,
                ];
            })->all();
        } catch (Throwable $e) {
            return [];
        }
    }

    private function safeScholarships($limit)
    {
        try {
            return Post::query()
                ->with(['postMetas', 'tags:id,name,slug'])
                ->where('post_type', 'scholarship')
                ->orderBy('post_title')
                ->limit($limit)
                ->get()
                ->map(function ($post) {
                    return [
                        'id' => $post->id,
                        'slug' => $post->post_name,
                        'name' => $post->post_title,
                        'coverage' => $post->getMeta('coverage', 'Varies'),
                        'universities' => (int) $post->getMeta('universities', 0),
                        'desc' => $post->post_excerpt ?: strip_tags((string) $post->post_content),
                        'content' => $post->post_content,
                        'image' => $post->image_url,
                        'city' => $post->getMeta('city', ''),
                        'program_id' => $post->getMeta('program_id', ''),
                        'degree' => $post->getMeta('degree', ''),
                        'teaching_language' => $post->getMeta('teaching_language', ''),
                        'intake' => $post->getMeta('intake', ''),
                        'scholarship_type' => $post->getMeta('scholarship_type', ''),
                        'deadline' => $post->getMeta('deadline', ''),
                    ];
                })
                ->values()
                ->all();
        } catch (Throwable $e) {
            return [];
        }
    }

    private function safeServices($limit)
    {
        try {
            return Post::query()
                ->with(['postMetas'])
                ->where('post_type', 'service')
                ->orderByDesc('id')
                ->limit($limit)
                ->get()
                ->map(function ($post) {
                    $metas = [];
                    foreach ($post->postMetas as $meta) {
                        $metas[$meta->meta_key] = $meta->meta_value;
                    }

                    return [
                        'id' => $post->id,
                        'post_title' => $post->post_title,
                        'post_name' => $post->post_name,
                        'post_excerpt' => $post->post_excerpt,
                        'post_content' => $post->post_content,
                        'post_metas' => $post->postMetas,
                        'meta' => $metas,
                    ];
                })
                ->values()
                ->all();
        } catch (Throwable $e) {
            return [];
        }
    }

    private function safeMajors($limit)
    {
        try {
            return Subject::query()
                ->where(function ($q) {
                    $q->where('status', 1)->orWhereNull('status');
                })
                ->withCount([
                    'posts as universities_count' => function ($q) {
                        $q->where('post_type', 'university');
                    },
                ])
                ->orderBy('name')
                ->limit($limit)
                ->get()
                ->map(function ($subject) {
                    return [
                        'id' => $subject->id,
                        'slug' => Str::slug($subject->name),
                        'name' => $subject->name,
                        'field' => 'General',
                        'degree' => null,
                        'universities' => (int) $subject->universities_count,
                        'uni_count' => (int) $subject->universities_count,
                    ];
                })
                ->values()
                ->all();
        } catch (Throwable $e) {
            return [];
        }
    }

    private function safeSuccessPhotos($limit)
    {
        try {
            return Post::query()
                ->with(['categories:id,name,slug'])
                ->where('post_type', 'success-story')
                ->whereNotNull('image')
                ->where('image', '!=', '')
                ->orderByDesc('id')
                ->limit($limit)
                ->get()
                ->map(function ($post) {
                    return [
                        'id' => $post->id,
                        'post_title' => $post->post_title,
                        'post_excerpt' => $post->post_excerpt,
                        'image' => $post->image,
                        'image_url' => $post->image_url,
                        'categories' => $post->categories,
                    ];
                })
                ->values()
                ->all();
        } catch (Throwable $e) {
            return [];
        }
    }

    private function safeVideos()
    {
        try {
            if (!Schema::hasTable('website_videos') || !class_exists(WebsiteVideo::class)) {
                return [];
            }

            return WebsiteVideo::query()
                ->where('is_active', true)
                ->orderBy('sort')
                ->orderBy('id')
                ->get()
                ->map(function ($video) {
                    $youtubeId = $video->youtubeId();
                    if (!$youtubeId) {
                        return null;
                    }

                    return [
                        'id' => $youtubeId,
                        'title' => $video->title,
                        'tag' => $video->tag ?: 'Video',
                        'url' => $video->youtube_url,
                    ];
                })
                ->filter()
                ->values()
                ->all();
        } catch (Throwable $e) {
            return [];
        }
    }
}
