import { describe, it, expect } from 'vitest'
import { formatRupiah } from '../format'

describe('formatRupiah', () => {
  it('memformat angka ke rupiah', () => {
    expect(formatRupiah(15000000)).toBe('Rp 15.000.000')
  })

  it('menerima string desimal dari API Laravel', () => {
    expect(formatRupiah('10000000.00')).toBe('Rp 10.000.000')
  })
})
