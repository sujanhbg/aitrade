<template>
    <h1>Logging out....</h1>
</template>
<script>
export default {
    methods: {
        logout() {
            localStorage.clear();
            this.$router.push('/');
        }
    },
    mounted() {
        this.logout();
    }
}
</script>