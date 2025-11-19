<?php

namespace App\Services;

use App\Models\Checklist;
use App\Models\Diary;

class ScoreService
{
    public static function claculate_sleep_score($sleep)
    {
        $sleepRanges = [
            10 => [[390, 450]],                                   // 6.5–7.5h perfect
            9 => [[330, 390], [450, 510]],                      // 5.5–6.5 or 7.5–8.5
            7 => [[270, 330], [510, 570]],                      // 4.5–5.5 or 8.5–9.5
            4 => [[210, 270], [570, 630]],                      // 3.5–4.5 or 9.5–10.5
            3 => [[150, 210], [630, 690]],                      // 2.5–3.5 or 10.5–11.5
            2 => [[90, 150],  [690, 750]],                      // 1.5–2.5 or 11.5–12.5
            1 => [[30, 90],   [750, 810]],                      // 0.5–1.5 or 12.5–13.5
        ];

        $sleepScore = 0;

        foreach ($sleepRanges as $score => $ranges) {
            foreach ($ranges as [$min, $max]) {
                if ($sleep >= $min && $sleep <= $max) {
                    $sleepScore = $score;
                    break 2;
                }
            }
        }

        return $sleepScore;
    }

    public static function calculateFromDiary(Diary $diary)
    {
        $checklistArray = json_decode($diary->checklist_array, true) ?? [];
        $completedChecklist = count($checklistArray);
        $totalChecklist = Checklist::count();

        $checklistScore = $totalChecklist > 0 ? ($completedChecklist / $totalChecklist) * 5 : 0;

        $sleep_minutes = self::timeToMinutes($diary->sleep);
        $management_minutes = self::timeToMinutes($diary->management);
        $utilized_minutes = self::timeToMinutes($diary->utilized);
        $wasted_minutes = self::timeToMinutes($diary->wasted);

        $sleep = (float) $sleep_minutes;
        $management = (float) $management_minutes;
        $utilized = (float) $utilized_minutes;
        $wasted = (float) $wasted_minutes;

        $sleepScore = self::claculate_sleep_score($sleep);
        $sleepFinal = ($sleepScore / 10) * 2;

        if ($management <= 4 * 60) {
            $managementScore = 10;
        } else {
            $managementScore = max(0, 10 - ($management / 60 - 4.0)); // min score 0
        }
        $managementFinal = ($managementScore / 10) * 1;

        if ($utilized >= 12 * 60) {
            $utilizedScore = 10;
        } else {
            $utilizedScore = min((($utilized / 60) / 12) * 10, 10);
        }
        $utilizedFinal = ($utilizedScore / 10) * 3;

        if ($wasted <= 1 * 60) {
            $wastedScore = 10;
        } else {
            $wastedScore = max(0, 10 - ($management - 1));
        }
        $wastedScore = max(0, 10.0 - $wasted / 60);
        $wastedFinal = ($wastedScore / 10) * 3;

        $timeScore = $sleepFinal + $managementFinal + $utilizedFinal + $wastedFinal;
        $timeScore = ($timeScore / 9) * 5;

        return [
            'round' => round($checklistScore + $timeScore, 2),
            'detail' => [
                'checklistScore' => $checklistScore,
                'sleepFinal' => $sleepFinal,
                'managementFinal' => $managementFinal,
                'utilizedFinal' => $utilizedFinal,
                'wastedFinal' => $wastedFinal,
                'timeScore' => $timeScore,
            ],
        ];

    }

    // convert "HH:MM" to total minutes (int)
    public static function timeToMinutes(string $time): int
    {
        if (! preg_match('/^\d{1,2}:\d{2}$/', $time)) {
            return 0;
        }
        [$h, $m] = explode(':', $time);
        $h = (int) $h;
        $m = (int) $m;
        // clamp
        if ($h < 0) {
            $h = 0;
        }
        if ($h > 23) {
            $h = 23;
        }
        if ($m < 0) {
            $m = 0;
        }
        if ($m > 59) {
            $m = 59;
        }

        return $h * 60 + $m;
    }

    // convert minutes back to "HH:MM"
    public static function minutesToTime(int $minutes): string
    {
        // clamp between 0 and 1440 (24:00)
        if ($minutes < 0) {
            $minutes = 0;
        }
        if ($minutes > 24 * 60) {
            $minutes = 24 * 60;
        }
        $h = intdiv($minutes, 60);
        $m = $minutes % 60;

        return sprintf('%02d:%02d', $h, $m);
    }
}
