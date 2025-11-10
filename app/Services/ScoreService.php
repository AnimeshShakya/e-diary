<?php

namespace App\Services;

use App\Models\Checklist;
use App\Models\Diary;

class ScoreService
{
    public static function calculateFromDiary(Diary $diary): float
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
        } else {
            $sleepScore = 1;
        }
        $sleepFinal = ($sleepScore / 10) * 2;

        $management = $diary->management ?? 2;
        $managementScore = max(0, 10 - ($management - 1));
        $managementFinal = ($managementScore / 10) * 1;

        $utilized = $diary->utilized ?? 16;
        $utilizedScore = min(($utilized / 16) * 10, 10);
        $utilizedFinal = ($utilizedScore / 10) * 3;

        $wasted = $diary->wasted ?? 0;
        $wastedScore = max(0, 10 - $wasted);
        $wastedFinal = ($wastedScore / 10) * 3;

        $timeScore = $sleepFinal + $managementFinal + $utilizedFinal + $wastedFinal;
        $timeScore = ($timeScore / 9) * 5;

        return round($checklistScore + $timeScore, 2);
    }
}
