# Cadastro de Vendas — Projeto (Laravel + Vue + MySQL) — Dockerized

Este repositório contém um projeto de exemplo para um desafio técnico: um backend em Laravel (PHP-FPM), frontend em Vue 3 (Vite) e banco MySQL. Todos os serviços rodam via Docker Compose e a ideia é que um avaliador consiga rodar a aplicação com um único comando.

Status: pronto para uso em desenvolvimento. Executar:

```bash
# Cria as imagens e sobe todos os serviços em background
docker compose up -d --build
```

Isso deve deixar os serviços disponíveis em:
- Backend (Laravel + Nginx): http://localhost:8080
- Frontend (Vite dev server): http://localhost:5173
- MySQL: 3306 (usuário root / senha root)
- Redis: 6379 (se usado)

Obs: na primeira execução o container do backend irá gerar um projeto Laravel automaticamente (se não houver código no `backend/src`), rodar `composer install`, gerar APP_KEY e executar as migrations. O MySQL possui um script de inicialização que cria a base `sales` se necessário.

## Estrutura do repositório (relevante)

- `docker-compose.yml` — orquestra os serviços (backend, nginx, frontend, db, redis)
- `nginx/nginx.conf` — configuração do Nginx para servir o Laravel
- `backend/`
  - `Dockerfile` — imagem PHP-FPM (composer + extensões)
  - `start.sh` — script de inicialização do container backend (espera DB, cria DB, composer install, migrations, php-fpm)
  - `src/` — código Laravel montado via volume (local)
    - `.env` — variáveis de ambiente do Laravel (por padrão aponta para o MySQL do compose)
- `frontend/`
  - `Dockerfile` — imagem Node para Vue/Vite
  - `app/` — diretório montado para o app Vue (criado automaticamente se vazio)
- `mysql/init/` — scripts SQL executados na primeira inicialização do MySQL (cria DB `sales`)

## Comportamento esperado no primeiro boot
1. `docker compose up -d --build`
2. O container `db` (MySQL) inicia e, se o volume estiver limpo, roda `mysql/init/01-create-db.sql` criando a base `sales`.
3. O container `backend` aguarda o DB ficar saudável, executa `composer install` (se `vendor/` não existir), gera `APP_KEY`, cria as migrations de sessions/cache/queue (se necessário) e executa `php artisan migrate --force`.
4. O `nginx` serve o `public/` do Laravel e direciona PHP para o container `backend:9000`.
5. O `frontend` executa o servidor Vite em `:5173`.

## Variáveis de ambiente importantes (veja `backend/src/.env`)
- `DB_CONNECTION`, `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- `SESSION_DRIVER` (padrão: database) — o entrypoint cria a tabela caso necessário
- `CACHE_STORE` (padrão: database)
- `QUEUE_CONNECTION` (padrão: database)

> Para desenvolvimento rápido você pode usar `SESSION_DRIVER=file`, `CACHE_STORE=file` e `QUEUE_CONNECTION=sync` se quiser evitar dependências de tabelas imediatamente.

## Troubleshooting rápido
- Se receber `502 Bad Gateway` em `http://localhost:8080`:
  - Verifique se o backend está pronto: `docker compose logs -f backend`
  - Aguardem as mensagens: `fpm is running, ready to handle connections` e `Running migrations`.
  - Confirme que o `db` está `healthy`: `docker compose ps` e `docker compose logs -f db`.

- Se migrations falharem por permissões em `storage`:
  - Ajuste permissões localmente: `sudo chown -R $UID:$UID backend/src/storage backend/src/bootstrap/cache`
  - Ou dentro do container: `docker compose exec backend bash -lc 'chown -R www-data:www-data storage bootstrap/cache || true'`

- Caso o banco `sales` não exista (instalação limpa):
  - O MySQL init cria automaticamente se o volume estiver limpo; caso contrário o `start.sh` do backend garante `CREATE DATABASE IF NOT EXISTS sales`.

## Comandos úteis
- Subir em background: `docker compose up -d --build`
- Ver logs do backend: `docker compose logs -f backend`
- Ver logs do db: `docker compose logs -f db`
- Entrar no container backend: `docker compose exec backend bash` (ou sh)
- Rodar artisan manualmente: `docker compose exec backend bash -lc 'php artisan migrate --force'`

## Limpar e iniciar do zero
1. Parar e remover containers/volumes locais:
```bash
docker compose down -v
```
2. Subir novamente:
```bash
docker compose up -d --build
```

## Notas finais
- Este setup é pensado para facilitar a avaliação do desafio técnico: a pessoa avaliadora só precisa do Docker e de uma porta livre (8080 e 5173) e executar `docker compose up -d --build`..
