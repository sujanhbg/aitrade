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
                <div class="card p-2 text-center" @click="this.$router.push('/games/6cube')" style="cursor: pointer;">

                    <b>Titas Table</b><br>
                    <p>Min bid: $10.00<br>Max bid: $100.00</p>
                    <h5>Gain: 6x</h5>
                    <p class=" text-warning">Instant transfer</p>
                </div>
            </div>
        </div>

    </div>
</template>
<script setup>
import { ref, onMounted } from 'vue'
import Axios from "@/services/axios";
const back = '/games';
const mname = "Tables";
onMounted(() => {

})

</script>