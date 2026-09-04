<?php

namespace App\Http\Controllers\API;

use App\Models\Category;
use App\Models\Post\Post;
use App\Support\ChinaRegionMap;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CityController extends BaseController
{
    /**
     * Location categories that have (or can host) universities.
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 100);
        $perPage = $perPage > 0 && $perPage <= 200 ? $perPage : 100;
        $onlyWithUnis = !filter_var($request->input('all', false), FILTER_VALIDATE_BOOLEAN);

        $exclude = [
            'root',
            'chinese-universities',
            'medical-university',
            'engineering-university',
            'engineering-uuniversity',
            'language-course',
            'language-courses',
        ];

        $query = Category::query()
            ->whereNotIn('slug', $exclude)
            ->where('name', 'not like', '%universit%')
            ->withCount([
                'posts as universities_count' => function ($q) {
                    $q->where('post_type', 'university');
                },
            ])
            ->orderBy('name');

        if ($onlyWithUnis) {
            $query->whereHas('posts', function ($q) {
                $q->where('post_type', 'university');
            });
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('slug', 'like', '%' . $search . '%');
            });
        }

        // Limit early for homepage / mega menus
        if ($perPage <= 20 && !$request->filled('region') && !$request->filled('search')) {
            $query->limit($perPage);
        }

        $rows = $query->get()->filter(function ($category) {
            return ChinaRegionMap::isLocationCategory($category->slug, $category->name);
        })->values();

        if ($request->filled('region')) {
            $region = strtolower($request->input('region'));
            $rows = $rows->filter(function ($category) use ($region) {
                $label = strtolower(ChinaRegionMap::regionFor($category->slug, $category->name));
                return $label === $region
                    || Str::slug($label) === Str::slug($region);
            })->values();
        }

        $mapped = $rows->map(function ($category) {
            return $this->mapCity($category);
        });

        // Simple pagination over filtered collection
        $page = max(1, (int) $request->input('page', 1));
        $total = $mapped->count();
        $items = $mapped->slice(($page - 1) * $perPage, $perPage)->values();

        return $this->sendResponse([
            'cities' => [
                'data' => $items,
                'total' => $total,
                'current_page' => $page,
                'per_page' => $perPage,
                'last_page' => max(1, (int) ceil($total / $perPage)),
            ],
        ], 'successfully.');
    }

    /**
     * Single city by slug or id, with linked universities.
     */
    public function show($id)
    {
        $category = $this->resolveCategory($id);

        if (!$category || !ChinaRegionMap::isLocationCategory($category->slug, $category->name)) {
            return $this->sendError('City not found.', ['error' => 'City not found.'], 404);
        }

        $category->loadCount([
            'posts as universities_count' => function ($q) {
                $q->where('post_type', 'university');
            },
        ]);

        $universities = Post::query()
            ->with(['categories:id,name,slug', 'tags:id,name,slug', 'subjects:id,name'])
            ->where('post_type', 'university')
            ->whereHas('categories', function ($q) use ($category) {
                $q->where('categories.id', $category->id);
            })
            ->orderByDesc('id')
            ->limit(100)
            ->get();

        return $this->sendResponse([
            'city' => $this->mapCity($category),
            'universities' => $universities,
        ], 'successfully.');
    }

    /**
     * @param  string|int  $id
     * @return Category|null
     */
    private function resolveCategory($id)
    {
        $raw = (string) $id;

        if (ctype_digit($raw)) {
            return Category::find($raw);
        }

        return Category::query()
            ->where(function ($q) use ($raw) {
                $q->where('slug', $raw)
                    ->orWhere('slug', Str::slug($raw))
                    ->orWhere('name', $raw);
            })
            ->first();
    }

    /**
     * @param  Category  $category
     * @return array
     */
    private function mapCity(Category $category)
    {
        $desc = $category->description;
        if (!$desc) {
            $desc = $category->name . ' is a study destination in China with partner universities for international students.';
        }

        return [
            'id' => $category->id,
            'slug' => $category->slug,
            'name' => $category->name,
            'region' => ChinaRegionMap::regionFor($category->slug, $category->name),
            'universities' => (int) ($category->universities_count ?? 0),
            'uni_count' => (int) ($category->universities_count ?? 0),
            'desc' => $desc,
            'image' => $category->image,
        ];
    }
}
