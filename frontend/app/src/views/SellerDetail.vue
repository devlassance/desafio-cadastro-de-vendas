<template>
  <section>
    <div v-if="isLoading">Carregando...</div>
    <div v-else-if="error">{{ error }}</div>
    <div v-else>
      <h2>{{ seller?.name ?? '-' }}</h2>

      <div class="actions">
        <button :disabled="isSending" @click="resendEmail">Reenviar email</button>
        <span v-if="sendMessage" :style="{ color: sendOk ? 'green' : 'red', marginLeft: '8px' }">{{ sendMessage }}</span>
      </div>

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
import { getSeller, resendSellerDailyEmail } from '../services/seller';



const route =  useRoute();
const seller = ref<SellerDetail | null>(null);
const sellerId = Number(route.params.id);

const isLoading = ref(true);
const error = ref<string | null>(null);
const isSending = ref(false)
const sendMessage = ref('')
const sendOk = ref<boolean | null>(null)

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

async function resendEmail() {
  try {
    isSending.value = true
    sendMessage.value = ''
    sendOk.value = null
    const ok = await resendSellerDailyEmail(sellerId)
    if (ok) {
      sendOk.value = true
      sendMessage.value = 'Email enviado com sucesso.'
    } else {
      sendOk.value = false
      sendMessage.value = 'Falha ao enviar email.'
    }
  } catch (e) {
    sendOk.value = false
    sendMessage.value = 'Falha ao enviar email.'
  } finally {
    isSending.value = false
  }
}

</script>

<style scoped>
.actions { margin: .5rem 0; display: flex; align-items: center; gap: .5rem; }
.table { width: 100%; border-collapse: collapse; }
th, td { border-bottom: 1px solid #3333; padding: .5rem; text-align: left; }
thead th { background: #f6f6f6; color: #000 }
</style>


