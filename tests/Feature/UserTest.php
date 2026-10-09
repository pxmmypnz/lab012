<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_regular_user_can_manage_only_their_self_service_profile(): void
    {
        $user = User::where('email', 'user@my-db.com')->firstOrFail();

        $this->actingAs($user)
            ->get('/users')
            ->assertForbidden();

        $this->get(route('users.selves.view'))
            ->assertOk()
            ->assertSee('user@my-db.com')
            ->assertSee('USER');

        $this->from(route('users.selves.update-form'))
            ->post(route('users.selves.update'), [
                'name' => 'Updated User',
                'email' => 'changed@example.com',
                'role' => 'ADMIN',
                'password' => '',
            ])
            ->assertRedirect(route('users.selves.view'))
            ->assertSessionHas('status', 'Your information was updated.');

        $user->refresh();
        $this->assertSame('Updated User', $user->name);
        $this->assertSame('user@my-db.com', $user->email);
        $this->assertSame('USER', $user->role);
        $this->assertTrue(Hash::check('1234', $user->password));

        $this->post(route('users.delete', ['user' => $user->email]))
            ->assertForbidden();
    }

    public function test_administrator_can_create_search_update_and_delete_users_but_not_modify_self_role(): void
    {
        $admin = User::where('email', 'admin@my-db.com')->firstOrFail();
        $this->actingAs($admin);

        $this->get('/users')->assertOk()->assertSee('admin@my-db.com');

        $this->get('/users?term=USER')
            ->assertOk()
            ->assertSee('user@my-db.com')
            ->assertDontSee('admin@my-db.com');

        $this->from(route('users.list', ['term' => 'user']))
            ->post(route('users.create'), [
                'name' => 'New User',
                'email' => 'new@example.com',
                'password' => '1234',
                'role' => 'USER',
            ])
            ->assertRedirect(route('users.view', ['user' => 'new@example.com']))
            ->assertSessionHas('status', 'User new@example.com was created.')
            ->assertSessionHas('bookmarks.users.view', route('users.list', ['term' => 'USER']));

        $created = User::where('email', 'new@example.com')->firstOrFail();
        $this->assertTrue(Hash::check('1234', $created->password));

        $this->post(route('users.update', ['user' => $created->email]), [
            'name' => 'Renamed User',
            'email' => 'ignored@example.com',
            'password' => '',
            'role' => 'ADMIN',
        ])->assertRedirect(route('users.view', ['user' => $created->email]));

        $created->refresh();
        $this->assertSame('new@example.com', $created->email);
        $this->assertSame('Renamed User', $created->name);
        $this->assertSame('ADMIN', $created->role);
        $this->assertTrue(Hash::check('1234', $created->password));

        $this->post(route('users.update', ['user' => $admin->email]), [
            'name' => 'Changed Admin',
            'email' => 'ignored@example.com',
            'password' => '',
            'role' => 'USER',
        ])->assertRedirect(route('users.view', ['user' => $admin->email]));

        $admin->refresh();
        $this->assertSame('Changed Admin', $admin->name);
        $this->assertSame('admin@my-db.com', $admin->email);
        $this->assertSame('ADMIN', $admin->role);

        $this->post(route('users.delete', ['user' => $admin->email]))
            ->assertForbidden();

        $this->post(route('users.delete', ['user' => $created->email]))
            ->assertRedirect(route('users.index'));
        $this->assertDatabaseMissing('users', ['email' => 'new@example.com']);
    }
}
