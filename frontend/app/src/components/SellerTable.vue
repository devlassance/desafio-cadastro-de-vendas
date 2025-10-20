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
      <tr
        v-for="s in sellers"
        :key="s.id"
        class="clickable-row"
        tabindex="0"
        @click="goto(s.id)"
        @keydown.enter.prevent="goto(s.id)"
        @keydown.space.prevent="goto(s.id)"
      >
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
import { useRouter } from 'vue-router'

defineProps<{ sellers: Seller[] }>()

function formatDate(iso?: string) {
  if (!iso) return '-'
  const d = new Date(iso)
  return d.toLocaleDateString()
}

const router = useRouter()
function goto(id: number) {
  router.push(`/sellers/${id}`)
}
</script>

<style scoped>
.table { width: 100%; border-collapse: collapse; }
th, td { border-bottom: 1px solid #3333; padding: .5rem; text-align: left; }
thead th { background: #f6f6f6; color: #000 }
tbody tr.clickable-row { cursor: pointer; transition: background .15s ease; }
tbody tr.clickable-row:hover, tbody tr.clickable-row:focus { background: #3f3f3f; }
</style>

