<?php

namespace App\Http\Controllers\API;

use App\Support\AboutPageContent;
use Illuminate\Support\Facades\Cache;
use Throwable;

class AboutController extends BaseController
{
    const CACHE_KEY = 'website_about_v2';
    const CACHE_TTL = 120;

    public function show()
    {
        try {
            $payload = Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
                return AboutPageContent::publicPayload();
            });
        } catch (Throwable $e) {
            $payload = AboutPageContent::defaults();
        }

        return response()
            ->json([
                'success' => true,
                'message' => 'successfully.',
                'err_res' => '',
                'status_code' => 200,
                'data' => [
                    'about' => $payload,
                    'generated_at' => now()->toIso8601String(),
                ],
            ], 200)
            ->header('Cache-Control', 'public, max-age=60, stale-while-revalidate=120');
    }
}
