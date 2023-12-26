<template>
    <homenav :mname="mname" :back="back"></homenav>
    <div class="p-2" @click="this.crmenu()">
        <div class="row" id="mainbody">
            <div class="col-12">
                <h2>Welcome to {{ mname }}</h2>
                <h6>Trading, Invest and Gameplay from single plase</h6>


            </div>

        </div>
        <div class="row mb-3 fs-4 text-info">
            <div class="col-4">Balance:</div>
            <div class="col-4">12345.45</div>
            <div class="col-12">
                <button class="btn btn-sm btn-outline-primary"><span class="material-symbols-outlined">send_money</span>
                    Transfer</button>
                <button class="btn btn-sm btn-outline-primary"><span class="material-symbols-outlined">payments</span>
                    Withdrow</button>
                <button class="btn btn-sm btn-outline-primary"><span class="material-symbols-outlined">paid</span>
                    Deposit</button>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-6 mb-2">
                <div class="card p-2 text-center" @click="this.$router.push('/trade')">
                    <span class="material-symbols-outlined" style="font-size: 48px;">
                        monitoring
                    </span> Trade
                </div>
            </div>
            <div class="col-6 mb-2">
                <div class="card p-2 text-center" @click="this.$router.push('/games')">
                    <span class="material-symbols-outlined" style="font-size: 48px;">
                        casino
                    </span> Games
                </div>
            </div>
            <div class="col-6 mb-2">
                <div class="card p-2 text-center" @click="this.$router.push('/trade')">
                    <span class="material-symbols-outlined" style="font-size: 48px;">
                        monitoring
                    </span> Trade
                </div>
            </div>
            <div class="col-6 mb-2">
                <div class="card p-2 text-center" @click="this.$router.push('/games')">
                    <span class="material-symbols-outlined" style="font-size: 48px;">
                        casino
                    </span> Games
                </div>
            </div>

        </div>
    </div>
</template>
<script setup>
import { ref } from "vue";
import homenav from '../components/homenav.vue';
const back = '/home';
const mname = 'AIFX';
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
