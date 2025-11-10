<?php

namespace App\Http\Controllers;

use App\Models\DailyScore;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScoresExportController
{
    public function export(Request $request)
    {
        $user = $request->user();
        if (! $user) {
            abort(403);
        }

        $q = DailyScore::query()->where('user_id', $user->id);
        if ($request->filled('start')) {
            $q->where('date', '>=', $request->get('start'));
        }
        if ($request->filled('end')) {
            $q->where('date', '<=', $request->get('end'));
        }

        $filename = 'daily-scores-'.now()->format('Ymd_His').'.csv';

        return response()->streamDownload(function () use ($q) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['date', 'score']);

            $q->orderBy('date', 'desc')->chunk(200, function ($rows) use ($handle) {
                foreach ($rows as $row) {
                    fputcsv($handle, [Carbon::parse($row->date)->format('Y-m-d'), (string) $row->score]);
                }
            });

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
