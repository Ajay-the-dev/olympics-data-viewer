// resources/js/router/index.js
import { createRouter, createWebHistory } from 'vue-router'
import Upload from '../components/home.vue'
import List from '../components/list.vue'


const routes = [
  { path: '/', name: 'home', component: Upload },
  { path: '/list', name: 'list', component: List },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router
