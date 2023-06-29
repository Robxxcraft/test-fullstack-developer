import { reactive } from 'vue';

const state = reactive({
    authenticated: false,
    user: null,
})

const mutations = {
    async getUser() {
        await axios.get('/api/user').then(res => {
            state.authenticated = true
            state.user = res.data.data
        }).catch(err => {
            console.log(err)
        });
    },
}

export default {
    state, mutations
}