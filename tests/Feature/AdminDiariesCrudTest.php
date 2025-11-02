<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDiariesCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_diaries_index_page_renders(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.diaries.index'))
            ->assertOk()
            ->assertSee('New Diary');
    }
}
