import { reactive } from 'vue';

const state = reactive({
    toast: null,
})

const mutations = {
    setToast(payload) {
        state.toast = payload
    },
}

export default {
    state, mutations
}