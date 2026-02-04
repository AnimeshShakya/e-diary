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
        $options = $this->parseOptions();
        $this->info('Starting backfill of daily_scores');
        $query = $this->buildDiaryQuery($options);
        $total = $options['limit'] ?? $query->count();
        $this->info("Processing up to {$total} diary records...");

        return $options['limit']
            ? $this->processWithLimit($query, $options)
            : $this->processInChunks($query, $options);
    }

    /**
     * Parse CLI options into an array.
     */
    private function parseOptions(): array
    {
        return [
            'start' => $this->option('start'),
            'end' => $this->option('end'),
            'user' => $this->option('user'),
            'chunk' => (int) $this->option('chunk'),
            'limit' => $this->option('limit') ? (int) $this->option('limit') : null,
        ];
    }

    /**
     * Build the Diary query with optional filters.
     */
    private function buildDiaryQuery(array $options)
    {
        $query = Diary::query();
        if ($options['user']) {
            $query->where('user_id', $options['user']);
        }
        if ($options['start']) {
            $query->where('date', '>=', Carbon::parse($options['start'])->toDateString());
        }
        if ($options['end']) {
            $query->where('date', '<=', Carbon::parse($options['end'])->toDateString());
        }

        return $query;
    }

    /**
     * Process diary records when a limit is specified.
     */
    private function processWithLimit($query, array $options): int
    {
        $processed = 0;
        $limit = $options['limit'];

        $bar = $this->output->createProgressBar($limit);
        foreach ($query->orderBy('id')->cursor() as $diary) {
            $this->saveScore($diary);
            $bar->advance();

            if (++$processed >= $limit) {
                break;
            }
        }
        $bar->finish();
        $this->newLine();
        $this->info("Processed {$processed} diary records");

        return 0;
    }

    /**
     * Process diaries using chunkById for memory efficiency.
     */
    private function processInChunks($query, array $options): int
    {
        $processed = 0;
        $count = $query->count();
        $bar = $count ? $this->output->createProgressBar($count) : null;
        $query->orderBy('id')->chunkById($options['chunk'], function ($diaries) use (&$processed, $bar) {
            foreach ($diaries as $diary) {
                $this->saveScore($diary);
                $processed++;

                if ($bar) {
                    $bar->advance();
                }
            }
            DB::disconnect();
        });

        if ($bar) {
            $bar->finish();
            $this->newLine();
        }

        $this->info("Completed. Processed {$processed} diaries.");

        return 0;
    }

    /**
     * Centralized score calculation + DB persistence with error handling.
     */
    private function saveScore(Diary $diary): void
    {
        try {
            $score = ScoreService::calculateFromDiary($diary);

            DailyScore::updateOrCreate(
                [
                    'user_id' => $diary->user_id,
                    'date' => $diary->date,
                ],
                ['score' => $score]
            );
        } catch (\Throwable $e) {
            $this->error("Failed to persist score for diary id={$diary->id}: {$e->getMessage()}");
        }
    }
}
