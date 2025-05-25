const API_BASE = 'http://localhost:8000/api'

export async function fetchProducts() {
  const res = await fetch(`${API_BASE}/products`)
  return await res.json()
}
