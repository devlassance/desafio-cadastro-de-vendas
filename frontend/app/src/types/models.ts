export interface Paginated<T> {
  current_page: number
  data: T[]
  first_page_url: string
  from: number | null
  last_page: number
  last_page_url: string
  next_page_url: string | null
  prev_page_url: string | null
  per_page: number
  to: number | null
  total: number
}
