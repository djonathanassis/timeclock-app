<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $user = $request->user();

        if ($user === null) {
            return redirect()->route('login')
                ->with('error', 'Você precisa estar autenticado para verificar seu e-mail.');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
        }

        if ($user->markEmailAsVerified()) {
            // Garantir que o usuário implementa MustVerifyEmail
            if ($user instanceof MustVerifyEmail) {
                event(new Verified($user));
            }
        }

        return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
    }
}
