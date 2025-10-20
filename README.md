# Cadastro de Vendas — Laravel + Vue + MySQL (Docker)

Importante: não basta rodar apenas “docker compose up -d --build”. Após o build, existem passos dentro dos containers para concluir a configuração.

## Stack e serviços
- Backend: Laravel (PHP-FPM) + Nginx
- Frontend: Vue 3 + Vite (TypeScript)
- Banco: MySQL 8
- Redis: opcional (cache/fila)

Portas principais
- Backend (Nginx + Laravel): http://localhost:8080
- Frontend (Vite dev server): http://localhost:5173
- MySQL: 3306 (root/root)
- Redis: 6379

## 1) Subir os containers

```bash
docker compose up -d --build
```

Aguarde os serviços ficarem de pé e siga para a configuração do Backend e Frontend.

## 2) Configurar o Backend (Laravel)

Entrar no container do backend:
```bash
docker exec -it sales-backend bash
```

Dentro do container, executar os comandos iniciais do Laravel:
```bash
# 2.1) Gerar .env a partir do exemplo
cp .env.example .env

# 2.2) Instalar dependências do Laravel
composer install

# 2.3) Gerar APP_KEY
php artisan key:generate
```

Ajustes importantes no `.env` (se necessário):
- DB_HOST=db, DB_DATABASE=sales, DB_USERNAME=root, DB_PASSWORD=root (compatível com docker-compose)
- Mailer (Gmail) — ver guia abaixo

Criar usuário admin via seeder:
1) Edite `database/seeders/DatabaseSeeder.php` e altere o e-mail do usuário admin para o seu e-mail pessoal.
2) Não cadastre vendedores/vendas no seeder (facilita testar envio de e-mail aos vendedores).
3) Execute migrations + seed:
```bash
php artisan migrate:fresh --seed
```

### Mailer com Gmail
O `.env.example` já está quase pronto para SMTP do Gmail. Gere uma Senha de App na sua conta Google (2FA habilitado) e coloque em `MAIL_PASSWORD`.

Valores típicos:
- `MAIL_MAILER=smtp`
- `MAIL_HOST=smtp.gmail.com`
- `MAIL_PORT=587`
- `MAIL_ENCRYPTION=tls`
- `MAIL_USERNAME=seu_email@gmail.com`
- `MAIL_PASSWORD=senha_de_app`
- `MAIL_FROM_ADDRESS=seu_email@gmail.com`
- `MAIL_FROM_NAME="Cadastro de Vendas"`

## 3) Configurar o Frontend (Vue 3 + Vite)

Entrar no container do frontend:
```bash
docker exec -it sales-frontend bash
```

Instalar dependências:
```bash
npm install
```

O Vite dev server roda no container (porta 5173). Acesse:
- Frontend: http://localhost:5173
- Backend (API via Nginx): http://localhost:8080

## 4) Comandos úteis

- Subir/atualizar serviços:
```bash
docker compose up -d --build
```

- Entrar no backend e rodar Artisan:
```bash
docker exec -it sales-backend bash
php artisan migrate:fresh --seed
```

- Entrar no frontend:
```bash
docker exec -it sales-frontend bash
```

- Reset geral (remove volumes):
```bash
docker compose down -v && docker compose up -d --build
```

## 5) Checklist do desafio — status

Funcionalidades
- [x] Cadastrar vendedores informando nome e e-mail
- [x] Cadastrar vendas, informando o vendedor, o valor e a data da venda
- [x] Listar todos os vendedores
- [x] Listar todas as vendas
- [x] Listar todas as vendas por vendedor

Capacidades da aplicação
- [x] Interagir com todos os endpoints da API
- [x] Enviar um e-mail para o vendedor ao final de cada dia com a quantidade de vendas do dia, o valor total e o total das comissões
- [x] Enviar um e-mail para o administrador com a soma de todas as vendas do dia
- [x] Permitir que o administrador reenvie o e-mail de comissão a um vendedor
- [ ] Implementar autenticação na API
- [x] Implementar testes (unitários, integração)
- [x] Implementar validação dos dados enviados
- [x] Implementar uso de cache e fila
- [x] Implementar uso de TypeScript e features modernas (PHP e JS)

Observação: a parte de autenticação será detalhada por e-mail (mais informações e contexto).
