import api from './api'
import type { Paginated } from '../types/models'
import type { Seller } from '../types/seller'
import type { SellerSelect } from '../types/sellerSelect'
import type { SellerDetail } from '../types/sellerDetail'

export async function listSellers(page: number = 1): Promise<Seller[]> {
  const { data } = await api.get<Seller[] | Paginated<Seller>>(`/sellers?page=${page}`)
  if (Array.isArray(data)) return data
  return data.data
}

export async function listSellersForSelect(): Promise<SellerSelect[]> {
  const { data } = await api.get<SellerSelect[]>('sellers/for-select')
  if (Array.isArray(data)) return data
  return data
}

export async function getSeller(id: number): Promise<SellerDetail> {
  // Some backends wrap payloads as { data: SellerDetail }
  const resp = await api.get<SellerDetail | { data: SellerDetail }>(`/sellers/${id}`)
  const raw: any = (resp as any)?.data
  const unwrapped = (raw && typeof raw === 'object' && 'data' in raw) ? (raw as any).data : raw
  return unwrapped as SellerDetail
}

export async function createSeller(payload: { name: string; email: string }) {
  return api.post('/sellers', payload)
}

export default { listSellers, listSellersForSelect, getSeller, createSeller }