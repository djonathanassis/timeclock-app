<?php

declare(strict_types = 1);

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * @param Request $request
     * @param Closure $next
     * @param string ...$roles
     * @return Response
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (! $user || (! property_exists($user, 'role') || $user->role === null)) {
            abort(403, 'Acesso não autorizado.');
        }

        // Lidar com casos em que role é um enum ou uma string
        $userRole = $user->role instanceof UserRole ? $user->role->value : $user->role;
        
        // Debug para verificar o tipo e valor da role
        Log::info('User Role Type: ' . gettype($userRole));
        Log::info('User Role Value: ' . $userRole);
        Log::info('Expected Roles: ' . implode(', ', $roles));
        
        foreach ($roles as $role) {
            if (strcasecmp($userRole, $role) === 0) {
                return $next($request);
            }
        }

        abort(403, 'Você não tem permissão para acessar este recurso.');
    }
}
