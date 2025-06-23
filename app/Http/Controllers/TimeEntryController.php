<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Events\TimeEntryRegistered;
use App\Models\TimeEntry;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class TimeEntryController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', TimeEntry::class);

        $userId    = $request->query('user_id', Auth::id());
        $startDate = $this->parseDate($request->query('start_date'), Carbon::now()->startOfMonth());
        $endDate   = $this->parseDate($request->query('end_date'), Carbon::now()->endOfDay());

        $timeEntries = TimeEntry::query()
            ->with('user')
            ->InDateRange(
                $startDate->startOfDay(),
                $endDate->endOfDay(),
            )
            ->where('user_id', $userId)
            ->orderBy('recorded_at', 'desc')
            ->paginate(15)
            ->appends($request->except('page'));

        $users = [];

        if ($this->userCan('viewOtherUserEntries')) {
            $users = User::orderBy('name')->get(['id', 'name']);
        }

        // Obter o último registro de ponto do usuário atual
        $lastTimeEntry = TimeEntry::query()
            ->where('user_id', Auth::id())
            ->latest('recorded_at')
            ->first();

        // Verificar se o último registro foi feito hoje
        $registeredToday = $lastTimeEntry && $lastTimeEntry->recorded_at->format('Y-m-d') === Carbon::today()->format('Y-m-d');
        
        // Obter o nome do usuário atual para exibição
        $currentUser = User::find($userId);
        $userName = $currentUser ? $currentUser->name : 'Usuário não encontrado';

        return view(
            'time-entries.index',
            [
                'timeEntries' => $timeEntries, 
                'userId' => $userId, 
                'startDate' => $startDate, 
                'endDate' => $endDate, 
                'users' => $users, 
                'lastTimeEntry' => $lastTimeEntry, 
                'registeredToday' => $registeredToday,
                'userName' => $userName
            ]
        );
    }

    /**
     * @throws AuthorizationException
     */
    public function store(Request $request): RedirectResponse
    {
        $userId = Auth::id();

        if (TimeEntry::todayForUser($userId)->exists()) {
            return redirect()->route('time-entries.index')
                ->with('error', 'Você já registrou um ponto nos últimos 2 minutos.');
        }

        $timeEntry = TimeEntry::query()->create([
            'user_id'     => $userId,
            'recorded_at' => now(),
        ]);

        event(new TimeEntryRegistered($timeEntry));

        return redirect()->route('time-entries.index')
            ->with('status', 'Ponto registrado com sucesso!');
    }

    /**
     * Converte uma string de data em um objeto Carbon.
     */
    private function parseDate(?string $date, Carbon $default): Carbon
    {
        return $date !== null && $date !== '' && $date !== '0' ? Carbon::parse($date) : $default;
    }

    /**
     * Verifica se o usuário atual tem uma determinada permissão.
     */
    private function userCan(string $ability, $arguments = []): bool
    {
        return Auth::check() && Gate::allows($ability, $arguments ?: TimeEntry::class);
    }
}
