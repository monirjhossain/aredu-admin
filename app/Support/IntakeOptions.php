<?php

namespace App\Support;

class IntakeOptions
{
    /**
     * @return list<string>
     */
    public static function list(): array
    {
        $currentYear = (int) now()->year;
        $intake = [];

        for ($i = -2; $i < 3; $i++) {
            $intake[] = 'March-' . ($currentYear + $i);
            $intake[] = 'September-' . ($currentYear + $i);
        }

        return $intake;
    }

    public static function defaultForStudent(?string $studentIntake): string
    {
        $options = self::list();

        if ($studentIntake && in_array($studentIntake, $options, true)) {
            return $studentIntake;
        }

        $year = (string) now()->year;
        foreach ($options as $option) {
            if (str_contains($option, $year)) {
                return $option;
            }
        }

        return $options[0] ?? '';
    }
}
