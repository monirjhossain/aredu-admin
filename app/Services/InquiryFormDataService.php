<?php

namespace App\Services;

use App\Models\Inquiry\Inquiry;
use App\Models\Post\Post;
use App\Models\Student\Student;
use App\Models\Study;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class InquiryFormDataService
{
    public function getFormOptions(): array
    {
        return [
            'universities' => Post::select('id', 'post_title')
                ->where('post_type', 'university')
                ->orderBy('post_title')
                ->get(),
            'subjects' => Subject::select('id', 'name')
                ->where('status', 1)
                ->orderBy('name')
                ->get(),
            'courses' => Study::select('id', 'name')
                ->orderBy('name')
                ->get(),
            'lastEducationLevels' => $this->lastEducationLevels(),
            'countries' => $this->distinctCountries(),
            'preferredCountries' => $this->distinctPreferredCountries(),
        ];
    }

    public function getSubjectsForUniversity(?int $universityId): Collection
    {
        if (!$universityId) {
            return Subject::select('id', 'name')
                ->where('status', 1)
                ->orderBy('name')
                ->get();
        }

        $university = Post::where('post_type', 'university')->find($universityId);

        if (!$university) {
            return collect();
        }

        $subjects = $university->subjects()
            ->select('subjects.id', 'subjects.name')
            ->where('subjects.status', 1)
            ->orderBy('subjects.name')
            ->get();

        if ($subjects->isNotEmpty()) {
            return $subjects;
        }

        return Subject::select('id', 'name')
            ->where('status', 1)
            ->orderBy('name')
            ->get();
    }

    private function lastEducationLevels(): Collection
    {
        $defaults = collect([
            'SSC',
            'HSC',
            'Diploma',
            'Bachelor',
            'MBBS',
            'Masters',
            'PhD',
            'Foundation',
            'Language Course',
        ]);

        $fromStudies = Study::query()
            ->whereNotNull('name')
            ->where('name', '!=', '')
            ->orderBy('name')
            ->pluck('name');

        $fromStudents = $this->distinctColumnValues(new Student(), 'last_education');
        $fromInquiries = $this->distinctColumnValues(new Inquiry(), 'last_education');

        $ordered = $defaults
            ->merge($fromStudies)
            ->merge($fromStudents)
            ->merge($fromInquiries)
            ->unique()
            ->values();

        $priority = ['SSC', 'HSC'];

        return $ordered->sort(function ($a, $b) use ($priority) {
            $aIndex = array_search($a, $priority, true);
            $bIndex = array_search($b, $priority, true);

            if ($aIndex !== false && $bIndex !== false) {
                return $aIndex <=> $bIndex;
            }

            if ($aIndex !== false) {
                return -1;
            }

            if ($bIndex !== false) {
                return 1;
            }

            return strcasecmp($a, $b);
        })->values();
    }

    private function distinctCountries(): Collection
    {
        $fromStudents = $this->distinctColumnValues(new Student(), 'country');
        $fromInquiries = $this->distinctColumnValues(new Inquiry(), 'country');

        $defaults = collect([
            'Bangladesh',
            'India',
            'Pakistan',
            'Nepal',
        ]);

        return $defaults
            ->merge($fromStudents)
            ->merge($fromInquiries)
            ->unique()
            ->sort()
            ->values();
    }

    private function distinctPreferredCountries(): Collection
    {
        $fromStudents = $this->distinctColumnValues(new Student(), 'preferred_country');
        $fromInquiries = $this->distinctColumnValues(new Inquiry(), 'country_preferred');

        $defaults = collect([
            'China',
            'USA',
            'UK',
            'Italy',
            'Germany',
            'Canada',
            'Spain',
            'Japan',
            'Australia',
            'India',
            'Pakistan',
            'Nepal',
            'Kyrgyzstan',
        ]);

        return $defaults
            ->merge($fromStudents)
            ->merge($fromInquiries)
            ->unique()
            ->sort()
            ->values();
    }

    private function distinctColumnValues(Model $model, string $column): Collection
    {
        $table = $model->getTable();

        if (!Schema::hasColumn($table, $column)) {
            return collect();
        }

        return $model->newQuery()
            ->whereNotNull($column)
            ->where($column, '!=', '')
            ->distinct()
            ->orderBy($column)
            ->pluck($column);
    }
}
