<?php

namespace Tests\Feature\Livewire;

use App\Models\Location;
use App\Models\User;
use App\Livewire\CreateLocation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Testcase;

class CreateLocationTest extends Testcase
{
    use RefreshDatabase;

    public function test_renders_successfully()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(CreateLocation::class)
            ->assertStatus(200);
    }

    public function test_title_is_required()
    {
        Livewire::test(CreateLocation::class)
            ->set('title', '')
            ->call('save')
            ->assertHasErrors(['title' => 'required']);
    }

    public function test_title_must_be_at_least_3_characters()
    {
        Livewire::test(CreateLocation::class)
            ->set('title', 'ab')
            ->call('save')
            ->assertHasErrors(['title' => 'min']);
    }

    public function test_title_must_be_max_255_characters()
    {
        Livewire::test(CreateLocation::class)
            ->set('title', str_repeat('a', 256))
            ->call('save')
            ->assertHasErrors(['title' => 'max']);
    }

    public function test_title_must_be_unique()
    {
        Location::create(['title' => 'Existing Location']);

        Livewire::test(CreateLocation::class)
            ->set('title', 'Existing Location')
            ->call('save')
            ->assertHasErrors(['title' => 'unique']);
    }

    public function test_can_create_location_with_unique_title()
    {
        Livewire::test(CreateLocation::class)
            ->set('title', 'Location 1')
            ->call('save')
            ->assertHasNoErrors();

        Livewire::test(CreateLocation::class)
            ->set('title', 'Location 2')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('locations', ['title' => 'Location 2']);
    }

    public function test_title_is_purified_from_html()
    {
        Livewire::test(CreateLocation::class)
            ->set('title', '<script>alert("xss")</script>Safe Title<b>bold</b>')
            ->call('save')
            ->assertHasNoErrors();

        $location = Location::latest()->first();
        $this->assertStringNotContainsString('<script>', $location->title);
        $this->assertStringNotContainsString('<b>', $location->title);
        $this->assertStringContainsString('Safe Title', $location->title);
    }

    public function test_title_is_trimmed()
    {
        Livewire::test(CreateLocation::class)
            ->set('title', '  Test Location  ')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('locations', [
            'title' => 'Test Location'
        ]);
    }

    public function test_dispatches_events_after_successful_save()
    {
        Livewire::test(CreateLocation::class)
            ->set('title', 'Test Location')
            ->call('save')
            ->assertDispatched('refreshComponentLocationsList')
            ->assertDispatched('toast');
    }

    public function test_form_resets_after_successful_save()
    {
        Livewire::test(CreateLocation::class)
            ->set('title', 'Test Location')
            ->set('showAddLocationModal', true)
            ->call('save')
            ->assertSet('title', null)
            ->assertSet('showAddLocationModal', false);
    }

    public function test_validates_title_on_update()
    {
        Livewire::test(CreateLocation::class)
            ->set('title', 'ab')
            ->assertHasErrors(['title' => 'min'])
            ->set('title', 'Valid Title')
            ->assertHasNoErrors();
    }
}