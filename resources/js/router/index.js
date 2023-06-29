import { computed } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import store from '../store/auth'
const routes = [
  {
    path: '/',
    name: 'Home',
    component: () => import('../views/home.vue'),
    // props: true
  },
  // {
  //   path: '/:catchAll(.*)',
  //   name: 'NotFound',
  //   component: () => import('../views/notfound.vue'),
  // },
  {
    path: '/login',
    name: 'Login',
    component: () => import('../views/auth/login.vue'),
    meta: { guest: true }
  },
  {
    path: '/register',
    component: () => import('../views/auth/register.vue'),
    meta: { guest: true }
  },
  {
    path: '/contacts',
    name: 'ManageContacts',
    component: () => import('../views/contacts/manage.vue'),
    meta: { auth: true }
  },
  {
    path: '/contacts/create',
    component: () => import('../views/contacts/create.vue'),
    meta: { auth: true }
  },
  {
    path: '/contacts/edit/:id',
    component: () => import('../views/contacts/edit.vue'),
    meta: { auth: true }
  },
  
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(){
    window.scrollTo(0,0)
  }
})

router.beforeEach( async (to, from, next) => {
  await store.mutations.getUser()
  const isAuth = computed(()=> store.state.user)
  if (to.matched.some(record => record.meta.auth)) {
    if (isAuth.value) next()
    else next({path: "/login", query: { requireAuth: true }})
  } else if (to.matched.some(record => record.meta.guest)) {
    if (!isAuth.value) next()
    else next({path: "/"})
  } else {
    next()
  }
})

export default router
