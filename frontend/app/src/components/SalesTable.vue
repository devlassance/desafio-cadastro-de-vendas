<template>
  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Seller</th>
        <th>Amount</th>
        <th>Commission</th>
        <th>Date</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="s in sales" :key="s.id">
        <td>{{ s.id }}</td>
        <td>{{ s.seller_id }}</td>
        <td>{{ currency(s.amount) }}</td>
        <td>{{ currency(s.commission) }}</td>
        <td>{{ formatDate(s.sale_date) }}</td>
      </tr>
    </tbody>
  </table>
</template>

<script setup lang="ts">
import type { Sale } from '../types/sale'

defineProps<{ sales: Sale[] }>()

function currency(v?: number | string) {
  if (v === undefined || v === null) return '-'
  const n = typeof v === 'string' ? Number(v) : v
  return new Intl.NumberFormat(undefined, { style: 'currency', currency: 'BRL' }).format(n)
}

function formatDate(iso?: string) {
  if (!iso) return '-'
  const d = new Date(iso)
  return d.toLocaleDateString()
}
</script>
