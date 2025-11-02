<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Livewire\Admin\Checklists\Create as ChecklistCreate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminChecklistsCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function actingAsAdmin(): User
    {
        /** @var User $admin */
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        return $admin;
    }

    public function test_admin_checklists_index_renders(): void
    {
        $this->actingAsAdmin();
        $this->get(route('admin.checklists.index'))
            ->assertStatus(200)
            ->assertSee('Checklists');
    }

    public function test_admin_can_create_checklist(): void
    {
        $this->actingAsAdmin();

        Livewire::test(ChecklistCreate::class)
            ->set('form.name', 'Hydrate')
            ->set('form.description', 'Drink water')
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('admin.checklists.index'));

        $this->assertDatabaseHas('checklists', [
            'name' => 'Hydrate',
            'description' => 'Drink water',
        ]);
    }
}
