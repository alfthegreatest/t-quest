<?php

namespace Tests\Feature\Livewire;

use App\Livewire\CreateGame;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class CreateGameTest extends TestCase
{
    use RefreshDatabase;

    public function test_renders_successfully()
    {
        Livewire::test(CreateGame::class)
            ->assertStatus(200);
    }

    public function test_can_create_game()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        /** @var \Illuminate\Filesystem\FilesystemAdapter $publicDisk */
        $publicDisk = Storage::fake('public');

        $image = UploadedFile::fake()->image('game.jpg');
        Livewire::test(CreateGame::class)
            ->set('title', 'Test Game')
            ->set('description', 'This is a test game')
            ->set('image', $image)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('games', [
            'title' => 'Test Game',
            'description' => 'This is a test game',
            'created_by' => $user->id,
        ]);
        $publicDisk->assertExists('games/' . $image->hashName());
    }

    public function test_validation_rules()
    {
        Livewire::test(CreateGame::class)
            ->set('title', '')
            ->call('save')
            ->assertHasErrors(['title' => 'required']);

        Livewire::test(CreateGame::class)
            ->set('title', 'ab')
            ->call('save')
            ->assertHasErrors(['title' => 'min']);
    }
}
