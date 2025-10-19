<template>
  <section>
    <h2>Sales</h2>
    <div class="actions">
      <button :disabled="store.loading" @click="store.fetchAll">Reload</button>
    </div>
    <p v-if="store.error" style="color:red">{{ store.error }}</p>
    <SalesTable :sales="store.items" />

    <h3 style="margin-top:1rem">Create Sale</h3>
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
      <button type="submit" :disabled="store.loading">Create</button>
    </form>
  </section>
</template>

<script setup lang="ts">
import { onMounted, reactive } from 'vue'
import { useSalesStore } from '@/stores/sales'
import SalesTable from '@/components/SalesTable.vue'

const store = useSalesStore()

const form = reactive({
  seller_id: undefined as number | undefined,
  amount: undefined as number | undefined,
  date: new Date().toISOString().slice(0, 10),
})

onMounted(() => {
  store.fetchAll()
})

async function submit() {
  if (!form.seller_id || form.amount === undefined || !form.date) return
  await store.add({
    seller_id: form.seller_id,
    amount: form.amount,
    sale_date: form.date,
  })
  form.amount = undefined
}
</script>

<style scoped>
.actions { margin-bottom: .5rem; }
.row { display: flex; gap: 1rem; margin: .5rem 0; }
label { display: flex; flex-direction: column; font-size: .9rem; }
input { padding: .4rem .5rem; }
</style>
