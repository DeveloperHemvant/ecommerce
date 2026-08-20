<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('admin login screen can be rendered', function () {
    $response = $this->get('/admin/login');

    $response->assertStatus(200);
});

test('admin can authenticate using valid credentials', function () {
    $user = User::factory()->create([
        'email' => 'admin_test@gmail.com',
        'password' => Hash::make('12345678'),
        'role' => 'admin',
    ]);

    $response = $this->post('/admin/login', [
        'email' => 'admin_test@gmail.com',
        'password' => '12345678',
    ]);

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect(route('admin.dashboard'));
});

test('non admin user cannot access admin dashboard', function () {
    $customer = User::factory()->create([
        'email' => 'customer_test@gmail.com',
        'password' => Hash::make('12345678'),
        'role' => 'customer',
    ]);

    $response = $this->actingAs($customer)->get('/admin');

    $response->assertRedirect(route('admin.login'));
});

test('admin cannot authenticate with invalid password', function () {
    User::factory()->create([
        'email' => 'admin_test2@gmail.com',
        'password' => Hash::make('12345678'),
        'role' => 'admin',
    ]);

    $this->post('/admin/login', [
        'email' => 'admin_test2@gmail.com',
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('customer can register and authenticate', function () {
    $response = $this->post('/register', [
        'name' => 'New Customer',
        'email' => 'newcustomer@example.com',
        'phone' => '+91 99999 88888',
        'password' => '12345678',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('collections'));
});

test('admin can view dashboard with dynamic metrics and date range filter', function () {
    $admin = User::factory()->create([
        'name' => 'Sonakshi Admin',
        'email' => 'admin@gmail.com',
        'password' => Hash::make('12345678'),
        'role' => 'admin',
    ]);

    $response = $this->actingAs($admin)->get('/admin?range=today');

    $response->assertStatus(200);
    $response->assertSee('Dashboard Overview');
    $response->assertSee('Total Revenue');
    $response->assertSee('Revenue Trajectory');
});
