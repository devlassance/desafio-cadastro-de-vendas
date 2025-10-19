#!/bin/sh
set -eu

APP_DIR=/usr/src/app

if [ ! -f "$APP_DIR/package.json" ]; then
  echo "No Vue project found. Scaffolding a new Vue 3 + TypeScript (Vite) app..."
  mkdir -p "$APP_DIR"
  cd "$APP_DIR"
  # Create Vue + TypeScript template
  npm create vite@latest . -- --template vue-ts
  npm install

  # Common frontend deps
  npm install vue-router pinia axios
  npm install -D @vitejs/plugin-vue @types/node
fi

cd "$APP_DIR"

# Ensure Vite config exists and is TypeScript with proxy to backend nginx
if [ ! -f vite.config.ts ]; then
  cat > vite.config.ts <<'EOF'
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import path from 'node:path'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [vue()],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'src'),
    },
  },
  server: {
    host: true,
    port: 5173,
    strictPort: true,
    // Proxy API requests from the dev server (frontend container) to nginx service
    proxy: {
      '/api': {
        target: 'http://nginx',
        changeOrigin: true,
      },
    },
  },
})
EOF
fi

# Basic API helper (idempotent)
mkdir -p src/services src/types
if [ ! -f src/services/api.ts ]; then
  cat > src/services/api.ts <<'EOF'
import axios from 'axios'

// During development, Vite proxy will forward /api to the nginx container.
// In production, set baseURL via env (e.g., import.meta.env.VITE_API_BASE_URL)
export const api = axios.create({
  baseURL: '/api',
})

export default api
EOF
fi

if [ ! -f src/types/models.ts ]; then
  cat > src/types/models.ts <<'EOF'
export interface Seller {
  id: number
  name: string
  email: string
  created_at?: string
  updated_at?: string
}

export interface Sale {
  id: number
  seller_id: number
  value: number
  created_at?: string
  updated_at?: string
}

export interface Paginated<T> {
  data: T[]
  current_page: number
  last_page: number
  per_page: number
  total: number
}
EOF
fi

# Ensure index.html exists (vite template creates it, but create if missing)
if [ ! -f index.html ]; then
  cat > index.html <<'EOF'
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sales Frontend</title>
  </head>
  <body>
    <div id="app"></div>
    <script type="module" src="/src/main.ts"></script>
  </body>
  </html>
EOF
fi

# Ensure package.json has dev script
if ! grep -q '"dev"' package.json; then
  node -e '
const fs=require("fs");
const p=JSON.parse(fs.readFileSync("package.json","utf8"));
p.scripts=p.scripts||{};p.scripts.dev=p.scripts.dev||"vite";
fs.writeFileSync("package.json",JSON.stringify(p,null,2));
'
fi

exec npm run dev -- --host 0.0.0.0 --port 5173
