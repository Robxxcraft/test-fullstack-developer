<template>
    <Transition name="slideY">
        <Toast v-if="toast" type="error">
            <template #text>
            {{toastContent}}
            </template>
        </Toast>
    </Transition>
    <div class="px-md-5 pt-md-3 min-vh-100 d-flex flex-column">
        <Navbar /> 
        <div class="my-5 card w-50 mx-auto border-0 shadow-sm">
            <div class="card-body bg-white">
                <div class="ps-3 fw-bold text-primary fs-5">Edit Contact - {{ route.params.id }}</div>
                <form @submit.prevent="updateContact" class="p-3">
                    <div class="pb-3 position-relative">
                        <label class="form-label"><span class="text-danger">*</span> Name</label>
                        <input @focus="clearErrors('name')" type="text" v-model="form.name" :class="`form-control mb-1 ${ errors.name ? 'is-invalid' : ''}`" placeholder="Enter name">
                        <div v-if="errors.name" class="invalid-feedback position-absolute bottom-0 ps-2">{{ errors.name[0] }}</div>
                    </div>
                    <div class="pb-3 position-relative">
                        <label class="form-label"><span class="text-danger">*</span> Email</label>
                        <input @focus="clearErrors('email')" type="text" v-model="form.email" :class="`form-control mb-1 ${ errors.email ? 'is-invalid' : ''}`" placeholder="Enter email">
                        <div v-if="errors.email" class="invalid-feedback position-absolute bottom-0 ps-2">{{ errors.email[0] }}</div>
                    </div>
                    <div class="pb-3 position-relative">
                        <label class="form-label"><span class="text-danger">*</span> Phone</label>
                        <input @focus="clearErrors('phone')" type="number" name="number" v-model="form.phone" :class="`form-control mb-1 ps-5 ${ errors.phone ? 'is-invalid' : ''}`" placeholder="Enter phone">
                        <div class="position-absolute p-2 codephone">+62</div>
                        <div v-if="errors.phone" class="invalid-feedback position-absolute bottom-0 ps-2">{{ errors.phone[0] }}</div>
                    </div>
                    <div class="mt-2 d-flex justify-content-end">
                        <router-link to="/contacts" class="btn btn-outline-dark fw-semibold me-3 text-darkgray">Back</router-link>
                        <button type="submit" :disabled="loading" class="btn btn-primary fw-semibold text-white d-flex justify-content-center">
                            <template v-if="loading">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: 0; display: block; shape-rendering: auto;" width="24" height="24" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
                                    <circle cx="50" cy="50" fill="none" stroke="#ffffff" stroke-width="10" r="35" stroke-dasharray="164.93361431346415 56.97787143782138">
                                        <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" values="0 50 50;360 50 50" keyTimes="0;1"></animateTransform>
                                    </circle>
                                </svg>
                            </template>
                            <template v-else>
                                Update
                            </template>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import { onMounted, reactive, ref } from 'vue'
import Navbar from '../../component/navbar.vue'
import Toast from '../../component/toast.vue'
import { useRoute, useRouter } from 'vue-router'
import toastStore from '../../store/toast'
export default {
    components: {
        Navbar,
        Toast
    },
    setup() {
        const toast = ref(false)
        const toastContent = ref('')
        const loading = ref(false)
        const form = reactive({
            name: '',
            email: '',
            phone: null,
        });
        const errors = ref({})
        const route = useRoute()
        const router = useRouter()

        onMounted(()=>{
            axios.get(`/api/contacts/${route.params.id}`).then(res=>{
                form.name = res.data.name
                form.email = res.data.email
                form.phone = res.data.phone
            })
        })

        const updateContact = () => {
            loading.value = true
            axios.post(`/api/contacts/${route.params.id}`, form).then(res=>{
                loading.value = false
                toastStore.mutations.setToast(res.data.success)
                router.push({name: 'ManageContacts', params: {toast: res.data.success}});
            }).catch(err => {
                loading.value = false
                toast.value = true
                toastContent.value = 'Some error occured'
                const removeToast = () => {
                    toastContent.value = ''
                    toast.value = false
                }
                setTimeout(removeToast, 3000)
                window.scrollTo(0,0)
                
                if (err.response.status === 422) {
                    errors.value = err.response.data.errors
                }
            })
        }

        const clearErrors = (column)=>{
            errors.value[column] = ''
        }

    return { toast, toastContent, errors, form, updateContact, route, clearErrors, loading }
    },
}
</script>

<style>
.codephone {
    bottom: 22%;
    left: 1%;
}
</style>