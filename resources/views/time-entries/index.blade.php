<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Registros de Ponto') }}
            </h2>
            @can('registerTimeEntry', App\Models\TimeEntry::class)
            <form action="{{ route('time-entries.store') }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    {{ __('Registrar Ponto') }}
                </button>
            </form>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('status'))
                        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('status') }}</span>
                            <button @click="show = false" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                                <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                                </svg>
                            </button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                            <button @click="show = false" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                                <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                                </svg>
                            </button>
                        </div>
                    @endif

                    @can('registerTimeEntry', App\Models\TimeEntry::class)
                    <div class="mb-6">
                        @if(isset($lastTimeEntry))
                        <div class="mb-2 text-center {{ $registeredToday ? 'text-green-600' : 'text-gray-600' }}">
                            <p>Último registro: <span class="font-semibold">{{ $lastTimeEntry->recorded_at->format('d/m/Y H:i:s') }}</span></p>
                            @if($registeredToday)
                            <p class="text-sm">Você já registrou ponto hoje!</p>
                            @endif
                        </div>
                        @endif
                        
                        <form action="{{ route('time-entries.store') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full {{ $registeredToday ?? false ? 'bg-blue-600 hover:bg-blue-700' : 'bg-green-600 hover:bg-green-700 animate-pulse' }} text-white font-bold py-4 px-6 rounded-lg flex items-center justify-center text-lg transition-all duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $registeredToday ?? false ? 'REGISTRAR NOVO PONTO' : 'REGISTRAR PONTO AGORA' }}
                            </button>
                        </form>
                        
                        <div class="mt-2 text-center text-sm text-gray-500">
                            Horário atual: <span class="font-semibold" id="current-time"></span>
                        </div>
                    </div>
                    @endcan

                    <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                        <form action="{{ route('time-entries.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            @if (Auth::user()->role === \App\Enums\UserRole::ADMIN)
                            <div>
                                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Funcionário</label>
                                <select name="user_id" id="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Todos os funcionários</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" {{ $userId == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Data Inicial</label>
                                <input type="date" name="start_date" id="start_date" value="{{ $startDate->format('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>

                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Data Final</label>
                                <input type="date" name="end_date" id="end_date" value="{{ $endDate->format('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>

                            <div class="flex items-end space-x-2">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    Filtrar
                                </button>
                                <a href="{{ route('time-entries.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    Limpar
                                </a>
                                @if (Auth::user()->role === \App\Enums\UserRole::ADMIN)
                                <a href="{{ route('time-entries.report') }}" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    Relatório
                                </a>
                                @endif
                            </div>
                        </form>
                    </div>

                    <div class="overflow-x-auto bg-white rounded-lg shadow">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    @if (Auth::user()->role === \App\Enums\UserRole::ADMIN && !$userId)
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Funcionário</th>
                                    @endif
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hora</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($timeEntries as $entry)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $entry->id }}</div>
                                        </td>
                                        @if (Auth::user()->role === \App\Enums\UserRole::ADMIN && !$userId)
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $entry->user->name ?? 'N/A' }}</div>
                                        </td>
                                        @endif
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">{{ $entry->recorded_at->format('d/m/Y') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $entry->recorded_at->format('H:i:s') }}</div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ Auth::user()->role === \App\Enums\UserRole::ADMIN && !$userId ? '4' : '3' }}" class="px-6 py-8 whitespace-nowrap text-sm text-gray-500 text-center">
                                            <div class="flex flex-col items-center justify-center space-y-2">
                                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span>Nenhum registro de ponto encontrado.</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $timeEntries->links() }}
                    </div>
                    
                    @if ($timeEntries->count() > 0)
                    <div class="mt-4 bg-blue-50 p-4 rounded-lg text-sm">
                        <p class="font-medium text-blue-800">
                            Mostrando {{ $timeEntries->firstItem() }} a {{ $timeEntries->lastItem() }} de {{ $timeEntries->total() }} registros de ponto.
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Atualização automática da página
            const today = new Date().toISOString().split('T')[0];
            const endDateInput = document.getElementById('end_date');
            
            if (endDateInput && endDateInput.value === today) {
                setTimeout(function() {
                    window.location.reload();
                }, 300000); // 5 minutos
            }
            
            // Relógio em tempo real
            const currentTimeElement = document.getElementById('current-time');
            if (currentTimeElement) {
                function updateClock() {
                    const now = new Date();
                    const hours = String(now.getHours()).padStart(2, '0');
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    const seconds = String(now.getSeconds()).padStart(2, '0');
                    currentTimeElement.textContent = `${hours}:${minutes}:${seconds}`;
                }
                
                // Atualiza a cada segundo
                updateClock();
                setInterval(updateClock, 1000);
            }
        });
    </script>
    @endpush
</x-app-layout> 