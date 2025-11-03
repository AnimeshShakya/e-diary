<?php

namespace Database\Seeders;

use App\Models\Checklist;
use App\Models\Diary;
use App\Models\DiaryChecklistItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class DiaryTwoMonthsSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure we have at least some checklists
        $defaultNames = ['7h Sleep', '2L Water', '1h Sunlight'];
        foreach ($defaultNames as $name) {
            Checklist::firstOrCreate(['name' => $name], ['description' => $name.' habit']);
        }
        $checklists = Checklist::query()->pluck('id')->all();

        // Seed for all users; if none exist, create one demo user
        if (User::count() === 0) {
            User::factory()->create([
                'name' => 'Demo User',
                'email' => 'demo@example.com',
            ]);
        }

        $start = Carbon::today()->subDays(59);
        $end = Carbon::today();

        /** @var \Illuminate\Support\Collection<int, User> $users */
        $users = User::all();

        foreach ($users as $user) {
            $cursor = $start->copy();
            while ($cursor->lte($end)) {
                // Skip if diary already exists for this date
                $existing = Diary::query()
                    ->where('user_id', $user->id)
                    ->where('date', $cursor->toDateString())
                    ->first();
                if ($existing) {
                    $cursor->addDay();

                    continue;
                }

                // Random selection of checklist ids (0-3 items)
                $selected = Arr::random($checklists, min(count($checklists), random_int(0, 3)));
                if (! is_array($selected)) {
                    $selected = [$selected];
                }
                sort($selected);

                // Random times and scores
                $yesterdaySleep = sprintf('%02d:%02d', random_int(22, 23), [0, 15, 30, 45][array_rand([0, 1, 2, 3])]);
                $todayWake = sprintf('%02d:%02d', random_int(5, 8), [0, 15, 30, 45][array_rand([0, 1, 2, 3])]);

                $diary = Diary::create([
                    'user_id' => $user->id,
                    'date' => $cursor->toDateString(),
                    'yesterday_sleep_time' => $yesterdaySleep,
                    'today_wake_time' => $todayWake,
                    'text' => fake()->paragraph(3),
                    'urgent_important' => fake()->sentence(6),
                    'urgent_notimportant' => fake()->sentence(6),
                    'noturgent_important' => fake()->sentence(6),
                    'noturgent_notimportant' => fake()->sentence(6),
                    'good_memo' => fake()->sentence(10),
                    'sleep' => random_int(360, 540), // minutes
                    'management' => random_int(60, 240),
                    'utilized' => random_int(120, 540),
                    'wasted' => random_int(0, 180),
                    'checklist_array' => json_encode($selected),
                ]);

                // Maintain normalized checklist items for analytics
                if (! empty($selected)) {
                    $insert = [];
                    foreach ($selected as $cid) {
                        $insert[] = [
                            'user_id' => $user->id,
                            'diary_id' => $diary->id,
                            'checklist_id' => $cid,
                            'date' => $cursor->toDateString(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                    DiaryChecklistItem::query()->insert($insert);
                }

                $cursor->addDay();
            }
        }
    }
}
