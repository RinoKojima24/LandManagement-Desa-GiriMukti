<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): Response
    {
        $request->validate([
            'name' => ['required', 'string'],
            'username' => ['required', 'string', 'alpha_dash', 'max:255', 'unique:users,username'],
            'nama_petugas' => ['required', 'string', 'lowercase', 'username', 'max:255'],
            'no_telpon' => ['required', 'string', 'numeric'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,super_admin,operator,viewer'],
        ]);
            $data = null;
            if(!isset($request->role)){
                $data['role'] = 'viewer';
            }
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password_hash' => Hash::make($request->string('password')),
            'nama_petugas' => $request->nama_petugas,
            'no_telpon' => $request->no_telpon,
            'role' => $request->role ?? $data['role'],
            'is_active' => $request->has('is_active') ? true : false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);p

        event(new Registered($user));

        Auth::login($user);

        return response()->noContent();
    }
}
