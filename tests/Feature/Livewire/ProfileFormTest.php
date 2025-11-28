<?php

namespace Tests\Feature\Livewire;

use App\Livewire\ProfileForm;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProfileFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_mounts_with_user_data()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'contact_telegram' => '@test',
            'contact_whatsapp' => '1234567890',
        ]);

        $this->actingAs($user);

        Livewire::test(ProfileForm::class)
            ->assertSet('name', 'Test User')
            ->assertSet('email', 'test@example.com')
            ->assertSet('contact_telegram', '@test')
            ->assertSet('contact_whatsapp', '1234567890');
    }

    public function test_updates_profile_information()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        Livewire::test(ProfileForm::class)
            ->set('name', 'New Name')
            ->set('contact_telegram', '@newtelegram');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Name',
            'contact_telegram' => '@newtelegram',
        ]);
    }

    public function test_validation_rules()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(ProfileForm::class)
            ->set('name', '')
            ->assertHasErrors(['name' => 'required']);
    }
}
