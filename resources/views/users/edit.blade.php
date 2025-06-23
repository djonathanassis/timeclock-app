<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Editar Funcionário') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('users.show', $employee) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    {{ __('Visualizar') }}
                </a>
                <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    {{ __('Voltar') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('users.update', $employee) }}" x-data="employeeForm()">
                        @csrf
                        @method('PUT')

                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b">Informações Pessoais</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Nome -->
                                <div>
                                    <x-input-label for="name" :value="__('Nome Completo')" />
                                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $employee->name)" required autofocus />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>

                                <!-- CPF -->
                                <div>
                                    <x-input-label for="cpf" :value="__('CPF')" />
                                    <x-text-input id="cpf" class="block mt-1 w-full" type="text" name="cpf" :value="old('cpf', $employee->cpf)" required x-mask="999.999.999-99" x-on:input="formatCpf" />
                                    <x-input-error :messages="$errors->get('cpf')" class="mt-2" />
                                </div>

                                <!-- Email -->
                                <div>
                                    <x-input-label for="email" :value="__('Email')" />
                                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $employee->email)" required />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>

                                <!-- Cargo -->
                                <div>
                                    <x-input-label for="job_position" :value="__('Cargo')" />
                                    <select id="job_position" name="job_position" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>
                                        <option value="">Selecione um cargo</option>
                                        <option value="developer" {{ old('job_position', $employee->job_position->value) == 'developer' ? 'selected' : '' }}>Desenvolvedor</option>
                                        <option value="manager" {{ old('job_position', $employee->job_position->value) == 'manager' ? 'selected' : '' }}>Gerente</option>
                                        <option value="analyst" {{ old('job_position', $employee->job_position->value) == 'analyst' ? 'selected' : '' }}>Analista</option>
                                        <option value="other" {{ old('job_position', $employee->job_position->value) == 'other' ? 'selected' : '' }}>Outro</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('job_position')" class="mt-2" />
                                </div>

                                <!-- Data de Nascimento -->
                                <div>
                                    <x-input-label for="birth_date" :value="__('Data de Nascimento')" />
                                    <x-text-input id="birth_date" class="block mt-1 w-full" type="date" name="birth_date" :value="old('birth_date', $employee->birth_date->format('Y-m-d'))" required max="{{ date('Y-m-d') }}" />
                                    <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b">Endereço</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- CEP -->
                                <div>
                                    <x-input-label for="zip_code" :value="__('CEP')" />
                                    <div class="flex">
                                        <x-text-input id="zip_code" class="block mt-1 w-full" type="text" name="zip_code" :value="old('zip_code', $employee->zip_code)" required x-mask="99999-999" x-on:blur="searchCep" />
                                        <button type="button" @click="searchCep" class="mt-1 ml-2 px-3 py-2 bg-gray-200 rounded-md hover:bg-gray-300 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </button>
                                    </div>
                                    <x-input-error :messages="$errors->get('zip_code')" class="mt-2" />
                                </div>

                                <!-- Logradouro -->
                                <div>
                                    <x-input-label for="street" :value="__('Logradouro')" />
                                    <x-text-input id="street" class="block mt-1 w-full" type="text" name="street" :value="old('street', $employee->street)" required x-model="street" />
                                    <x-input-error :messages="$errors->get('street')" class="mt-2" />
                                </div>

                                <!-- Número -->
                                <div>
                                    <x-input-label for="number" :value="__('Número')" />
                                    <x-text-input id="number" class="block mt-1 w-full" type="text" name="number" :value="old('number', $employee->number)" />
                                    <x-input-error :messages="$errors->get('number')" class="mt-2" />
                                </div>

                                <!-- Complemento -->
                                <div>
                                    <x-input-label for="complement" :value="__('Complemento')" />
                                    <x-text-input id="complement" class="block mt-1 w-full" type="text" name="complement" :value="old('complement', $employee->complement)" />
                                    <x-input-error :messages="$errors->get('complement')" class="mt-2" />
                                </div>

                                <!-- Bairro -->
                                <div>
                                    <x-input-label for="neighborhood" :value="__('Bairro')" />
                                    <x-text-input id="neighborhood" class="block mt-1 w-full" type="text" name="neighborhood" :value="old('neighborhood', $employee->neighborhood)" required x-model="neighborhood" />
                                    <x-input-error :messages="$errors->get('neighborhood')" class="mt-2" />
                                </div>

                                <!-- Cidade -->
                                <div>
                                    <x-input-label for="city" :value="__('Cidade')" />
                                    <x-text-input id="city" class="block mt-1 w-full" type="text" name="city" :value="old('city', $employee->city)" required x-model="city" />
                                    <x-input-error :messages="$errors->get('city')" class="mt-2" />
                                </div>

                                <!-- Estado -->
                                <div>
                                    <x-input-label for="state" :value="__('Estado')" />
                                    <select id="state" name="state" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required x-model="state">
                                        <option value="">Selecione um estado</option>
                                        <option value="AC" {{ old('state', $employee->state) == 'AC' ? 'selected' : '' }}>Acre</option>
                                        <option value="AL" {{ old('state', $employee->state) == 'AL' ? 'selected' : '' }}>Alagoas</option>
                                        <option value="AP" {{ old('state', $employee->state) == 'AP' ? 'selected' : '' }}>Amapá</option>
                                        <option value="AM" {{ old('state', $employee->state) == 'AM' ? 'selected' : '' }}>Amazonas</option>
                                        <option value="BA" {{ old('state', $employee->state) == 'BA' ? 'selected' : '' }}>Bahia</option>
                                        <option value="CE" {{ old('state', $employee->state) == 'CE' ? 'selected' : '' }}>Ceará</option>
                                        <option value="DF" {{ old('state', $employee->state) == 'DF' ? 'selected' : '' }}>Distrito Federal</option>
                                        <option value="ES" {{ old('state', $employee->state) == 'ES' ? 'selected' : '' }}>Espírito Santo</option>
                                        <option value="GO" {{ old('state', $employee->state) == 'GO' ? 'selected' : '' }}>Goiás</option>
                                        <option value="MA" {{ old('state', $employee->state) == 'MA' ? 'selected' : '' }}>Maranhão</option>
                                        <option value="MT" {{ old('state', $employee->state) == 'MT' ? 'selected' : '' }}>Mato Grosso</option>
                                        <option value="MS" {{ old('state', $employee->state) == 'MS' ? 'selected' : '' }}>Mato Grosso do Sul</option>
                                        <option value="MG" {{ old('state', $employee->state) == 'MG' ? 'selected' : '' }}>Minas Gerais</option>
                                        <option value="PA" {{ old('state', $employee->state) == 'PA' ? 'selected' : '' }}>Pará</option>
                                        <option value="PB" {{ old('state', $employee->state) == 'PB' ? 'selected' : '' }}>Paraíba</option>
                                        <option value="PR" {{ old('state', $employee->state) == 'PR' ? 'selected' : '' }}>Paraná</option>
                                        <option value="PE" {{ old('state', $employee->state) == 'PE' ? 'selected' : '' }}>Pernambuco</option>
                                        <option value="PI" {{ old('state', $employee->state) == 'PI' ? 'selected' : '' }}>Piauí</option>
                                        <option value="RJ" {{ old('state', $employee->state) == 'RJ' ? 'selected' : '' }}>Rio de Janeiro</option>
                                        <option value="RN" {{ old('state', $employee->state) == 'RN' ? 'selected' : '' }}>Rio Grande do Norte</option>
                                        <option value="RS" {{ old('state', $employee->state) == 'RS' ? 'selected' : '' }}>Rio Grande do Sul</option>
                                        <option value="RO" {{ old('state', $employee->state) == 'RO' ? 'selected' : '' }}>Rondônia</option>
                                        <option value="RR" {{ old('state', $employee->state) == 'RR' ? 'selected' : '' }}>Roraima</option>
                                        <option value="SC" {{ old('state', $employee->state) == 'SC' ? 'selected' : '' }}>Santa Catarina</option>
                                        <option value="SP" {{ old('state', $employee->state) == 'SP' ? 'selected' : '' }}>São Paulo</option>
                                        <option value="SE" {{ old('state', $employee->state) == 'SE' ? 'selected' : '' }}>Sergipe</option>
                                        <option value="TO" {{ old('state', $employee->state) == 'TO' ? 'selected' : '' }}>Tocantins</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('state')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b">Senha</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Senha -->
                                <div>
                                    <x-input-label for="password" :value="__('Nova Senha (deixe em branco para manter a atual)')" />
                                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" autocomplete="new-password" />
                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                </div>

                                <!-- Confirmar Senha -->
                                <div>
                                    <x-input-label for="password_confirmation" :value="__('Confirmar Nova Senha')" />
                                    <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" autocomplete="new-password" />
                                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Atualizar') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializa os valores do formulário com os valores existentes
            if (window.employeeForm) {
                const form = employeeForm();
                form.street = '{!! old('street', $employee->street) !!}';
                form.neighborhood = '{!! old('neighborhood', $employee->neighborhood) !!}';
                form.city = '{!! old('city', $employee->city) !!}';
                form.state = '{!! old('state', $employee->state) !!}';
            }
        });
    </script>
    @endpush
</x-app-layout> 