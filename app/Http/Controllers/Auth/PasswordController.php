<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     * 
     * @throws ValidationException
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user = $request->user();
        
        if ($user === null) {
            return back()->with('error', 'Usuário não encontrado.');
        }
        
        $password = '';
        if (is_array($validated) && isset($validated['password']) && is_string($validated['password'])) {
            $password = $validated['password'];
        }
        
        $user->update([
            'password' => Hash::make($password),
        ]);

        return back()->with('status', 'password-updated');
    }
}
