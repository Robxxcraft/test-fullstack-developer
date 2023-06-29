<template>
    <div class="position-absolute vw-100 vh-100">
        <img src="../../assets/images/auth.jpg" class="img-fluid vw-100 vh-100" style="object-fit: cover; z-index: -1;" alt="">
    </div>
    <div class="vh-100 vw-100 d-flex justify-content-center align-items-center overflow-hidden">
        <div class="bg-white rounded w-auth-box overflow-hidden auth-box">
            <div class="d-flex text-center bg-white">
                <router-link to="/login" :class="`py-2 fw-bold col-6 auth-link ${currentRoute == '/login' ? 'auth-link-actived' : 'auth-link-left' }`">Login</router-link>
                <router-link to="/register" :class="`py-2 fw-bold col-6 auth-link auth-link ${currentRoute == '/register' ? 'auth-link-actived' : 'auth-link-right' }`">Register</router-link>
            </div>
            <div class="p-4">
                <div class="fs-5 fw-semibold mb-3">Register</div>
                <form @submit.prevent="register">
                    <div class="pb-3 position-relative">
                        <label for="name" class="form-label"><span class="text-danger">*</span> Name</label>
                        <input @focus="clearErrors('name')" type="text" v-model="form.name" :class="`form-control mb-1 ${ errors.name ? 'is-invalid' : '' }`" placeholder="Enter name" aria-label="name">
                        <div v-if="errors.name" class="invalid-feedback position-absolute bottom-0">{{errors.name[0]}}</div>
                    </div>
                    <div class="pb-3 position-relative">
                        <label for="email" class="form-label"><span class="text-danger">*</span> Email</label>
                        <input @focus="clearErrors('email')" type="text" v-model="form.email" :class="`form-control mb-1 ${ errors.email ? 'is-invalid' : '' }`" placeholder="Enter email" aria-label="email">
                        <div v-if="errors.email" class="invalid-feedback position-absolute bottom-0">{{errors.email[0]}}</div>
                    </div>
                    <div class="pb-3 position-relative">
                        <label for="password" class="form-label"><span class="text-danger">*</span> Password</label>
                        <input @focus="clearErrors('password')" type="password" v-model="form.password" :class="`form-control mb-1 ${ errors.password ? 'is-invalid' : '' } `" placeholder="Enter password" aria-label="password">
                        <div v-if="errors.password" class="invalid-feedback position-absolute bottom-0">{{errors.password[0]}}</div>
                    </div>
                    <div class="pb-3 position-relative">
                        <label for="password_confirmation" class="form-label"><span class="text-danger">*</span> Password Confirmation</label>
                        <input @focus="clearErrors('password')" type="password" v-model="form.password_confirmation" :class="`form-control mb-1 ${ errors.password_confirmation ? 'is-invalid' : '' } `" placeholder="Enter password confirmation" aria-label="password confirmation">
                        <div v-if="errors.password_confirmation" class="invalid-feedback position-absolute bottom-0">{{errors.password_confirmation[0]}}</div>
                    </div>
                    <button type="submit" :disabled="loading" class="mt-3 btn btn-primary fw-semibold w-100 text-white d-flex justify-content-center">
                        <template v-if="loading">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: 0; display: block; shape-rendering: auto;" width="24" height="24" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
                                <circle cx="50" cy="50" fill="none" stroke="#ffffff" stroke-width="10" r="35" stroke-dasharray="164.93361431346415 56.97787143782138">
                                    <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" values="0 50 50;360 50 50" keyTimes="0;1"></animateTransform>
                                </circle>
                            </svg>
                        </template>
                        <template v-else>
                            Register
                        </template>
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import { computed, reactive, ref } from "vue"
import { useRoute, useRouter } from "vue-router"
export default {
    setup() {
        const router = useRouter()
        const route = useRoute()
        const loading = ref(false)
        const form = reactive({
            name: null,
            email: null,
            password: null,
            password_confirmation: null,
        });
        const currentRoute = computed(() => route.path)
        const errors = ref({});
 
        const register = async () => {
            loading.value = true
            await axios.get('/sanctum/csrf-cookie')
            await axios.post('/register', form).then(() => {
                loading.value = false
                router.push('/login')
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

        return { form, register, errors, currentRoute, clearErrors, loading }
    },
}
</script>

<style>
.body-bg {
    background-image: linear-gradient(90deg, #7c88a8 0%, #c49185 75%);
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