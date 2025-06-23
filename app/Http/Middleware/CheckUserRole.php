<?php

declare(strict_types = 1);

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * @param Request $request
     * @param Closure $next
     * @param  string  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!$user || !isset($user->role)) {
            abort(403, 'Acesso não autorizado.');
        }

        $userRole = $user->role->value;

        if (in_array($userRole, $roles, true)) {
            return $next($request);
        }

        abort(403, 'Você não tem permissão para acessar este recurso.');
    }
} 
