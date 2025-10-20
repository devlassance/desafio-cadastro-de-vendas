<template>
  <section>
    <h2>Sales</h2>
    <div class="actions">
      <button :disabled="loading" @click="() => fetchPage(page)">Reload</button>
    </div>
    <p v-if="error" style="color:red">{{ error }}</p>
    <SalesTable :sales="sales" />

    <PaginationControls
      :page="page"
      :loading="loading"
      :hasPrev="page > 1"
      :hasNext="!endReached"
      @prev="prev"
      @next="next"
    />

    <h3 style="margin-top:1rem">Create Sale</h3>
    <form @submit.prevent="submit">
      <div class="row">
        <label>Seller
          <select v-model.number="form.seller_id" :disabled="sellersLoading" required>
            <option value="" disabled>Select a seller</option>
            <option v-for="(name, id) in sellers" :key="id" :value="id">
            {{ name }} (ID: {{ id }})
          </option>
          </select>
        </label>
        <label>Amount
          <input v-model.number="form.amount" type="number" step="0.01" min="0" required />
        </label>
        <label>Date
          <input v-model="form.date" type="date" required />
        </label>
      </div>
      <button type="submit" :disabled="loading">Create</button>
    </form>
  </section>
</template>

<script setup lang="ts">
import { onMounted, reactive, ref } from 'vue'
import SalesTable from '../components/SalesTable.vue'
import PaginationControls from '../components/PaginationControls.vue'
import { listSales, createSale } from '../services/sales'
import type { Sale } from '../types/sale'
import { listSellersForSelect } from '../services/seller'

import type { SellerSelect } from '../types/sellerSelect'

const loading = ref(false)
const error = ref('')
const page = ref(1)
const sales = ref<Sale[]>([])
const endReached = ref(false)
const sellers = ref<SellerSelect[]>([])
const sellersLoading = ref(false)

const form = reactive({
  seller_id: undefined as number | undefined,
  amount: undefined as number | undefined,
  date: new Date().toISOString().slice(0, 10),
})

onMounted(() => {
  fetchPage(1)
  fetchSellers()
})

async function submit() {
  try {
    loading.value = true
    error.value = ''
    if (!form.seller_id || form.amount === undefined || !form.date) return
    await createSale({
      seller_id: form.seller_id,
      amount: form.amount,
      sale_date: form.date,
    })
    form.amount = undefined
    await fetchPage(page.value)
  } catch (e: any) {
    error.value = e?.response?.data?.message || e?.message || 'Failed to create sale'
  } finally {
    loading.value = false
  }
}

async function fetchPage(p: number) {
  try {
    loading.value = true
    error.value = ''
    const data = await listSales(p)
    sales.value = data
    endReached.value = data.length === 0
    page.value = p
  } catch (e: any) {
    error.value = e?.response?.data?.message || e?.message || 'Failed to load sales'
  } finally {
    loading.value = false
  }
}

function next() {
  if (!endReached.value) fetchPage(page.value + 1)
}

function prev() {
  if (page.value > 1) fetchPage(page.value - 1)
}

async function fetchSellers() {
  try {
    sellersLoading.value = true
    const data = await listSellersForSelect()
    sellers.value = data
  } catch (e) {
    // 
  } finally {
    sellersLoading.value = false
  }
}

</script>