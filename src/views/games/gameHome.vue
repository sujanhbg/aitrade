<script setup></script>

<template>
    <topnav :mname="this.mname" :back="this.back"></topnav>
    <div class="p-2" @click="this.crmenu()">
        <div class="row" id="mainbody">
            <div class="col-12">
                <h2>Games</h2>


            </div>
            <div class="col-12">

            </div>
            <div class="col-12 mb-2">
                <div class="card p-2 text-center" @click="this.$router.push('/trade')">
                    <span class="material-symbols-outlined" style="font-size: 48px;">
                        monitoring
                    </span>
                    <b>6 Cube</b><br>
                    <p>Traditional Indian Game</p>
                </div>
            </div>
            <div class="col-12 mb-2">
                <div class="card p-2 text-center" @click="this.$router.push('/trade')">
                    <span class="material-symbols-outlined" style="font-size: 48px;">
                        monitoring
                    </span> Crypto
                </div>
            </div>

        </div>

    </div>
</template>
<script>

export default {
    name: "games",
    data() {
        return {
            back: '/',
            mname: 'Games'
        }
    },
    methods: {
        goto(link) {
            this.$router.push(link);
        }
    },
    mounted() {
        this.crmenu();
    },
};
</script>
<style scoped>
h1 {
    font-size: 3rem !important;
}

.middle-card {
    background-image: linear-gradient(135deg, #a5f6c3 10%, #3797ff 100%);
}

[data-bs-theme="dark"] .middle-card {
    background-image: linear-gradient(135deg, #017b2e 10%, #0a417d 100%);
}
</style>
