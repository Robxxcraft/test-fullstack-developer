<template>
    <Transition name="slideY">
        <Toast v-if="toast" type="error">
        <template #text>
            Authenticated required
        </template>
        </Toast>
    </Transition>
    <div class="position-absolute vw-100 vh-100">
        <img src="../../assets/images/auth.jpg" class="auth-background img-fluid vw-100 vh-100" alt="Login">
    </div>
    <div class="vh-100 vw-100 d-flex justify-content-center align-items-center overflow-hidden">
        <div class="bg-white rounded w-auth-box overflow-hidden auth-box">
            <div class="d-flex text-center bg-white">
                <router-link to="/login" :class="`py-2 fw-bold col-6 auth-link ${currentRoute == '/login' ? 'auth-link-actived' : 'auth-link-left' }`">Login</router-link>
                <router-link to="/register" :class="`py-2 fw-bold col-6 auth-link auth-link ${currentRoute == '/register' ? 'auth-link-actived' : 'auth-link-right' }`">Register</router-link>
            </div>
            <div class="p-4">
                <div class="fs-5 fw-semibold mb-3 text-darkgray">Login</div>
                <form @submit.prevent="login">
                    <div class="pb-3 position-relative">
                        <label for="email" class="form-label"><span class="text-danger">*</span> Email</label>
                        <input @focus="clearErrors('email')" type="text" v-model="form.email" :class="`form-control mb-1 ${ errors.email ? 'is-invalid' : '' }`" placeholder="Enter email..." aria-label="email">
                        <div v-if="errors.email" class="invalid-feedback position-absolute bottom-0">{{errors.email[0]}}</div>
                    </div>
                    <div class="pb-3 position-relative">
                        <label for="password" class="form-label"><span class="text-danger">*</span> Password</label>
                        <input @focus="clearErrors('password')" type="password" v-model="form.password" :class="`form-control mb-1 ${ errors.password ? 'is-invalid' : '' } `" placeholder="Enter password..." aria-label="password">
                        <div v-if="errors.password" class="invalid-feedback position-absolute bottom-0">{{errors.password[0]}}</div>
                    </div>
                    <button type="submit" :disabled="loading" class="mt-3 btn btn-primary fw-semibold text-white w-100 d-flex justify-content-center">
                        <template v-if="loading">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: 0; display: block; shape-rendering: auto;" width="24" height="24" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
                                <circle cx="50" cy="50" fill="none" stroke="#ffffff" stroke-width="10" r="35" stroke-dasharray="164.93361431346415 56.97787143782138">
                                    <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" values="0 50 50;360 50 50" keyTimes="0;1"></animateTransform>
                                </circle>
                            </svg>
                        </template>
                        <template v-else>
                            Login
                        </template>    
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import { computed, onMounted, reactive, ref } from "vue"
import { useRoute } from 'vue-router'
import Toast from '../../component/toast.vue'
export default {
    components: {
        Toast
    },
    setup() {
        const route = useRoute()
        const toast = ref(false)
        const loading = ref(false)
        const form = reactive({
            email: null,
            password: null,
        })
        const currentRoute = computed(() => route.path)
        const errors = ref({})

        onMounted(()=>{
            if (route.query.requireAuth) {
                toast.value = true
                const removeToast = () => {
                    toast.value = false
                }
                setTimeout(removeToast, 4000)
            }
        })

        const login = async () => {
            loading.value = true
            await axios.get('/sanctum/csrf-cookie')
            await axios.post('/login', form).then(() => {
                loading.value = false
                window.location.href = "/"
            }).catch(error => {
                loading.value = false
                if (error.response.status === 422) {
                    errors.value = error.response.data.errors
                }
            })
        }
        
        const clearErrors = (column)=>{
            errors.value[column] = ''
        }

        return { form, login, errors, currentRoute, clearErrors, toast, loading }
    },
}
</script>
<style scoped>
.auth-background {
    object-fit: cover;
    z-index: -1;
}
.w-auth-box {
    width: 30%;
}
@media (max-width: 782px) {
    .w-auth-box {
        width: 80%;
    }
}
</style>