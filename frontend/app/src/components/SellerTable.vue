<template>
  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Email</th>
        <th>Data de Criação</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="s in sellers" :key="s.id">
        <td>{{ s.id }}</td>
        <td>{{ s.name }}</td>
        <td>{{ s.email }}</td>
        <td>{{ formatDate(s.created_at) }}</td>
      </tr>
    </tbody>
  </table>
</template>

<script setup lang="ts">
import type { Seller } from '../types/seller';

defineProps<{ sellers: Seller[] }>()

function formatDate(iso?: string) {
  if (!iso) return '-'
  const d = new Date(iso)
  return d.toLocaleDateString()
}
</script>

