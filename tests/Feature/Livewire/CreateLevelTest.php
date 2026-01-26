<?php

namespace Tests\Feature\Livewire;

use App\Livewire\CreateLevel;
use App\Models\Game;
use App\Models\User;
use App\Models\Level;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;


class CreateLevelTest extends TestCase
{
    use RefreshDatabase;

    public function test_renders_successfully()
    {
        $user = User::factory()->create();
        $game = Game::factory()->create();

        Livewire::actingAs($user)
            ->test(CreateLevel::class, ['gameId' => $game->id])
            ->assertStatus(200);
    }

    public function test_can_create_level()
    {
        $user = User::factory()->create();
        $game = Game::factory()->create();

        $days = 1;
        $hours = 1;
        $minutes = 1;
        $longitude = '52.234148';
        $latitude = '21.003730';

        Livewire::test(CreateLevel::class, ['gameId' => $game->id])
            ->set('name', 'test level')
            ->set('description', 'some level description')
            ->set('points', 1)
            ->set('longitude', $longitude)
            ->set('latitude', $latitude)
            ->set('availability_time_days', $days)
            ->set('availability_time_hours', $hours)
            ->set('availability_time_minutes', $minutes)
            ->call('save')
            ->assertHasNoErrors();

        $totalSeconds = ($days * 86400) + ($hours * 3600) + ($minutes * 60);
        $coordinates = DB::raw('ST_GeomFromText("POINT(52.234148 21.003730)", 4326)');
        $this->assertDatabaseHas('levels', [
            'name' => 'test level',
            'description' => 'some level description',
            'game_id' => $game->id,
            'availability_time' => $totalSeconds,
        ]);

        $level = \App\Models\Level::where('name', 'test level')->first();
        $this->assertNotNull($level);

        $point = DB::selectOne(
            'SELECT ST_X(coordinates) as lng, ST_Y(coordinates) as lat FROM levels WHERE id = ?',
            [$level->id]
        );
        $this->assertEquals($longitude, $point->lng, '', 0.0001);
        $this->assertEquals($latitude, $point->lat, '', 0.0001);
    }

    public function test_name_is_required()
    {
        $game = Game::factory()->create();
        Livewire::test(CreateLevel::class, ['gameId' => $game->id])
            ->set('name', '')
            ->call('save')
            ->assertHasErrors(['name' => 'required']);
    }

    public function test_name_must_be_at_least_3_characters()
    {
        $game = Game::factory()->create();
        Livewire::test(CreateLevel::class, ['gameId' => $game->id])
            ->set('name', 'ab')
            ->call('save')
            ->assertHasErrors(['name' => 'min']);
    }

    public function test_coordinates_are_required()
    {
        $game = Game::factory()->create();
        Livewire::test(CreateLevel::class, ['gameId' => $game->id])
            ->set('name', 'test level')
            ->set('availability_time_hours', 1)
            ->call('save')
            ->assertHasErrors(['longitude', 'latitude']);
    }

    public function test_latitude_must_be_between_minus_90_and_90()
    {
        $game = Game::factory()->create();
        Livewire::test(CreateLevel::class, ['gameId' => $game->id])
            ->set('latitude', 91)
            ->call('save')
            ->assertHasErrors(['latitude']);
    }

    public function test_longitude_must_be_between_minus_180_and_180()
    {
        $game = Game::factory()->create();
        Livewire::test(CreateLevel::class, ['gameId' => $game->id])
            ->set('latitude', -182)
            ->call('save')
            ->assertHasErrors(['longitude']);
    }

    public function test_availability_time_must_be_greater_than_zero()
    {
        $game = Game::factory()->create();
        Livewire::test(CreateLevel::class, ['gameId' => $game->id])
            ->set('name', 'Test Level')
            ->set('points', 0)
            ->set('latitude', 52.0)
            ->set('longitude', 21.0)
            ->set('availability_time_days', 0)
            ->set('availability_time_hours', 0)
            ->set('availability_time_minutes', 0)
            ->call('save')
            ->assertHasErrors(['availability_time']);
    }

    public function test_availability_time_calculates_correctly()
    {
        $component = Livewire::test(CreateLevel::class, ['gameId' => 1])
            ->set('availability_time_days', 1)
            ->set('availability_time_hours', 2)
            ->set('availability_time_minutes', 30);
        
        $expected = (1 * 86400) + (2 * 3600) + (30 * 60); // 93780
        $this->assertEquals($expected, $component->availabilityTime);
    }

    public function test_availability_time_formatted_displays_correctly()
    {
        $component = Livewire::test(CreateLevel::class, ['gameId' => 1])
            ->set('availability_time_days', 2)
            ->set('availability_time_hours', 1)
            ->set('availability_time_minutes', 30);
        
        $this->assertEquals('2 days, 1 hour, 30 minutes', $component->availabilityTimeFormatted);
    }

    public function test_clear_coordinates_reset_values() {
        Livewire::test(CreateLevel::class, ['gameId' => 1])
            ->set('latitude', 52.0)
            ->set('longitude', 21.0)
            ->set('showMapModal', true)
            ->call('clearCoordinates')
            ->assertSet('latitude', null)
            ->assertSet('longitude', null)
            ->set('showMapModal', false);
    }

    public function test_description_is_purified()
    {
        $game = Game::factory()->create();
        
        Livewire::test(CreateLevel::class, ['gameId' => $game->id])
            ->set('name', 'Test Level')
            ->set('description', '<script>alert("xss")</script><p>Safe text</p>')
            ->set('points', 1)
            ->set('latitude', 52.0)
            ->set('longitude', 21.0)
            ->set('availability_time_hours', 1)
            ->call('save')
            ->assertHasNoErrors();
        
        $level = Level::latest()->first();
        $this->assertStringNotContainsString('<script>', $level->description);
        $this->assertStringContainsString('Safe text', $level->description);
    }

    public function testpoints_must_be_greater_or_equal_than_zero()
    {
        $game = Game::factory()->create();
        Livewire::test(CreateLevel::class, ['gameId' => $game->id])
            ->set('name', 'Test Level')
            ->set('points', -1)
            ->set('latitude', 52.0)
            ->set('longitude', 21.0)
            ->set('availability_time_days', 0)
            ->set('availability_time_hours', 0)
            ->set('availability_time_minutes', 0)
            ->call('save')
            ->assertHasErrors(['points']);
    }

    public function test_dispatches_events_after_successful_save()
    {
        $game = Game::factory()->create();
        Livewire::test(CreateLevel::class, ['gameId' => $game->id])
            ->set('name', 'Test Level')
            ->set('points', 1)
            ->set('latitude', 52.0)
            ->set('longitude', 21.0)
            ->set('availability_time_hours', 1)
            ->call('save')
            ->assertDispatched('refreshComponentLevelsList')
            ->assertDispatched('toast');
    }

    public function test_form_resets_after_successful_save()
    {
        $game = Game::factory()->create();
        Livewire::test(CreateLevel::class, ['gameId' => $game->id])
            ->set('name', 'Test Level')
            ->set('description', 'Description')
            ->set('points', 0)
            ->set('latitude', 52.0)
            ->set('longitude', 21.0)
            ->set('availability_time_hours', 1)
            ->call('save')
            ->assertSet('name', null)
            ->assertSet('description', null)
            ->assertSet('latitude', null);
    }
}