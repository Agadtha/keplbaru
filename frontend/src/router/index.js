import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import ProductsView from '../views/ProductsView.vue'
import ProductFormView from '../views/ProductFormView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    { path: '/', name: 'home', component: HomeView },
    { path: '/products', name: 'products', component: ProductsView },
    { path: '/products/create', name: 'products.create', component: ProductFormView },
    { path: '/products/:id/edit', name: 'products.edit', component: ProductFormView, props: true },
  ],
})

export default router
