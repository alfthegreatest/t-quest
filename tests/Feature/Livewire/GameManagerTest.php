<?php

namespace Tests\Feature\Livewire;

use App\Livewire\GameManager;
use App\Models\Game;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class GameManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_renders_successfully()
    {
        Livewire::test(GameManager::class)
            ->assertStatus(200);
    }

    public function test_displays_active_games()
    {
        $user = User::factory()->create();

        $activeGame = Game::factory()->create([
            'title' => 'Active Game',
            'active' => true,
            'created_by' => $user->id
        ]);

        $inactiveGame = Game::factory()->create([
            'title' => 'Inactive Game',
            'active' => false,
            'created_by' => $user->id
        ]);

        Livewire::test(GameManager::class)
            ->assertSee('Active Game')
            ->assertDontSee('Inactive Game');
    }
}
