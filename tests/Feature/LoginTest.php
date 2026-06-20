<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('Shows the login screen', function(){
    $response= $this->get(route('login'));
    $response->assertOk();
});

test('Logs in a verified use successfully', function(){
    User::factory()->create([
        'email' => 'juan@juan.com',
        'password' => bcrypt('Password8!'),
        'email_verified_at' => now()
    ]);

     $response= $this->post(route('login.store'), [
        'email' => 'juan@juan.com',
        'password' => 'Password8!',
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticated();
});

test('Does not log in with invalid credentials', function(){
    User::factory()->create([
        'email' => 'juan@juan.com',
        'password' => bcrypt('Password8!'),
    ]);

     $response= $this->from(route('login'))->post(route('login.store'), [
        'email' => 'juan@juan.com',
        'password' => 'incorrect-Password8!',
    ]);

    $response->assertRedirect(route('login'));
    $response->assertSessionHas('error', 'Credenciales incorrectas');

    $this->assertGuest();
});

test('Prevents unverified user from accessing dashboard', function(){
    User::factory()->unverified()->create([
        'email' => 'juan@juan.com',
        'password' => bcrypt('Password8!'),
    ]);

     $response= $this->post(route('login.store'), [
        'email' => 'juan@juan.com',
        'password' => 'Password8!',
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticated();

    $dashboardResponse = $this->get(route('dashboard'));
    $dashboardResponse->assertRedirect(route('verification.notice'));
});

test('Does not allow access to dashboard if email is not verified', function(){
    $user= User::factory()->create([
        'email_verified_at' => null
    ]);

    $response = $this->actingAs($user)->get(route('dashboard'));
    $response->assertRedirect(route('verification.notice'));
});

test('Allow access to dashboard if email is verified', function(){
    $user= User::factory()->create([
        'email_verified_at' => now()
    ]);

    $response = $this->actingAs($user)->get(route('dashboard'));
    $response->assertOk();
});

test('Fails login if user does not exists', function(){
    $response = $this->from(route('login'))
                        ->post(route('login.store'), [
                            'email' => 'noexiste@dominio.com',
                            'password' => 'Password8!'
                        ]);

    $response->assertRedirect(route('login'));
    $response->assertSessionHasErrors([
        'email' => 'No encontramos una cuenta con este correo electronico'
    ]);

    $this->assertGuest();
});