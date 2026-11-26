import { describe, it, expect, vi, afterEach } from 'vitest'
import { getProducts, createProduct, deleteProduct, ApiError } from '../products'

function mockFetch(status, body) {
  const fn = vi.fn().mockResolvedValue({
    ok: status >= 200 && status < 300,
    status,
    json: async () => body,
  })
  vi.stubGlobal('fetch', fn)
  return fn
}

afterEach(() => vi.unstubAllGlobals())

describe('api produk', () => {
  it('getProducts mengembalikan array dari API', async () => {
    mockFetch(200, [{ id: 1, name: 'Laptop' }])
    expect(await getProducts()).toEqual([{ id: 1, name: 'Laptop' }])
  })

  it('createProduct mengirim POST dengan body JSON', async () => {
    const fn = mockFetch(201, { id: 2 })
    await createProduct({ name: 'Mouse', price: 50000 })
    const [, options] = fn.mock.calls[0]
    expect(options.method).toBe('POST')
    expect(JSON.parse(options.body)).toEqual({ name: 'Mouse', price: 50000 })
  })

  it('deleteProduct mengirim DELETE', async () => {
    const fn = mockFetch(200, { message: 'ok' })
    await deleteProduct(3)
    const [url, options] = fn.mock.calls[0]
    expect(url).toMatch(/\/products\/3$/)
    expect(options.method).toBe('DELETE')
  })

  it('melempar ApiError berisi pesan validasi saat 422', async () => {
    mockFetch(422, { message: 'invalid', errors: { name: ['Nama wajib diisi'] } })
    await expect(createProduct({})).rejects.toMatchObject({
      status: 422,
      errors: { name: ['Nama wajib diisi'] },
    })
    await expect(createProduct({})).rejects.toBeInstanceOf(ApiError)
  })
})
