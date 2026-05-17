<?php

namespace App\Queries;

use Illuminate\Database\Eloquent\Builder;

/**
 * CSCA list-filter queries via student_csca_exams rows.
 */
final class StudentCscaQuery
{
    public static function apply(Builder $query, ?string $filter): Builder
    {
        if ($filter === 'yes') {
            return self::withResult($query);
        }

        if ($filter === 'no') {
            return self::withoutResult($query);
        }

        return $query;
    }

    public static function withResult(Builder $query): Builder
    {
        return $query->whereHas('studentCscaExams', function (Builder $sub) {
            self::whereColumnFilled($sub, 'result');
        });
    }

    public static function withoutResult(Builder $query): Builder
    {
        return $query->whereDoesntHave('studentCscaExams', function (Builder $sub) {
            self::whereColumnFilled($sub, 'result');
        });
    }

    private static function whereColumnFilled(Builder $query, string $column): Builder
    {
        return $query->whereNotNull($column)->where($column, '!=', '');
    }
}
