interface Seller {
    id: number
    name: string
}

export interface Sale {
  id: number
  amount: number
  commission: number
  sale_date: string
  seller: Seller
  created_at?: string
  updated_at?: string
}
