<?php

namespace App\Console\Commands;

use App\Models\DailyScore;
use App\Models\Diary;
use App\Services\ScoreService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BackfillDailyScores extends Command
{
    protected $signature = 'backfill:daily-scores {--start=} {--end=} {--user=} {--chunk=200} {--limit=}';

    protected $description = 'Backfill daily_scores table from existing Diary records';

    public function handle(): int
    {
        $start = $this->option('start');
        $end = $this->option('end');
        $user = $this->option('user');
        $chunk = (int) $this->option('chunk');
        $limit = $this->option('limit') ? (int) $this->option('limit') : null;

        $this->info('Starting backfill of daily_scores');

        $query = Diary::query();

        if ($user) {
            $query->where('user_id', $user);
        }

        if ($start) {
            $query->where('date', '>=', Carbon::parse($start)->toDateString());
        }

        if ($end) {
            $query->where('date', '<=', Carbon::parse($end)->toDateString());
        }

        $total = $limit ?? $query->count();

        $this->info("Processing up to {$total} diary records...");

        $processed = 0;

        if ($limit) {
            // iterate with cursor to allow a strict limit
            $bar = $this->output->createProgressBar($limit);
            foreach ($query->orderBy('id')->cursor() as $diary) {
                $score = ScoreService::calculateFromDiary($diary);
                try {
                    DailyScore::updateOrCreate([
                        'user_id' => $diary->user_id,
                        'date' => $diary->date,
                    ], ['score' => $score]);
                } catch (\Throwable $e) {
                    $this->error("Failed to persist score for diary id={$diary->id}: {$e->getMessage()}");
                }

                $processed++;
                $bar->advance();
                if ($processed >= $limit) {
                    break;
                }
            }

            $bar->finish();
            $this->newLine();
            $this->info("Processed {$processed} diary records");

            return 0;
        }

        // No hard limit: use chunkById for efficient processing
        $bar = null;
        $count = $query->count();
        if ($count > 0) {
            $bar = $this->output->createProgressBar($count);
        }

        $query->orderBy('id')->chunkById($chunk, function ($diaries) use (&$processed, $bar) {
            foreach ($diaries as $diary) {
                $score = ScoreService::calculateFromDiary($diary);
                try {
                    DailyScore::updateOrCreate([
                        'user_id' => $diary->user_id,
                        'date' => $diary->date,
                    ], ['score' => $score]);
                } catch (\Throwable $e) {
                    $this->error("Failed to persist score for diary id={$diary->id}: {$e->getMessage()}");
                }

                $processed++;
                if ($bar) {
                    $bar->advance();
                }
            }
            // free memory
            DB::disconnect();
        });

        if ($bar) {
            $bar->finish();
            $this->newLine();
        }

        $this->info("Completed. Processed {$processed} diaries.");

        return 0;
    }
}
