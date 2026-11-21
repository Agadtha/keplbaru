<script setup>
import { ref, onMounted } from 'vue'
import { formatRupiah } from '../utils/format'

const products = ref([])
const error = ref('')
const loading = ref(true)

onMounted(async () => {
  try {
    const res = await fetch(`${import.meta.env.VITE_API_URL}/products`, {
      headers: { Accept: 'application/json' },
    })
    if (!res.ok) throw new Error(`HTTP ${res.status}`)
    products.value = await res.json()
  } catch (e) {
    error.value = 'Gagal mengambil data dari API: ' + e.message
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <h1>Produk</h1>
  <p v-if="loading">Memuat...</p>
  <p v-else-if="error">{{ error }}</p>
  <p v-else-if="products.length === 0">Belum ada produk.</p>
  <ul v-else>
    <li v-for="p in products" :key="p.id">
      {{ p.name }} - {{ formatRupiah(p.price) }}
    </li>
  </ul>
</template>
