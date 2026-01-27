<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminSetupController extends Controller
{
    protected function setupAllowed(): bool
    {
        return User::query()->where('is_admin', true)->count() === 0;
    }

    public function create()
    {
        abort_unless($this->setupAllowed(), 404);
        return view('admin.setup.register');
    }

    public function store(Request $request)
    {
        abort_unless($this->setupAllowed(), 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->is_admin = true;
        $user->must_change_password = true;
        $user->save();

        auth()->login($user);

        return redirect()->route('admin.security')->with('status', 'Admin created. Please change your password before continuing.');
    }
}
