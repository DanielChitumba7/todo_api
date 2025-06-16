# 📋 API RESTful de Gerenciamento de Tarefas (To-Do List)

Esta é uma API desenvolvida em **Laravel** com foco em gerenciamento de tarefas (To-Do List). Cada usuário pode se autenticar, criar, listar, editar, filtrar e deletar suas tarefas.

## ⚙️ Tecnologias e Padrões Utilizados

- **Laravel 12**
- **Sanctum** para autenticação via token
- **Padrão Repository Service**
- **Swagger (OpenAPI)** para documentação da API
- **Validações customizadas com Form Requests**
- **Pest** para testes automatizados

---

## 🚀 Endpoints

### 🧑‍💻 Autenticação

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| POST | `/user/register` | Registra um novo usuário |
| POST | `/user/login` | Realiza login e retorna token |
| POST | `/user/logout` | Realiza logout do usuário autenticado |
| GET  | `/check-auth` | Verifica se o usuário está autenticado |

### ✅ Tarefas

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| GET | `/tasks` | Lista todas as tarefas do usuário autenticado |
| GET | `/tasks/{id}` | Mostra uma tarefa específica |
| POST | `/tasks` | Cria uma nova tarefa |
| PUT | `/tasks/{id}` | Atualiza uma tarefa existente |
| DELETE | `/tasks/{id}` | Exclui uma tarefa existente |
| GET | `/tasks/status/{status}` | Filtra tarefas por status (`pending`, `completed`) |

---

## 🛠️ Configuração e Execução do Projeto

Siga os passos abaixo para configurar e executar o projeto localmente:

### 1. Clonar o Repositório

```bash
git clone <https://github.com/DanielChitumba7/todo_api.git>
cd <todo_api>
```

### 2. Instalar Dependências

Instale as dependências do Composer:

```bash
composer install
```

### 3. Configurar o Ambiente

Crie o ficheiro de ambiente a partir do exemplo:

```bash
cp .env.example .env
```

Edite o ficheiro `.env` com as suas configurações de base de dados e outras variáveis de ambiente. Certifique-se de que a secção da base de dados está configurada corretamente (ex: `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).

### 4. Gerar a Chave da Aplicação

```bash
php artisan key:generate
```

### 5. Rodar as Migrações

Execute as migrações da base de dados para criar as tabelas necessárias:

```bash
php artisan migrate
```

### 6. Executar o Servidor Local

Inicie o servidor de desenvolvimento do Laravel:

```bash
php artisan serve
```

O servidor estará disponível em `http://127.0.0.1:8000` (ou outra porta, se especificado).

---

## 🧪 Testes Automatizados

Este projeto utiliza **Pest** para testes automatizados. Para executar os testes, utilize o seguinte comando:

```bash
./vendor/bin/pest
```

Para mais detalhes sobre como escrever e executar testes com Pest, consulte a documentação oficial do Pest.

---

## 🌐 Documentação da API (Swagger/OpenAPI)

Esta API é documentada utilizando **Swagger (OpenAPI)**, o que permite uma fácil exploração e teste dos endpoints. Para aceder à documentação interativa:

### 1. Instalar Dependências (se ainda não o fez)

Certifique-se de que tem as dependências necessárias para o Swagger instaladas. Normalmente, utiliza-se o `darkaonline/l5-swagger` para Laravel. Se não tiver, adicione via Composer:

```bash
composer require darkaonline/l5-swagger
php artisan vendor:publish --provider="L5Swagger\L5SwaggerServiceProvider" --tag="l5-swagger-config"
php artisan swagger-l5-gen:generate
```

### 2. Aceder à Documentação

Após iniciar o servidor local (`php artisan serve`), a documentação Swagger estará disponível no seguinte URL:

```
http://127.0.0.1:8000/api/docs
```

Nesta interface, poderá:

- **Visualizar todos os endpoints** da API, incluindo métodos HTTP, URLs e descrições.
- **Verificar os parâmetros** necessários para cada requisição (caminho, query, corpo da requisição).
- **Consultar exemplos de requisições e respostas** para cada endpoint.
- **Entender os códigos de status HTTP** retornados pela API.
- **Testar os endpoints diretamente** a partir da interface, enviando requisições e visualizando as respostas em tempo real.

---

## 🚀 Como Testar a API com Postman

Para testar esta API de forma eficiente, recomendamos o uso do **Postman**, uma ferramenta popular para desenvolvimento e teste de APIs. Siga os passos abaixo para configurar e testar os endpoints.

### 1. Instalar o Postman

Se ainda não tem o Postman, pode fazer o download e instalá-lo a partir do site oficial: [https://www.postman.com/downloads/](https://www.postman.com/downloads/)


### 3. Configurar o Ambiente no Postman

É útil configurar um ambiente no Postman para gerir a URL base da sua API e tokens de autenticação:

1. No canto superior direito do Postman, clique no ícone de `Environment Quick Look` (o olho).
2. Clique em `Add` para criar um novo ambiente.
3. Dê um nome ao ambiente (ex: `Laravel To-Do API Local`).
4. Adicione uma variável `base_url` com o valor `http://127.0.0.1:8000/api` (ou a URL onde o seu servidor Laravel está a correr).
5. Adicione uma variável `auth_token` (deixe-a vazia por enquanto, será preenchida após o login).
6. Selecione este ambiente no dropdown de ambientes.

### 4. Testar os Endpoints

#### 4.1. Autenticação

##### Registrar um Novo Usuário (`POST /user/register`)

- **Método:** `POST`
- **URL:** `{{base_url}}/user/register`
- **Headers:** `Content-Type: application/json`
- **Body (raw, JSON):**
  ```json
  {
    "name": "Seu Nome",
    "email": "seu.email@example.com",
    "password": "sua_senha",
    "password_confirmation": "sua_senha"
  }
  ```
- **Ação:** Envie a requisição. Você deve receber um `201 Created` com os dados do usuário e um `token`.

##### Realizar Login (`POST /user/login`)

- **Método:** `POST`
- **URL:** `{{base_url}}/user/login`
- **Headers:** `Content-Type: application/json`
- **Body (raw, JSON):**
  ```json
  {
    "email": "seu.email@example.com",
    "password": "sua_senha"
  }
  ```
- **Ação:** Envie a requisição. Se o login for bem-sucedido, você receberá um `200 OK` com os dados do usuário e um `token`. **Copie este token** e guarde-o na variável de ambiente `auth_token` do Postman.

#### 4.2. Tarefas (Requer Autenticação)

Para todas as requisições de tarefas, adicione o seguinte header de autenticação:

- **Headers:** `Authorization: Bearer {{auth_token}}`

##### Listar Tarefas (`GET /tasks`)

- **Método:** `GET`
- **URL:** `{{base_url}}/tasks`
- **Ação:** Envie a requisição. Você deve receber um `200 OK` com uma lista de tarefas.

##### Criar uma Nova Tarefa (`POST /tasks`)

- **Método:** `POST`
- **URL:** `{{base_url}}/tasks`
- **Headers:** `Content-Type: application/json`, `Authorization: Bearer {{auth_token}}`
- **Body (raw, JSON):**
  ```json
  {
    "title": "Minha Nova Tarefa",
    "description": "Descrição detalhada da tarefa",
    "status": "pending"
  }
  ```
- **Ação:** Envie a requisição. Você deve receber um `201 Created` com os detalhes da tarefa criada.

##### Atualizar uma Tarefa (`PUT /tasks/{id}`)

- **Método:** `PUT`
- **URL:** `{{base_url}}/tasks/<ID_DA_TAREFA>` (substitua `<ID_DA_TAREFA>` pelo ID real da tarefa)
- **Headers:** `Content-Type: application/json`, `Authorization: Bearer {{auth_token}}`
- **Body (raw, JSON):**
  ```json
  {
    "title": "Tarefa Atualizada",
    "status": "completed"
  }
  ```
- **Ação:** Envie a requisição. Você deve receber um `200 OK` com os detalhes da tarefa atualizada.

##### Deletar uma Tarefa (`DELETE /tasks/{id}`)

- **Método:** `DELETE`
- **URL:** `{{base_url}}/tasks/<ID_DA_TAREFA>` (substitua `<ID_DA_TAREFA>` pelo ID real da tarefa)
- **Headers:** `Authorization: Bearer {{auth_token}}`
- **Ação:** Envie a requisição. Você deve receber um `200 OK` com uma mensagem de sucesso.

##### Filtrar Tarefas por Status (`GET /tasks/status/{status}`)

- **Método:** `GET`
- **URL:** `{{base_url}}/tasks/status/pending` (ou `completed`, `in_progress`, etc.)
- **Headers:** `Authorization: Bearer {{auth_token}}`
- **Ação:** Envie a requisição. Você deve receber um `200 OK` com uma lista de tarefas filtradas.

---


