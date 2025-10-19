export interface Paginated<T> {
  data: T[]
  current_page: number
  last_page: number
  per_page: number
  total: number
}
export interface Seller {
  id: number
  name: string
  email: string
  created_at?: string
  updated_at?: string
}

export interface Sale {
  id: number
  seller_id: number
  value: number
  created_at?: string
  updated_at?: string
}

export interface Paginated<T> {
  data: T[]
  current_page: number
  last_page: number
  per_page: number
  total: number
}
