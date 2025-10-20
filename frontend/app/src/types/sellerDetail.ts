interface Sale {
      id: number
      amount: number
      commission: number
      sale_date: string
      created_at?: string
      updated_at?: string
}

export interface SellerDetail {
    id: number;
    name: string;
    email: string;
    created_at: string;
    updated_at: string;
    sales: Sale[];
}