<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Helpers\DateHelper;
use App\Models\TimeEntry;
use App\Services\TimeEntry\Interfaces\TimeEntryServiceInterface;
use App\Services\User\Interfaces\UserServiceInterface;
use Exception;
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
     * @param TimeEntryServiceInterface $timeEntryService
     * @param UserServiceInterface $userService
     */
    public function __construct(
        private readonly TimeEntryServiceInterface $timeEntryService,
        private readonly UserServiceInterface $userService
    ) {
    }

    /**
     * @param Request $request
     * @return View
     * @throws AuthorizationException
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', TimeEntry::class);

        $userId    = (int) $request->query('user_id', Auth::id());
        $startDate = DateHelper::parseDate($request->query('start_date'), Carbon::now()->startOfMonth());
        $endDate   = DateHelper::parseDate($request->query('end_date'), Carbon::now()->endOfDay());

        $timeEntries = $this->timeEntryService->listTimeEntries(
            $userId,
            $startDate,
            $endDate,
            $request->except('page')
        );

        $users = [];

        if ($this->userCan('viewOtherUserEntries')) {
            $users = $this->userService->getAllUsers();
        }

        $lastTimeEntry = $this->timeEntryService->getLastTimeEntry((int) Auth::id());

        $registeredToday = $lastTimeEntry && $lastTimeEntry->recorded_at->format('Y-m-d') === Carbon::today()->format('Y-m-d');

        $currentUser = $this->userService->getUserById($userId);
        $userName    = $currentUser->name ?? 'Usuário não encontrado';

        return view(
            'time-entries.index',
            [
                'timeEntries'     => $timeEntries,
                'userId'          => $userId,
                'startDate'       => $startDate,
                'endDate'         => $endDate,
                'users'           => $users,
                'lastTimeEntry'   => $lastTimeEntry,
                'registeredToday' => $registeredToday,
                'userName'        => $userName,
            ]
        );
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('registerTimeEntry', TimeEntry::class);

        try {
            $this->timeEntryService->registerTimeEntry((int) Auth::id());

            return redirect()->route('time-entries.index')
                ->with('status', 'Ponto registrado com sucesso!');
        } catch (Exception $e) {
            return redirect()->route('time-entries.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * @param string $ability
     * @param mixed $arguments
     * @return bool
     */
    private function userCan(string $ability, mixed $arguments = []): bool
    {
        return Auth::check() && Gate::allows($ability, $arguments ?: TimeEntry::class);
    }
}
