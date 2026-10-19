<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_form_is_available_to_guests(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Admin sign in')
            ->assertSee('name="email"', false);
    }

    public function test_admin_can_sign_in_and_view_the_dashboard(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'is_admin' => true,
        ]);

        $this->assertTrue($admin->is_admin);
        $this->assertTrue(Hash::check('password', $admin->password));

        $response = $this->post(route('login.authenticate'), [
            'email' => $admin->email,
            'password' => 'password',
            'remember' => '1',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($admin);

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Welcome, Admin User')
            ->assertSee('admin@example.com');
    }

    public function test_non_admin_cannot_sign_in(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'is_admin' => false,
        ]);

        $this->from(route('login'))
            ->post(route('login.authenticate'), [
                'email' => $user->email,
                'password' => 'password',
            ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_dashboard_requires_authentication_and_admin_can_sign_out(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create(['is_admin' => false]))
            ->get(route('dashboard'))
            ->assertForbidden();

        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin)
            ->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }
}
