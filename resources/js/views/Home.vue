<template>
    <div class="px-md-5 pt-md-3 min-vh-100 d-flex flex-column">
        <Navbar />
        <div style="flex-grow: 1;" class="d-flex flex-column align-items-center justify-content-center">
            <div class="fs-3 fw-bold mb-2">Tes fullstack developer</div>
            <div class="d-flex" v-if="!user">
                <router-link to="/login" class="fs-5 btn btn-primary border fw-semibold me-3 text-white">Login</router-link>
                <router-link to="/register" class="fs-5 btn btn-outline-dark border fw-semibold">Register</router-link>
            </div>
            <div v-else>
                <div class="fs-5 mb-3">Welcome, <span class="fw-semibold">{{user.name}}</span></div>
                <div class="text-gray">Email : <span class="fw-semibold">{{ user.email }}</span></div>
                <div class="text-gray">Account Created : <span class="fw-semibold">{{ user.created_at }}</span></div>
            </div>
        </div>
    </div>
</template>

<script>
import store from '../store/auth'
import { computed } from 'vue'
import Navbar from '../component/navbar.vue'
export default {
    components: {
        Navbar
    },
    setup() {
        const user = computed(()=>{
            return store.state.user
        })

        return { user }
    },
}
</script>