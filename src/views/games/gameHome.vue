<template>
    <topnav :mname="mname" :back="back"></topnav>
    <div class="p-2" @click="this.crmenu()">
        <div class="row" id="mainbody">
            <div class="col-12">
                <h2>{{ mname }}</h2>
            </div>
            <div class="col-12">

            </div>
            <div class="col-12 mb-2">
                <div class="card p-2 text-center cursor-pointer" @click="this.$router.push('/games/6cube')">
                    <img src="@/assets/dice.webp">
                    <b>6 Cube</b><br>
                    <p>Traditional Indian Game</p>
                </div>
            </div>
        </div>

    </div>
</template>
<script setup>
import { ref, onMounted } from 'vue'
import Axios from "@/services/axios";
const back = '/';
const mname = "Games";
onMounted(() => {

})

</script>