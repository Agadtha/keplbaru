<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import { useRouter, RouterLink } from 'vue-router'
import { ApiError, getProduct, createProduct, updateProduct } from '../api/products'

// id hanya ada pada route /products/:id/edit
const props = defineProps({ id: { type: String, default: null } })

const router = useRouter()
const isEditing = computed(() => props.id !== null)

const form = reactive({ name: '', price: '', description: '' })
const loading = ref(isEditing.value)
const saving = ref(false)
const error = ref('')
const fieldErrors = ref({})

onMounted(async () => {
  if (!isEditing.value) return
  try {
    const p = await getProduct(props.id)
    form.name = p.name
    form.price = Number(p.price)
    form.description = p.description ?? ''
  } catch (e) {
    error.value = e.status === 404 ? 'Produk tidak ditemukan.' : 'Gagal memuat produk: ' + e.message
  } finally {
    loading.value = false
  }
})

async function submit() {
  saving.value = true
  fieldErrors.value = {}
  error.value = ''

  const payload = {
    name: form.name,
    price: form.price,
    description: form.description || null,
  }

  try {
    if (isEditing.value) {
      await updateProduct(props.id, payload)
    } else {
      await createProduct(payload)
    }
    router.push({
      name: 'products',
      query: { status: isEditing.value ? 'updated' : 'created' },
    })
  } catch (e) {
    if (e instanceof ApiError && e.status === 422) {
      fieldErrors.value = e.errors
    } else {
      error.value = 'Gagal menyimpan: ' + e.message
    }
  } finally {
    saving.value = false
  }
}
</script>

<template>
  <h1>{{ isEditing ? 'Ubah Produk' : 'Tambah Produk' }}</h1>

  <p v-if="loading">Memuat...</p>
  <form v-else @submit.prevent="submit">
    <p v-if="error" class="error">{{ error }}</p>

    <label>
      Nama
      <input v-model="form.name" type="text" />
      <small v-if="fieldErrors.name" class="error">{{ fieldErrors.name[0] }}</small>
    </label>

    <label>
      Harga
      <input v-model="form.price" type="number" min="0" step="any" />
      <small v-if="fieldErrors.price" class="error">{{ fieldErrors.price[0] }}</small>
    </label>

    <label>
      Deskripsi
      <textarea v-model="form.description" rows="3"></textarea>
      <small v-if="fieldErrors.description" class="error">{{ fieldErrors.description[0] }}</small>
    </label>

    <div class="actions">
      <button type="submit" :disabled="saving">
        {{ saving ? 'Menyimpan...' : 'Simpan' }}
      </button>
      <RouterLink :to="{ name: 'products' }">Batal</RouterLink>
    </div>
  </form>
</template>

<style scoped>
form {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
  max-width: 480px;
}

label {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

input,
textarea {
  padding: 0.4rem;
  font: inherit;
}

.actions {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.error {
  color: #d33;
}
</style>
