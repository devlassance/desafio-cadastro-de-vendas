#!/usr/bin/env bash
set -euo pipefail

APP_DIR=/usr/src/app

if [ ! -f "$APP_DIR/package.json" ]; then
  echo "No Vue project found. Scaffolding a new Vue 3 + Vite app..."
  mkdir -p "$APP_DIR"
  cd "$APP_DIR"
  # Use npm create with defaults; name project 'frontend'
  npm create vite@latest . -- --template vue
  npm install
fi

cd "$APP_DIR"
# Ensure vite binds to 0.0.0.0
if ! grep -q "server:" vite.config.* 2>/dev/null; then
  # create vite config override if needed
  cat > vite.config.js <<'EOF'
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
  plugins: [vue()],
  server: {
    host: true,
    port: 5173,
    strictPort: true
  }
})
EOF
  npm install -D @vitejs/plugin-vue
fi

exec npm run dev -- --host 0.0.0.0 --port 5173
