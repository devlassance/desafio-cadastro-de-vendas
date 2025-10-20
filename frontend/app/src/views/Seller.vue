<template>
  <section>
    <h2>Seller</h2>
    <div class="actions">
      <button :disabled="loading" @click="() => fetchPage(page)">Reload</button>
    </div>
    <p v-if="error" style="color:red">{{ error }}</p>
    <SellerTable :sellers="sellers" />

    <PaginationControls
      :page="page"
      :loading="loading"
      :hasPrev="page > 1"
      :hasNext="!endReached"
      @prev="prev"
      @next="next"
    />

    <h3 style="margin-top:1rem">Create Seller</h3>
    <form @submit.prevent="submit">
      <div class="row">
        <label>Seller ID
          <input v-model.number="form.seller_id" type="number" min="1" required />
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
import SellerTable from '../components/SellerTable.vue'
import PaginationControls from '../components/PaginationControls.vue'
import { listSellers } from '../services/seller'
import type { Seller } from '../types/seller'


const loading = ref(false)
const error = ref('')
const page = ref(1)
const sellers = ref<Seller[]>([])
const endReached = ref(false)

const form = reactive({
  name: undefined as string | undefined,
  email: undefined as string | undefined,
  date: new Date().toISOString().slice(0, 10),
})

onMounted(() => {
  fetchPage(1)
})

// async function submit() {
//   try {
//     loading.value = true
//     error.value = ''
//     if (!form.seller_id || form.amount === undefined || !form.date) return
//     await createSale({
//       seller_id: form.seller_id,
//       amount: form.amount,
//       sale_date: form.date,
//     })
//     form.amount = undefined
//     await fetchPage(page.value)
//   } catch (e: any) {
//     error.value = e?.response?.data?.message || e?.message || 'Failed to create sale'
//   } finally {
//     loading.value = false
//   }
// }

async function fetchPage(p: number) {
  try {
    loading.value = true
    error.value = ''
    const data = await listSellers(p)
    sellers.value = data
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

</script>

<style scoped>
.actions { margin-bottom: .5rem; }
.row { display: flex; gap: 1rem; margin: .5rem 0; }
label { display: flex; flex-direction: column; font-size: .9rem; }
input { padding: .4rem .5rem; }


</style>
