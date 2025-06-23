<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Exceptions\UserAlreadyExistsException;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\User\DTOs\UserDTO;
use App\Services\User\Interfaces\UserServiceInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class UserController extends Controller
{
    /**
     * @param UserServiceInterface $userService
     */
    public function __construct(
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
        $this->authorize('viewAny', User::class);

        $user      = $request->user();
        $managerId = $user ? $user->id : 0;

        $employees = $this->userService->getUsersByManager($managerId, 10)
            ->appends($request->except('page'));

        return view('users.index', [
            'employees' => $employees,
        ]);
    }

    /**
     * @return View
     * @throws AuthorizationException
     */
    public function create(): View
    {
        $this->authorize('create', User::class);

        return view('users.create');
    }

    /**
     * @throws AuthorizationException
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $this->authorize('create', User::class);

        try {
            $userDTO = UserDTO::fromArray($request->validated());
            $this->userService->createUser($userDTO);

            return redirect()->route('users.index')
                ->with('status', 'Funcionário cadastrado com sucesso!');
        } catch (Throwable $throwable) {
            report($throwable);

            return redirect()->back()
                ->withErrors([
                    'error' => 'Erro ao cadastrar funcionário. Tente novamente mais tarde.',
                ])
                ->withInput();
        }
    }

    /**
     * @param User $user
     * @return View
     * @throws AuthorizationException
     */
    public function show(User $user): View
    {
        $this->authorize('view', $user);

        return view('users.show', [
            'employee' => $user,
        ]);
    }

    /**
     * @param User $user
     * @return View
     * @throws AuthorizationException
     */
    public function edit(User $user): View
    {
        $this->authorize('update', $user);

        return view('users.edit', [
            'employee' => $user,
        ]);
    }

    /**
     * @param UpdateUserRequest $request
     * @param User $user
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $this->authorize('update', $user);

        try {
            $userDTO = UserDTO::fromArray($request->validated());
            $this->userService->updateUser($user, $userDTO);

            return redirect()->route('users.index')
                ->with('status', 'Funcionário atualizado com sucesso!');
        } catch (Throwable $throwable) {
            report($throwable);

            return redirect()->back()
                ->withErrors([
                    'error' => 'Erro ao atualizar funcionário. Tente novamente mais tarde.',
                ])
                ->withInput();
        }
    }

    /**
     * @param User $user
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete', $user);

        try {
            $this->userService->deleteUser($user);

            return redirect()->route('users.index')
                ->with('status', 'Funcionário excluído com sucesso!');
        } catch (Throwable $throwable) {
            report($throwable);

            return redirect()->back()
                ->withErrors([
                    'error' => 'Erro ao excluir funcionário. Tente novamente mais tarde.',
                ]);
        }
    }
}
