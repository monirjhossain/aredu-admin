<?php

namespace App\Support;

use App\Models\Branch;

class BranchApplicationQr
{
    public static function formUrl(Branch $branch): string
    {
        return url('/application-form?branch=' . $branch->id);
    }

    public static function imageUrl(Branch $branch, int $size = 320): string
    {
        return 'https://api.qrserver.com/v1/create-qr-code/?size=' . $size . 'x' . $size . '&data='
            . rawurlencode(self::formUrl($branch));
    }

    public static function logoUrl(Branch $branch): string
    {
        if (!$branch->logo) {
            return asset('media/custom/logo.png');
        }

        if (strpos($branch->logo, 'http') === 0 || strpos($branch->logo, '//') === 0) {
            return $branch->logo;
        }

        if (strpos($branch->logo, '/') === 0 || strpos($branch->logo, 'uploads/') === 0 || strpos($branch->logo, 'media/') === 0) {
            return asset(ltrim($branch->logo, '/'));
        }

        return asset('media/custom/' . ltrim($branch->logo, '/'));
    }
}
