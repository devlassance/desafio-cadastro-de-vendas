<template>
  <section>
    <div v-if="isLoading">Carregando...</div>
    <div v-else-if="error">{{ error }}</div>
    <div v-else>
      <h2>{{ seller?.name ?? '-' }}</h2>

      <h3 style="margin-top:1rem">Vendas</h3>
      <div v-if="(seller?.sales?.length || 0) === 0">Nenhuma venda encontrada.</div>
      <table v-else class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Valor</th>
            <th>Comissão</th>
            <th>Data</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="sale in (seller?.sales || [])" :key="sale.id">
            <td>{{ sale.id }}</td>
            <td>{{ currency(sale.amount) }}</td>
            <td>{{ currency(sale.commission) }}</td>
            <td>{{ formatDate(sale.sale_date) }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { useRoute } from 'vue-router';
import type { SellerDetail } from '../types/sellerDetail';
import { getSeller } from '../services/seller';



const route =  useRoute();
const seller = ref<SellerDetail | null>(null);
const sellerId = Number(route.params.id);

const isLoading = ref(true);
const error = ref<string | null>(null);

onMounted(async () => {
  try {
    const data = await getSeller(sellerId);
    seller.value = data;
  } catch (err) {
    error.value = 'Erro ao carregar vendedor';
  } finally {
    isLoading.value = false;
  }
})

function currency(v?: number | string) {
  if (v === undefined || v === null) return '-'
  const n = typeof v === 'string' ? Number(v) : v
  try {
    return new Intl.NumberFormat(undefined, { style: 'currency', currency: 'BRL' }).format(n)
  } catch {
    return String(n)
  }
}

function formatDate(iso?: string) {
  if (!iso) return '-'
  const d = new Date(iso)
  return d.toLocaleDateString()
}

</script>


