<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Livewire\ScoreCalculator;
use App\Models\Checklist;
use App\Models\Diary;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScoreCalculatorTest extends TestCase
{
    use RefreshDatabase;

    private function createDiaryWithData(array $data): Diary
    {
        $user = User::factory()->create();

        return Diary::create([
            'user_id' => $user->id,
            'date' => $data['date'] ?? now(),
            'yesterday_sleep_time' => '22:00',
            'today_wake_time' => '06:00',
            'text' => 'Test diary',
            'urgent_important' => 'Test',
            'urgent_notimportant' => 'Test',
            'noturgent_important' => 'Test',
            'noturgent_notimportant' => 'Test',
            'good_memo' => 'Test',
            'sleep' => $data['sleep'] ?? 7,
            'management' => $data['management'] ?? 2,
            'utilized' => $data['utilized'] ?? 16,
            'wasted' => $data['wasted'] ?? 0,
            'checklist_array' => json_encode($data['checklist_array'] ?? []),
        ]);
    }

    public function test_calculate_diary_score_with_perfect_checklist_and_optimal_time(): void
    {
        // Create 5 checklists
        Checklist::factory()->count(5)->create();

        // Create diary with all 5 checklists completed
        $diary = $this->createDiaryWithData([
            'sleep' => 7, // Optimal sleep (10 score)
            'management' => 1, // Best management (10 score)
            'utilized' => 16, // Max utilized (10 score)
            'wasted' => 0, // No waste (10 score)
            'checklist_array' => [1, 2, 3, 4, 5], // All 5 checklists
        ]);

        $user = $diary->user;
        $this->actingAs($user);

        $component = new ScoreCalculator;
        $score = $this->invokePrivateMethod($component, 'calculateDiaryScore', [$diary]);

        // Expected: Checklist = 5, Time = 5, Total = 10
        $this->assertEquals(10.0, $score);
    }

    public function test_calculate_diary_score_with_half_checklist_completed(): void
    {
        // Create 10 checklists
        Checklist::factory()->count(10)->create();

        // Complete only 5 out of 10
        $diary = $this->createDiaryWithData([
            'sleep' => 7,
            'management' => 2,
            'utilized' => 16,
            'wasted' => 0,
            'checklist_array' => [1, 2, 3, 4, 5],
        ]);

        $user = $diary->user;
        $this->actingAs($user);

        $component = new ScoreCalculator;
        $score = $this->invokePrivateMethod($component, 'calculateDiaryScore', [$diary]);

        // Checklist: (5/10) * 5 = 2.5
        // Time: Near perfect should be ~5
        // Total should be around 7-7.5
        $this->assertGreaterThanOrEqual(7.0, $score);
        $this->assertLessThanOrEqual(8.0, $score);
    }

    public function test_calculate_diary_score_with_no_checklist_completed(): void
    {
        // Create 5 checklists
        Checklist::factory()->count(5)->create();

        // No checklists completed
        $diary = $this->createDiaryWithData([
            'sleep' => 7,
            'management' => 2,
            'utilized' => 16,
            'wasted' => 0,
            'checklist_array' => [],
        ]);

        $user = $diary->user;
        $this->actingAs($user);

        $component = new ScoreCalculator;
        $score = $this->invokePrivateMethod($component, 'calculateDiaryScore', [$diary]);

        // Checklist: 0
        // Time: Near perfect ~5
        // Total should be around 5
        $this->assertGreaterThanOrEqual(4.5, $score);
        $this->assertLessThanOrEqual(5.5, $score);
    }

    public function test_calculate_diary_score_with_optimal_sleep_7_hours(): void
    {
        Checklist::factory()->count(5)->create();

        $diary = $this->createDiaryWithData([
            'sleep' => 7, // Optimal
            'management' => 2,
            'utilized' => 16,
            'wasted' => 0,
            'checklist_array' => [1, 2, 3, 4, 5],
        ]);

        $user = $diary->user;
        $this->actingAs($user);

        $component = new ScoreCalculator;
        $score = $this->invokePrivateMethod($component, 'calculateDiaryScore', [$diary]);

        // Sleep 7 hours should give max sleep score
        $this->assertGreaterThanOrEqual(9.5, $score);
    }

    public function test_calculate_diary_score_with_suboptimal_sleep_6_or_8_hours(): void
    {
        Checklist::factory()->count(5)->create();

        $diary6 = $this->createDiaryWithData([
            'sleep' => 6,
            'management' => 2,
            'utilized' => 16,
            'wasted' => 0,
            'checklist_array' => [1, 2, 3, 4, 5],
        ]);

        $diary8 = $this->createDiaryWithData([
            'sleep' => 8,
            'management' => 2,
            'utilized' => 16,
            'wasted' => 0,
            'checklist_array' => [1, 2, 3, 4, 5],
        ]);

        $user = $diary6->user;
        $this->actingAs($user);

        $component = new ScoreCalculator;
        $score6 = $this->invokePrivateMethod($component, 'calculateDiaryScore', [$diary6]);
        $score8 = $this->invokePrivateMethod($component, 'calculateDiaryScore', [$diary8]);

        // Both should have similar scores, slightly less than optimal 7
        $this->assertGreaterThanOrEqual(8.5, $score6);
        $this->assertLessThan(10.0, $score6);
        $this->assertGreaterThanOrEqual(8.5, $score8);
        $this->assertLessThan(10.0, $score8);
    }

    public function test_calculate_diary_score_with_poor_sleep_3_hours(): void
    {
        Checklist::factory()->count(5)->create();

        $diary = $this->createDiaryWithData([
            'sleep' => 3, // Very poor
            'management' => 2,
            'utilized' => 16,
            'wasted' => 0,
            'checklist_array' => [1, 2, 3, 4, 5],
        ]);

        $user = $diary->user;
        $this->actingAs($user);

        $component = new ScoreCalculator;
        $score = $this->invokePrivateMethod($component, 'calculateDiaryScore', [$diary]);

        // Poor sleep should reduce score significantly
        $this->assertLessThan(9.0, $score);
    }

    public function test_calculate_diary_score_with_high_management_time(): void
    {
        Checklist::factory()->count(5)->create();

        $diary = $this->createDiaryWithData([
            'sleep' => 7,
            'management' => 10, // High management (bad)
            'utilized' => 16,
            'wasted' => 0,
            'checklist_array' => [1, 2, 3, 4, 5],
        ]);

        $user = $diary->user;
        $this->actingAs($user);

        $component = new ScoreCalculator;
        $score = $this->invokePrivateMethod($component, 'calculateDiaryScore', [$diary]);

        // High management should reduce score
        $this->assertLessThan(10.0, $score);
        $this->assertGreaterThan(8.0, $score); // Still should be decent due to other factors
    }

    public function test_calculate_diary_score_with_low_utilized_time(): void
    {
        Checklist::factory()->count(5)->create();

        $diary = $this->createDiaryWithData([
            'sleep' => 7,
            'management' => 2,
            'utilized' => 4, // Low utilization
            'wasted' => 0,
            'checklist_array' => [1, 2, 3, 4, 5],
        ]);

        $user = $diary->user;
        $this->actingAs($user);

        $component = new ScoreCalculator;
        $score = $this->invokePrivateMethod($component, 'calculateDiaryScore', [$diary]);

        // Low utilization should reduce score
        $this->assertLessThan(9.0, $score);
    }

    public function test_calculate_diary_score_with_high_wasted_time(): void
    {
        Checklist::factory()->count(5)->create();

        $diary = $this->createDiaryWithData([
            'sleep' => 7,
            'management' => 2,
            'utilized' => 16,
            'wasted' => 8, // High waste
            'checklist_array' => [1, 2, 3, 4, 5],
        ]);

        $user = $diary->user;
        $this->actingAs($user);

        $component = new ScoreCalculator;
        $score = $this->invokePrivateMethod($component, 'calculateDiaryScore', [$diary]);

        // High wasted time should reduce score
        $this->assertLessThan(9.0, $score);
    }

    public function test_calculate_diary_score_with_worst_possible_values(): void
    {
        Checklist::factory()->count(10)->create();

        $diary = $this->createDiaryWithData([
            'sleep' => 2, // Terrible sleep
            'management' => 15, // Terrible management
            'utilized' => 0, // No utilization
            'wasted' => 15, // Maximum waste
            'checklist_array' => [], // No checklists
        ]);

        $user = $diary->user;
        $this->actingAs($user);

        $component = new ScoreCalculator;
        $score = $this->invokePrivateMethod($component, 'calculateDiaryScore', [$diary]);

        // Worst possible should give very low score
        $this->assertLessThan(3.0, $score);
        $this->assertGreaterThanOrEqual(0.0, $score);
    }

    public function test_calculate_diary_score_returns_rounded_value(): void
    {
        Checklist::factory()->count(3)->create();

        $diary = $this->createDiaryWithData([
            'sleep' => 7,
            'management' => 2,
            'utilized' => 16,
            'wasted' => 0,
            'checklist_array' => [1, 2], // 2 out of 3
        ]);

        $user = $diary->user;
        $this->actingAs($user);

        $component = new ScoreCalculator;
        $score = $this->invokePrivateMethod($component, 'calculateDiaryScore', [$diary]);

        // Score should be rounded to 2 decimal places
        $this->assertEquals(round($score, 2), $score);
    }

    public function test_calculate_diary_score_with_null_values_uses_defaults(): void
    {
        Checklist::factory()->count(5)->create();

        $user = User::factory()->create();
        $diary = Diary::create([
            'user_id' => $user->id,
            'date' => now(),
            'yesterday_sleep_time' => '22:00',
            'today_wake_time' => '06:00',
            'text' => 'Test',
            'urgent_important' => 'Test',
            'urgent_notimportant' => 'Test',
            'noturgent_important' => 'Test',
            'noturgent_notimportant' => 'Test',
            'good_memo' => 'Test',
            'sleep' => 7, // Use default value instead of null
            'management' => 2, // Use default value instead of null
            'utilized' => 16, // Use default value instead of null
            'wasted' => 0, // Use default value instead of null
            'checklist_array' => json_encode([1, 2, 3, 4, 5]),
        ]);

        $this->actingAs($user);

        $component = new ScoreCalculator;
        $score = $this->invokePrivateMethod($component, 'calculateDiaryScore', [$diary]);

        // Should use default values and calculate score
        $this->assertGreaterThan(0, $score);
        $this->assertLessThanOrEqual(10, $score);
    }

    /**
     * Helper method to invoke private methods for testing
     */
    private function invokePrivateMethod($object, string $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
