<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Componente de Registro de Ponto -->
            @can('registerTimeEntry', App\Models\TimeEntry::class)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ __('Registro de Ponto') }}
                    </h2>
                    
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex-1">
                            <div class="text-center md:text-left mb-4 md:mb-0">
                                <div class="text-sm text-gray-500 mb-1">Horário atual:</div>
                                <div class="text-2xl font-bold text-gray-800" id="current-time">--:--:--</div>
                                
                                @if(isset($lastTimeEntry))
                                <div class="mt-2 {{ $registeredToday ? 'text-green-600' : 'text-gray-600' }}">
                                    <div class="text-sm">Último registro:</div>
                                    <div class="font-medium">{{ $lastTimeEntry->recorded_at->format('d/m/Y H:i:s') }}</div>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex-1">
                            <form action="{{ route('time-entries.store') }}" method="POST">
                                @csrf
                                <button type="submit" class="{{ $registeredToday ?? false ? 'bg-blue-600 hover:bg-blue-700' : 'bg-green-600 hover:bg-green-700 animate-pulse' }} w-full text-white font-bold py-4 px-6 rounded-lg flex items-center justify-center text-lg transition-all duration-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $registeredToday ?? false ? 'REGISTRAR NOVO PONTO' : 'REGISTRAR PONTO AGORA' }}
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between items-center">
                        <div class="text-sm text-gray-500">
                            @if($registeredToday ?? false)
                            <span class="text-green-600 font-medium">✓ Você já registrou ponto hoje</span>
                            @else
                            <span class="text-yellow-600 font-medium">⚠️ Você ainda não registrou ponto hoje</span>
                            @endif
                        </div>
                        <a href="{{ route('time-entries.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Ver todos os registros →
                        </a>
                    </div>
                </div>
            </div>
            @endcan
            
            <!-- Informações de Boas-vindas -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">
                        {{ __('Bem-vindo!') }}
                    </h2>
                    <p>{{ __("Você está logado no sistema de registro de ponto.") }}</p>
                    
                    @if(Auth::user()->role === \App\Enums\UserRole::ADMIN)
                    <div class="mt-4 bg-blue-50 p-4 rounded-lg">
                        <h3 class="font-medium text-blue-800 mb-2">Área do Administrador</h3>
                        <p class="text-sm text-blue-700 mb-3">Como administrador, você tem acesso a recursos adicionais:</p>
                        
                        <!-- Estatísticas -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                            <div class="bg-white p-4 rounded-lg border border-blue-200 text-center">
                                <div class="text-sm text-gray-500">Funcionários</div>
                                <div class="text-2xl font-bold text-blue-600">{{ $stats['total_users'] ?? 0 }}</div>
                            </div>
                            <div class="bg-white p-4 rounded-lg border border-blue-200 text-center">
                                <div class="text-sm text-gray-500">Registros Hoje</div>
                                <div class="text-2xl font-bold text-green-600">{{ $stats['total_entries_today'] ?? 0 }}</div>
                            </div>
                            <div class="bg-white p-4 rounded-lg border border-blue-200 text-center">
                                <div class="text-sm text-gray-500">Registros no Mês</div>
                                <div class="text-2xl font-bold text-purple-600">{{ $stats['total_entries_month'] ?? 0 }}</div>
                            </div>
                        </div>
                        
                        <!-- Links rápidos -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            <a href="{{ route('users.index') }}" class="bg-white hover:bg-gray-50 p-3 rounded border border-blue-200 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                Gerenciar Funcionários
                            </a>
                            <a href="{{ route('time-entries.report') }}" class="bg-white hover:bg-gray-50 p-3 rounded border border-blue-200 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Relatórios de Ponto
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
