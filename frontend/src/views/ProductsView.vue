<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, RouterLink } from 'vue-router'
import { formatRupiah } from '../utils/format'
import { getProducts, deleteProduct } from '../api/products'

const route = useRoute()

const products = ref([])
const loading = ref(true)
const error = ref('')
const success = ref('')

// Pesan sukses dikirim dari halaman form lewat ?status=...
const messages = {
  created: 'Produk berhasil ditambahkan.',
  updated: 'Produk berhasil diperbarui.',
}
const flash = computed(() => messages[route.query.status] ?? '')

async function load() {
  try {
    products.value = await getProducts()
    error.value = ''
  } catch (e) {
    error.value = 'Gagal mengambil data dari API: ' + e.message
  } finally {
    loading.value = false
  }
}

async function remove(p) {
  if (!confirm(`Hapus produk "${p.name}"?`)) return
  success.value = ''
  try {
    await deleteProduct(p.id)
    success.value = 'Produk berhasil dihapus.'
    await load()
  } catch (e) {
    error.value = 'Gagal menghapus: ' + e.message
  }
}

onMounted(load)
</script>

<template>
  <div class="header">
    <h1>Produk</h1>
    <RouterLink :to="{ name: 'products.create' }" class="btn-primary">+ Tambah Produk</RouterLink>
  </div>

  <p v-if="flash || success" class="success">{{ success || flash }}</p>
  <p v-if="error" class="error">{{ error }}</p>

  <p v-if="loading">Memuat...</p>
  <p v-else-if="!error && products.length === 0">Belum ada produk.</p>
  <table v-else-if="products.length">
    <thead>
      <tr>
        <th>Nama</th>
        <th>Harga</th>
        <th>Deskripsi</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="p in products" :key="p.id">
        <td>{{ p.name }}</td>
        <td>{{ formatRupiah(p.price) }}</td>
        <td>{{ p.description }}</td>
        <td class="actions">
          <RouterLink :to="{ name: 'products.edit', params: { id: p.id } }">Ubah</RouterLink>
          <button type="button" @click="remove(p)">Hapus</button>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<style scoped>
.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.btn-primary {
  background: hsla(160, 100%, 37%, 1);
  color: #fff;
  padding: 0.5rem 1rem;
  border-radius: 6px;
  font-weight: 600;
}

@media (hover: hover) {
  .btn-primary:hover {
    background: hsla(160, 100%, 30%, 1);
  }
}

table {
  border-collapse: collapse;
  width: 100%;
}

th,
td {
  text-align: left;
  padding: 0.5rem 0.6rem;
  border-bottom: 1px solid var(--color-border);
}

.actions {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.error {
  color: #d33;
}

.success {
  color: hsla(160, 100%, 30%, 1);
}
</style>
