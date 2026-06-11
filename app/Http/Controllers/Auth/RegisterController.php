<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function index(){
        return view('auth.register');
    }

    public function store(SignupRequest $request){
        // $name = $request->input("name");
        // $email = $request->input('email');

        // $data = $request->validate([
        //     'name' => ['required', 'string'],
        //     'email' => ['required', 'email'],
        // ], [
        //     'name.required' => 'El Nombre es obligatorio',
        //     'email.required' => 'El Email es obligatorio',
        //     'email.email' => 'Error en el formato email'
        // ]);

        $data = $request->validated();

        User::create($data);

        
        // return "Hola: $name y tu email es: $email";
    }
}
