<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', User::class);
        
        $employees = User::query()
            ->where('role', UserRole::EMPLOYEE->value)
            ->orderBy('name')
            ->paginate(10);
            
        return view('users.index', [
            'employees' => $employees,
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function create(): View
    {
        $this->authorize('create', User::class);
        
        return view('users.create');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        
        $admin = $request->user();
        
        if ($admin === null) {
            abort(403);
        }

        User::query()->create([
            'name'         => $validated['name'],
            'cpf'          => $validated['cpf'],
            'email'        => $validated['email'],
            'password'     => Hash::make($validated['password']),
            'job_position' => $validated['job_position'],
            'birth_date'   => $validated['birth_date'],
            'zip_code'     => $validated['zip_code'],
            'street'       => $validated['street'],
            'number'       => $validated['number'] ?? null,
            'complement'   => $validated['complement'] ?? null,
            'neighborhood' => $validated['neighborhood'],
            'city'         => $validated['city'],
            'state'        => $validated['state'],
            'role'         => UserRole::EMPLOYEE->value,
            'manager_id'   => $admin->id,
        ]);

        return redirect()->route('users.index')
            ->with('status', 'Funcionário cadastrado com sucesso!');
    }

    /**
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
     * @throws AuthorizationException
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $this->authorize('update', $user);

        $validated = $request->validated();
        
        $updateData = [
            'name'         => $validated['name'],
            'cpf'          => $validated['cpf'],
            'email'        => $validated['email'],
            'job_position' => $validated['job_position'],
            'birth_date'   => $validated['birth_date'],
            'zip_code'     => $validated['zip_code'],
            'street'       => $validated['street'],
            'number'       => $validated['number'] ?? null,
            'complement'   => $validated['complement'] ?? null,
            'neighborhood' => $validated['neighborhood'],
            'city'         => $validated['city'],
            'state'        => $validated['state'],
        ];

        $user->update($updateData);

        return redirect()->route('users.index')
            ->with('status', 'Funcionário atualizado com sucesso!');
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete', $user);

        $user->delete();

        return redirect()->route('users.index')
            ->with('status', 'Funcionário excluído com sucesso!');
    }
}
