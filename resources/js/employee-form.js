/**
 * Função para gerenciar o formulário de funcionários com Alpine.js
 */
window.employeeForm = function() {
    return {
        street: '',
        neighborhood: '',
        city: '',
        state: '',
        
        /**
         * Formata o CPF removendo caracteres não numéricos
         */
        formatCpf() {
            let cpf = document.getElementById('cpf').value;
            cpf = cpf.replace(/\D/g, '');
            document.getElementById('cpf').value = cpf;
        },
        
        /**
         * Busca o endereço pelo CEP usando a API ViaCEP
         */
        async searchCep() {
            const cep = document.getElementById('zip_code').value.replace(/\D/g, '');
            
            if (cep.length !== 8) {
                return;
            }
            
            try {
                const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
                const data = await response.json();
                
                if (!data.erro) {
                    this.street = data.logradouro;
                    this.neighborhood = data.bairro;
                    this.city = data.localidade;
                    this.state = data.uf;
                }
            } catch (error) {
                console.error('Erro ao buscar CEP:', error);
            }
        }
    };
} 
 
 
 
 