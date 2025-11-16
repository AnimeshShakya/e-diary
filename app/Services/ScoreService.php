<?php

namespace App\Services;

use App\Models\Checklist;
use App\Models\Diary;

class ScoreService
{
    public static function calculateFromDiary(Diary $diary)
    {
        $checklistArray = json_decode($diary->checklist_array, true) ?? [];
        $completedChecklist = count($checklistArray);
        $totalChecklist = Checklist::count();

        $checklistScore = $totalChecklist > 0
            ? ($completedChecklist / $totalChecklist) * 5
            : 0;

        $sleep = $diary->sleep ?? 7;
        if ($sleep == 7) {
            $sleepScore = 10;
        } elseif ($sleep == 6 || $sleep == 8) {
            $sleepScore = 9;
        } elseif ($sleep == 5 || $sleep == 9) {
            $sleepScore = 7;
        } elseif ($sleep == 4 || $sleep == 10) {
            $sleepScore = 4;
        } elseif ($sleep == 3 || $sleep == 11) {
            $sleepScore = 3;
        } elseif ($sleep == 2 || $sleep == 12) {
            $sleepScore = 2;
        } elseif ($sleep == 1 || $sleep == 13) {
            $sleepScore = 1;
        } else {
            $sleepScore = 0;
        }
        $sleepFinal = ($sleepScore / 10) * 2;

        $management = $diary->management ?? 2;
        if ($management <= 4) {
            $managementScore = 10;
        } else {
            $managementScore = max(0, 10 - ($management - 4)); //min score 0
        }
        $managementFinal = ($managementScore / 10) * 1;

        $utilized = $diary->utilized ?? 16;
        if ($utilized >= 12) {
            $utilizedScore = 10;
        } else {
            $utilizedScore = min(($utilized / 12) * 10, 10);
        }
        $utilizedFinal = ($utilizedScore / 10) * 3;

        $wasted = $diary->wasted ?? 0;
        if ($wasted <= 1) {
            $wastedScore = 10;
        } else {
            $wastedScore = max(0, 10 - ($management - 1));
        }
        $wastedScore = max(0, 10 - $wasted);
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
}
