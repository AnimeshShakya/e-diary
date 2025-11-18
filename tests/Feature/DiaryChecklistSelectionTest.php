<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Livewire\Diaries\Create as DiaryCreate;
use App\Models\Checklist;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DiaryChecklistSelectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_diary_create_saves_selected_checklists_as_json(): void
    {
        /** @var User $user */
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $c1 = Checklist::create(['name' => 'Hydrate']);
        $c2 = Checklist::create(['name' => 'Exercise']);

        Livewire::test(DiaryCreate::class)
            ->set('form.date', now()->format('Y-m-d'))
            ->set('form.yesterday_sleep_time', '22:30')
            ->set('form.today_wake_time', '06:30')
            ->set('form.text', 'A good day')
            ->set('form.urgent_important', 'UI')
            ->set('form.urgent_notimportant', 'UNI')
            ->set('form.noturgent_important', 'NUI')
            ->set('form.noturgent_notimportant', 'NUNI')
            ->set('form.good_memo', 'Feeling great')
            ->set('form.sleep', '08:00')
            ->set('form.management', '07:00')
            ->set('form.utilized', '06:00')
            ->set('form.wasted', '03:00')
            ->call('toggleChecklist', $c1->id)
            ->call('toggleChecklist', $c2->id)
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('diaries', [
            'user_id' => $user->id,
            // The exact order may matter; we expect [c1,c2]
            'checklist_array' => json_encode([$c1->id, $c2->id]),
        ]);
    }
}
