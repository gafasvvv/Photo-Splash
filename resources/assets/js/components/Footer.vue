<template>
    <footer class="footer">
        <button v-if="isLogin" class="button button--link" @click="logout">
            Logout
        </button>
        <RouterLink v-else class="button button--link" to="/login">
            Login or Register
        </RouterLink>
    </footer>
</template>

<script>
import { mapState, mapGetters } from 'Vuex'

export default {
    computed: {
        ...mapState({
            apiStatus: state => state.auth.apiStatus
        }),
        ...mapGetters({
            isLogin: 'auth/check'
        }),
    },
    methods: {
        async logout (){
            await this.$store.dispatch('auth/logout')

            if (this.apiStatus) {
                this.$router.push('/login')
            }
        }
    }
}
</script>
