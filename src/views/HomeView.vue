<template>
    <homenav :mname="mname" :back="back"></homenav>
    <div class="p-2 mb-3" @click="this.crmenu()">
        <div class="row" id="mainbody">
            <div class="col-12">
                <h2>Welcome to {{ mname }}</h2>
                <h6>Trading, Invest and Gameplay from single plase</h6>


            </div>

        </div>
        <div class="row mb-3 fs-4 text-info">
            <div class="col-4">Balance:</div>
            <div class="col-4">{{ balance }}</div>
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
                <div class="card p-2 text-center cursor-pointer" @click="this.$router.push('/games')">
                    <img src="@/assets/poker.webp">
                    Games
                </div>
            </div>
            <div class="col-6 mb-2">
                <div class="card p-2 text-center cursor-pointer" @click="this.$router.push('/trade')">
                    <img src="@/assets/lucky.webp">
                    Lottery
                </div>
            </div>
            <div class="col-6 mb-2">
                <div class="card p-2 text-center cursor-pointer" @click="this.$router.push('/games')">
                    <img src="@/assets/betting.webp">
                    Bet
                </div>
            </div>

        </div>
    </div>
</template>
<script setup>
import { ref, onMounted } from 'vue'
import Axios from "@/services/axios";
import homenav from '../components/homenav.vue';
const back = '/home';
const mname = 'AIFX';
const token = localStorage.getItem('token') ? localStorage.getItem('token') : '0';
const headers = { Authorization: `Bearer ${token}` };
const balance = ref({});
onMounted(() => {
    Axios.get("?app=home&opt=userdata", { headers }).then((ress) => {
        balance.value = Number(ress.data.balance).toFixed(2);
    });
})
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
