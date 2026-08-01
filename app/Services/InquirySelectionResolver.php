<?php

namespace App\Services;

use App\Models\Inquiry\Inquiry;
use App\Models\Post\Post;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class InquirySelectionResolver
{
    public function universityNamesFromRequest(Request $request): ?string
    {
        $ids = $this->normalizeIds($request->input('university_ids', []));

        if (empty($ids) && $request->filled('university_id')) {
            $ids = [(int) $request->input('university_id')];
        }

        return $this->namesFromIds(
            Post::query()->where('post_type', 'university'),
            'post_title',
            $ids
        );
    }

    public function subjectNamesFromRequest(Request $request): ?string
    {
        $ids = $this->normalizeIds($request->input('subject_ids', []));

        if (empty($ids) && $request->filled('subject_id')) {
            $ids = [(int) $request->input('subject_id')];
        }

        return $this->namesFromIds(Subject::query(), 'name', $ids);
    }

    public function selectedUniversityIds(?Inquiry $inquiry, Collection $universities): array
    {
        $fromOld = old('university_ids');
        if (is_array($fromOld)) {
            return $this->normalizeIds($fromOld);
        }

        if (!$inquiry || empty($inquiry->university)) {
            return [];
        }

        return $this->matchStoredNamesToIds($inquiry->university, $universities, 'post_title');
    }

    public function selectedSubjectIds(?Inquiry $inquiry, Collection $subjects): array
    {
        $fromOld = old('subject_ids');
        if (is_array($fromOld)) {
            return $this->normalizeIds($fromOld);
        }

        if (!$inquiry || empty($inquiry->subject)) {
            return [];
        }

        return $this->matchStoredNamesToIds($inquiry->subject, $subjects, 'name');
    }

    public function labelsForIds(Collection $options, array $ids, string $labelKey): string
    {
        if (empty($ids)) {
            return '—';
        }

        $labels = $options
            ->whereIn('id', $ids)
            ->map(function ($item) use ($labelKey) {
                return $item->{$labelKey};
            })
            ->values()
            ->all();

        return $labels ? implode(', ', $labels) : '—';
    }

    private function namesFromIds($query, string $column, array $ids): ?string
    {
        if (empty($ids)) {
            return null;
        }

        $names = $query->whereIn('id', $ids)->pluck($column)->filter()->values();

        return $names->isEmpty() ? null : $names->implode(', ');
    }

    private function normalizeIds($ids): array
    {
        if (!is_array($ids)) {
            return [];
        }

        return array_values(array_unique(array_filter(array_map('intval', $ids))));
    }

    private function matchStoredNamesToIds(string $stored, Collection $options, string $labelKey): array
    {
        $names = collect(preg_split('/\s*,\s*/', $stored))
            ->filter()
            ->map(function ($name) {
                return trim($name);
            })
            ->values();

        return $options
            ->filter(function ($option) use ($names, $labelKey) {
                return $names->contains($option->{$labelKey});
            })
            ->pluck('id')
            ->map(function ($id) {
                return (int) $id;
            })
            ->values()
            ->all();
    }
}
