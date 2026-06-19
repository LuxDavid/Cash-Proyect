<?php

use App\Models\User;
use App\Notifications\VerifyEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;

uses(RefreshDatabase::class);

test('shows the resitration screen', function () {
    $response = $this->get(route('register'));

    $response->assertOK();

    $response->assertStatus(200);
    $response->assertSee('Crear cuenta');
    
    $response->assertSeeInOrder([
        'Crear cuenta'
    ]);
});

test('Register a new user as unverified and dispatches the registed event', function(){
    Event::fake();

    $response = $this->post(route('register.store'), [
        'name' => 'Juan Perez',
        'email' => 'juan@juan.com',
        'password' => 'Password86!',
        'password_confirmation' => 'Password86!'
    ]);

    $response->assertRedirect(route('verification.notice'));

    $user = User::where('email', 'juan@juan.com')->first();

    expect($user)->not->toBeNull();
    expect($user->name)->toBe('Juan Perez');
    expect($user->email)->toBe('juan@juan.com');
    expect($user->hasVerifiedEmail())->toBeFalse();

    Event::assertDispatched(Registered::class);
});

test('Should validate required fields when the request body is empty', function(){
    $response = $this->post(route('register.store'), []);

    $response->assertSessionHasErrors([
        'name',
        'email',
        'password'
    ]);

    $response ->assertSessionHasErrors([
        'name' => 'El Nombre es obligatorio',
        'email' => 'El Email es obligatorio',
        'password' => 'La Contraseña es obligatoria',
    ]);
});

test('Prevents duplicate email address', function(){
    Event::fake();

    User::factory()->create([
        'email' => 'juan@juan.com'
    ]);

    $response = $this->post(route('register.store'), [
        'name' => 'Juan Perez',
        'email' => 'juan@juan.com',
        'password' => 'Password86!',
        'password_confirmation' => 'Password86!'
    ]);

    $response->assertRedirect();

    $response ->assertSessionHasErrors([
        'email' => 'Este correo ya está registrado'
    ]);

});

test('Sends the verification email notificaction after registration', function(){
    Notification::fake();

    $response = $this->post(route('register.store'), [
        'name' => 'Juan Perez',
        'email' => 'juan@juan.com',
        'password' => 'Password86!',
        'password_confirmation' => 'Password86!'
    ]);

    $user = User::where('email', 'juan@juan.com')->first();

    Notification::assertSentTo($user, VerifyEmail::class);
});

test('Verifies the user email from a signed verification link', function(){
    $user= User::factory()->unverified()->create();

      $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1($user->email)
            ]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        $response->assertRedirect(route('dashboard'));

        expect($user->hasVerifiedEmail())->toBeTrue();
});

test('Does not allow an unverifies user to access the dashboard', function(){
    $user= User::factory()->unverified()->create();
    $response= $this->actingAs($user)->get(route('dashboard'));
    $response->assertRedirect(route('verification.notice'));
});

test('Allorws a verifies user to access the dashboard', function(){
    $user= User::factory()->create([
        'email_verified_at' => now()
    ]);

    $response= $this->actingAs($user)->get(route('dashboard'));
    $response->assertOk();
});