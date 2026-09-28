<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScholarshipFormOption extends Model
{
    protected $fillable = ['group', 'label', 'sort'];

    public static function labels(string $group): array
    {
        return static::query()
            ->where('group', $group)
            ->orderBy('sort')
            ->orderBy('label')
            ->pluck('label')
            ->all();
    }
}
