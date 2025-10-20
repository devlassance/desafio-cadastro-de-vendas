import { defineStore } from 'pinia'
import type { Sale } from '../types/sale'
import { listSales, createSale, type CreateSalePayload } from '../services/sales'

export const useSalesStore = defineStore('sales', {
  state: () => ({
    items: [] as Sale[],
    loading: false,
    error: '' as string,
  }),
  actions: {
    async fetchAll() {
      this.loading = true
      this.error = ''
      try {
        this.items = await listSales()
      } catch (e: any) {
        this.error = e?.response?.data?.message || e?.message || 'Failed to load sales'
      } finally {
        this.loading = false
      }
    },
    async add(payload: CreateSalePayload) {
      this.error = ''
      try {
        await createSale(payload)
        await this.fetchAll()
      } catch (e: any) {
        this.error = e?.response?.data?.message || e?.message || 'Failed to create sale'
        throw e
      }
    },
  },
})
