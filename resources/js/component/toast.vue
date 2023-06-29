<template>
    <div class="toast-wrap position-fixed w-100 d-flex justify-content-center">
        <div :class="`${toastType} shadow fw-semibold mx-6 rounded px-4 py-2 d-flex align-items-center`">
            <template v-if="type == 'success'">
                <div class="me-2 fs-5">
                    <i class="fa-regular fa-circle-check"></i>
                </div>
            </template>
            <template v-else-if="type == 'error'">
                <div class="me-2 fs-5">
                    <i class="fa-regular fa-circle-xmark"></i>
                </div>
            </template>
            <slot name="text"></slot>
        </div>
    </div>
</template>

<script>
import { ref } from 'vue';
export default {
    setup(props) {
        const toastType = ref('')
        switch (props.type) {
            case 'success':
                toastType.value = 'bg-secondary text-primary'
                break;

            case 'error':
                toastType.value = 'bg-danger text-white'
                break;
        
            default:
                break;
        }

        return { toastType }
    },
    props: ['type']
}
</script>

<style>
.toast-enter-from, 
.toast-leave-to {
    opacity: 0;
    transform: translateY(-60px);
}
/* .toast-enter-to, 
.toast-leave-from {
    opacity: 1;
    transform: translateY(0);
} */
.toast-enter-active,
.toast-leave-active{
    transition: all 0.5s ease-in-out;
}
.toast-wrap {
    z-index: 20;
    top: 10%;
}
</style>