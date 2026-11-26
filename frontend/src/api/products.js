const BASE = `${import.meta.env.VITE_API_URL}/products`

// Error khusus supaya form bisa menampilkan pesan validasi (422) dari Laravel
export class ApiError extends Error {
  constructor(status, message, errors = {}) {
    super(message)
    this.status = status
    this.errors = errors
  }
}

async function request(url, options = {}) {
  const res = await fetch(url, {
    ...options,
    headers: {
      Accept: 'application/json',
      ...(options.body ? { 'Content-Type': 'application/json' } : {}),
    },
  })

  const data = await res.json().catch(() => null)

  if (!res.ok) {
    throw new ApiError(
      res.status,
      data?.message || `HTTP ${res.status}`,
      data?.errors || {},
    )
  }
  return data
}

export const getProducts = () => request(BASE)

export const getProduct = (id) => request(`${BASE}/${id}`)

export const createProduct = (payload) =>
  request(BASE, { method: 'POST', body: JSON.stringify(payload) })

export const updateProduct = (id, payload) =>
  request(`${BASE}/${id}`, { method: 'PUT', body: JSON.stringify(payload) })

export const deleteProduct = (id) => request(`${BASE}/${id}`, { method: 'DELETE' })
