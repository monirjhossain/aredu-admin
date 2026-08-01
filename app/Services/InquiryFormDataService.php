<?php

namespace App\Services;

use App\Models\Inquiry\Inquiry;
use App\Models\Post\Post;
use App\Models\Student\Student;
use App\Models\Study;
use App\Models\Subject;
use Illuminate\Support\Collection;

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

        $fromStudents = Student::query()
            ->whereNotNull('last_education')
            ->where('last_education', '!=', '')
            ->distinct()
            ->orderBy('last_education')
            ->pluck('last_education');

        $fromInquiries = Inquiry::query()
            ->whereNotNull('last_education')
            ->where('last_education', '!=', '')
            ->distinct()
            ->orderBy('last_education')
            ->pluck('last_education');

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
        $fromStudents = Student::query()
            ->whereNotNull('country')
            ->where('country', '!=', '')
            ->distinct()
            ->orderBy('country')
            ->pluck('country');

        $fromInquiries = Inquiry::query()
            ->whereNotNull('country')
            ->where('country', '!=', '')
            ->distinct()
            ->orderBy('country')
            ->pluck('country');

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
        $fromStudents = Student::query()
            ->whereNotNull('preferred_country')
            ->where('preferred_country', '!=', '')
            ->distinct()
            ->orderBy('preferred_country')
            ->pluck('preferred_country');

        $fromInquiries = Inquiry::query()
            ->whereNotNull('country_preferred')
            ->where('country_preferred', '!=', '')
            ->distinct()
            ->orderBy('country_preferred')
            ->pluck('country_preferred');

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
}
