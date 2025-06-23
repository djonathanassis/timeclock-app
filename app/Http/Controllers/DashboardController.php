<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Models\TimeEntry;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Obter o último registro de ponto do usuário atual
        $lastTimeEntry = TimeEntry::query()
            ->where('user_id', Auth::id())
            ->latest('recorded_at')
            ->first();

        // Verificar se o último registro foi feito hoje
        $registeredToday = $lastTimeEntry && $lastTimeEntry->recorded_at->format('Y-m-d') === Carbon::today()->format('Y-m-d');

        // Estatísticas para administradores
        $stats = [];

        if (Auth::user()->role === \App\Enums\UserRole::ADMIN) {
            $stats = [
                'total_users'         => User::count(),
                'total_entries_today' => TimeEntry::whereDate('recorded_at', Carbon::today())->count(),
                'total_entries_month' => TimeEntry::whereMonth('recorded_at', Carbon::now()->month)
                    ->whereYear('recorded_at', Carbon::now()->year)
                    ->count(),
            ];
        }

        return view('dashboard', ['lastTimeEntry' => $lastTimeEntry, 'registeredToday' => $registeredToday, 'stats' => $stats]);
    }
}
