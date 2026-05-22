<?php

namespace App\Support;

use App\Models\Student\Student;

class StudentDisplay
{
    public static function initials(Student $student, string $fallback = 'ST'): string
    {
        $initials = strtoupper(
            substr((string) $student->first_name, 0, 1) . substr((string) $student->last_name, 0, 1)
        );

        return $initials !== '' ? $initials : $fallback;
    }

    /**
     * @param mixed $value
     */
    public static function value($value, string $empty = '—'): string
    {
        if ($value === null || $value === '') {
            return $empty;
        }

        return (string) $value;
    }

    public static function addressLine(Student $student): string
    {
        $address = trim((string) $student->address);
        $city = trim((string) $student->city);

        if ($address === '' && $city === '') {
            return '—';
        }

        if ($address === '') {
            return $city;
        }

        return $city !== '' ? $address . ', ' . $city : $address;
    }

    /**
     * @param array<string, mixed> $pairs label => raw value
     * @return array<int, array{label: string, value: string}>
     */
    public static function detailRows(array $pairs): array
    {
        $rows = [];

        foreach ($pairs as $label => $value) {
            $rows[] = [
                'label' => $label,
                'value' => self::value($value),
            ];
        }

        return $rows;
    }

    /**
     * CSCA block data for student show view.
     *
     * @return array{
     *     has_csca: bool,
     *     has_exam_rows: bool,
     *     legacy_scores: array<int, array{exam: string, result: string}>,
     *     show_empty: bool
     * }
     */
    public static function cscaPresentation(Student $student): array
    {
        $hasCscaFlag = (int) ($student->has_csca ?? 0) === 1;
        $legacyMath = trim((string) ($student->csca_math ?? ''));
        $legacyPhysics = trim((string) ($student->csca_physics ?? ''));
        $legacyChemistry = trim((string) ($student->csca_chemistry ?? ''));
        $hasLegacyCsca = $legacyMath !== '' || $legacyPhysics !== '' || $legacyChemistry !== '';
        $hasExamRows = $student->relationLoaded('studentCscaExams')
            ? $student->studentCscaExams->isNotEmpty()
            : $student->studentCscaExams()->exists();

        if (!$hasCscaFlag && ($hasExamRows || $hasLegacyCsca)) {
            $hasCscaFlag = true;
        }

        $legacyScores = [];

        if ($legacyMath !== '') {
            $legacyScores[] = ['exam' => 'Mathematics', 'result' => $legacyMath];
        }
        if ($legacyPhysics !== '') {
            $legacyScores[] = ['exam' => 'Physics', 'result' => $legacyPhysics];
        }
        if ($legacyChemistry !== '') {
            $legacyScores[] = ['exam' => 'Chemistry', 'result' => $legacyChemistry];
        }

        return [
            'has_csca' => $hasCscaFlag,
            'has_exam_rows' => $hasExamRows,
            'legacy_scores' => $legacyScores,
            'show_empty' => !$hasExamRows && $legacyScores === [],
        ];
    }
}
