<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\TimeEntry;
use App\Models\User;
use App\Repositories\TimeEntry\TimeEntryRepositoryInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{

    public function __construct(
       private readonly TimeEntryRepositoryInterface $repository
    ) {
    }

    public function index(): View
    {
        $lastTimeEntry = $this->repository->findLastByUser(Auth::id());

        $registeredToday = $lastTimeEntry && $lastTimeEntry->recorded_at->format('Y-m-d') === Carbon::today()->format('Y-m-d');

        $stats = [];

        $user = Auth::user();
        if ($user && $user->role === UserRole::ADMIN) {
            $stats = [
                'total_users'         => User::count(),
                'total_entries_today' => TimeEntry::whereDate('recorded_at', Carbon::today())->count(),
                'total_entries_month' => TimeEntry::whereMonth('recorded_at', Carbon::now()->month)
                    ->whereYear('recorded_at', Carbon::now()->year)
                    ->count(),
            ];
        }

        return view(
            'dashboard',
            ['lastTimeEntry' => $lastTimeEntry, 'registeredToday' => $registeredToday, 'stats' => $stats]
        );
    }
}
