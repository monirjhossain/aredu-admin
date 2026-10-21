<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteVideo extends Model
{
    protected $fillable = [
        'title',
        'tag',
        'youtube_url',
        'sort',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort' => 'integer',
    ];

    public static function extractYoutubeId(string $url): ?string
    {
        $url = trim($url);
        if ($url === '') {
            return null;
        }

        if (preg_match('/^[A-Za-z0-9_-]{11}$/', $url)) {
            return $url;
        }

        $patterns = [
            '~youtu\.be/([A-Za-z0-9_-]{11})~',
            '~youtube\.com/(?:shorts|embed|live|v)/([A-Za-z0-9_-]{11})~',
            '~[?&]v=([A-Za-z0-9_-]{11})~',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    public function youtubeId(): ?string
    {
        return static::extractYoutubeId((string) $this->youtube_url);
    }
}
