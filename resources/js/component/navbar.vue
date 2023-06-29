<template>
    <nav class="navbar navbar-expand-lg bg-primary shadow-sm rounded-2">
        <div class="container-fluid px-4">
            <div class="navbar-brand text-white fs-5">
                <i class="fa-solid fa-id-card-clip"></i>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto ms-3 mb-2 mb-lg-0">
                <li class="nav-item me-2">
                    <router-link id="home" to="/" class="nav-link active text-white fw-semibold">Home</router-link>
                </li>
                <li class="nav-item">
                    <router-link id="contacts" to="/contacts" class="nav-link text-white fw-semibold">Contacts</router-link>
                </li>
                <div id="slider" class="position-absolute bottom-0 bg-white rounded-top-4 currentLink"></div>
            </ul>
            <template v-if="isAuthenticated">
                <div class="d-flex align-items-center me-4">
                    <div class="text-white d-flex align-items-center justify-content-center bg-light rounded-circle avatar me-2">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div class="text-white fw-semibold">{{ user.name }}</div>
                </div>
                <button @click="logout" class="btn btn-danger fw-semibold d-flex align-items-center">
                    Logout
                    <i class="ms-2 fa-solid fa-arrow-right-from-bracket"></i>
                </button>
            </template>
            </div>
        </div>
    </nav>
</template>

<script>
import store from '../store/auth'
import { computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'

export default {
    setup() {
        const route = useRoute()

        const changeTab = (el) => {
            let marker = document.getElementById('slider')
            marker.style.left = el.offsetLeft+'px'
            marker.style.width = el.offsetWidth+'px'
        }

        onMounted(()=>{
            if (route.path == 'home') {
                let el = document.getElementById('home')
                changeTab(el)
            } else if (route.path.includes('contacts')) {
                let el = document.getElementById('contacts')
                changeTab(el)
            }
        })

        const logout = ()=>{
            axios.get('/api/logout').then(()=>{
                window.location.href = '/'
            })
        }

        const isAuthenticated = computed(()=>{
            return store.state.authenticated
        })

        const user = computed(()=>{
            return store.state.user
        })

        return { logout, isAuthenticated, user }
    },
}
</script>

<style>
.currentLink {
    width: 54px;
    height: 3px;
}
.avatar {
    width: 36px;
    height: 36px;
}
</style>