<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Novo Funcionário') }}
            </h2>
            <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('Voltar') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('users.store') }}" x-data="employeeForm()">
                        @csrf

                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b">Informações Pessoais</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Nome -->
                                <div>
                                    <x-input-label for="name" :value="__('Nome Completo')" />
                                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>

                                <!-- CPF -->
                                <div>
                                    <x-input-label for="cpf" :value="__('CPF')" />
                                    <x-text-input id="cpf" class="block mt-1 w-full" type="text" name="cpf" :value="old('cpf')" required x-mask="999.999.999-99" x-on:input="formatCpf" />
                                    <x-input-error :messages="$errors->get('cpf')" class="mt-2" />
                                </div>

                                <!-- Email -->
                                <div>
                                    <x-input-label for="email" :value="__('Email')" />
                                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>

                                <!-- Cargo -->
                                <div>
                                    <x-input-label for="job_position" :value="__('Cargo')" />
                                    <x-text-input id="job_position" class="block mt-1 w-full" type="text" name="job_position" :value="old('job_position')" required />
                                    <x-input-error :messages="$errors->get('job_position')" class="mt-2" />
                                </div>

                                <!-- Data de Nascimento -->
                                <div>
                                    <x-input-label for="birth_date" :value="__('Data de Nascimento')" />
                                    <x-text-input id="birth_date" class="block mt-1 w-full" type="date" name="birth_date" :value="old('birth_date')" required max="{{ date('Y-m-d') }}" />
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
                                        <x-text-input id="zip_code" class="block mt-1 w-full" type="text" name="zip_code" :value="old('zip_code')" required x-mask="99999-999" x-on:blur="searchCep" />
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
                                    <x-text-input id="street" class="block mt-1 w-full" type="text" name="street" :value="old('street')" required x-model="street" />
                                    <x-input-error :messages="$errors->get('street')" class="mt-2" />
                                </div>

                                <!-- Número -->
                                <div>
                                    <x-input-label for="number" :value="__('Número')" />
                                    <x-text-input id="number" class="block mt-1 w-full" type="text" name="number" :value="old('number')" />
                                    <x-input-error :messages="$errors->get('number')" class="mt-2" />
                                </div>

                                <!-- Complemento -->
                                <div>
                                    <x-input-label for="complement" :value="__('Complemento')" />
                                    <x-text-input id="complement" class="block mt-1 w-full" type="text" name="complement" :value="old('complement')" />
                                    <x-input-error :messages="$errors->get('complement')" class="mt-2" />
                                </div>

                                <!-- Bairro -->
                                <div>
                                    <x-input-label for="neighborhood" :value="__('Bairro')" />
                                    <x-text-input id="neighborhood" class="block mt-1 w-full" type="text" name="neighborhood" :value="old('neighborhood')" required x-model="neighborhood" />
                                    <x-input-error :messages="$errors->get('neighborhood')" class="mt-2" />
                                </div>

                                <!-- Cidade -->
                                <div>
                                    <x-input-label for="city" :value="__('Cidade')" />
                                    <x-text-input id="city" class="block mt-1 w-full" type="text" name="city" :value="old('city')" required x-model="city" />
                                    <x-input-error :messages="$errors->get('city')" class="mt-2" />
                                </div>

                                <!-- Estado -->
                                <div>
                                    <x-input-label for="state" :value="__('Estado')" />
                                    <select id="state" name="state" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required x-model="state">
                                        <option value="">Selecione um estado</option>
                                        <option value="AC">Acre</option>
                                        <option value="AL">Alagoas</option>
                                        <option value="AP">Amapá</option>
                                        <option value="AM">Amazonas</option>
                                        <option value="BA">Bahia</option>
                                        <option value="CE">Ceará</option>
                                        <option value="DF">Distrito Federal</option>
                                        <option value="ES">Espírito Santo</option>
                                        <option value="GO">Goiás</option>
                                        <option value="MA">Maranhão</option>
                                        <option value="MT">Mato Grosso</option>
                                        <option value="MS">Mato Grosso do Sul</option>
                                        <option value="MG">Minas Gerais</option>
                                        <option value="PA">Pará</option>
                                        <option value="PB">Paraíba</option>
                                        <option value="PR">Paraná</option>
                                        <option value="PE">Pernambuco</option>
                                        <option value="PI">Piauí</option>
                                        <option value="RJ">Rio de Janeiro</option>
                                        <option value="RN">Rio Grande do Norte</option>
                                        <option value="RS">Rio Grande do Sul</option>
                                        <option value="RO">Rondônia</option>
                                        <option value="RR">Roraima</option>
                                        <option value="SC">Santa Catarina</option>
                                        <option value="SP">São Paulo</option>
                                        <option value="SE">Sergipe</option>
                                        <option value="TO">Tocantins</option>
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
                                    <x-input-label for="password" :value="__('Senha')" />
                                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                </div>

                                <!-- Confirmar Senha -->
                                <div>
                                    <x-input-label for="password_confirmation" :value="__('Confirmar Senha')" />
                                    <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Cadastrar') }}
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
            // Inicializa os valores do formulário com os valores antigos
            if (window.employeeForm) {
                const form = employeeForm();
                form.street = '{{ old('street') }}';
                form.neighborhood = '{{ old('neighborhood') }}';
                form.city = '{{ old('city') }}';
                form.state = '{{ old('state') }}';
            }
        });
    </script>
    @endpush
</x-app-layout> 