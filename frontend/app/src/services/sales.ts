import api from './api'
import type { Paginated } from '@/types/models'
import type { Sale } from '@/types/sale'

export type CreateSalePayload = {
  amount: number
  sale_date: string
  seller_id: number
}

export async function listSales() {
  const { data } = await api.get<Sale[] | Paginated<Sale>>('/sales')
  if (Array.isArray(data)) return data
  return data.data
}

export async function createSale(payload: CreateSalePayload) {
  return api.post('/sales', payload)
}

export default { listSales, createSale }
