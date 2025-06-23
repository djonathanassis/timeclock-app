# Sistema de Registro de Ponto Eletrônico

Sistema de registro de ponto eletrônico desenvolvido com Laravel 12, permitindo que funcionários registrem seus pontos e administradores gerenciem usuários e visualizem relatórios.

## Requisitos do Sistema

- Docker e Docker Compose
- Git

## Tecnologias Utilizadas

- PHP 8.3 com Laravel 12
- MySQL 8.0
- Laravel Sail (ambiente Docker)
- Blade para templates

## Funcionalidades

### Perfil Funcionário
- Login com autenticação
- Registro de ponto
- Visualização do histórico de pontos registrados
- Troca de senha

### Perfil Administrador
- CRUD completo de funcionários
- Visualização de pontos registrados por qualquer funcionário
- Relatório de pontos com filtro por período
- Gestão de funcionários subordinados

## Instalação com Laravel Sail

1. Clone o repositório:
```bash
git clone https://github.com/seu-usuario/timeclock-app.git
cd timeclock-app
```

2. Configure o arquivo de ambiente:
```bash
cp .env.example .env
```

3. Inicie o ambiente Docker com Laravel Sail:
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```

4. Inicie os containers:
```bash
./vendor/bin/sail up -d
```

5. Gere a chave da aplicação:
```bash
./vendor/bin/sail artisan key:generate
```

6. Execute as migrações e seeders:
```bash
./vendor/bin/sail artisan migrate --seed
```

## Acesso ao Sistema

Após a instalação, o sistema estará disponível em: http://localhost

### Credenciais padrão:

**Administrador:**
- Email: admin@exemplo.com
- Senha: password

**Funcionário:**
- Email: funcionario@exemplo.com
- Senha: password

## Executando Testes

Para executar os testes automatizados:

```bash
./vendor/bin/sail artisan test
```

## Estrutura do Projeto

- `app/Models`: Modelos do Eloquent
- `app/Http/Controllers`: Controladores da aplicação
- `app/Http/Requests`: Classes de validação de formulários
- `app/DTOs`: Objetos de transferência de dados
- `app/Rules`: Regras de validação personalizadas
- `app/Services`: Serviços da aplicação
- `app/Enums`: Enumerações para cargos e papéis
- `app/Notifications`: Notificações do sistema
- `app/Listeners`: Listeners para eventos
- `database/migrations`: Migrações do banco de dados
- `resources/views`: Templates Blade
- `tests`: Testes automatizados

## Funcionalidades Especiais

### Validação de CPF
O sistema valida automaticamente os CPFs inseridos, garantindo que sejam válidos e únicos no banco de dados.

### Consulta de CEP
Integração com a API ViaCEP para preenchimento automático de endereços a partir do CEP informado.

### Relatório de Pontos
Implementação de relatório utilizando SQL puro para listar os registros de ponto com informações detalhadas dos funcionários e seus gestores.

### Notificações
O sistema notifica gestores quando seus funcionários registram pontos.

## Decisões Técnicas

- Utilização de DTOs para transferência de dados entre camadas
- Implementação de middlewares para controle de acesso baseado em papéis
- Uso de soft deletes para exclusão lógica de registros
- Testes automatizados para garantir o funcionamento correto das funcionalidades

## Comandos Úteis do Laravel Sail

```bash
# Iniciar os containers
./vendor/bin/sail up -d

# Parar os containers
./vendor/bin/sail down

# Executar comandos Artisan
./vendor/bin/sail artisan [comando]

# Executar comandos Composer
./vendor/bin/sail composer [comando]

# Executar comandos NPM
./vendor/bin/sail npm [comando]

# Executar testes
./vendor/bin/sail test
```
