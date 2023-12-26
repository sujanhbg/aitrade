
<template>
    <div class="topcard">
        <div class="row">
            <div class="col-2 text-start"><button class="btn btn-link" @click="this.$router.push(this.back)"><span
                        class="material-symbols-outlined">
                        arrow_back
                    </span></button>
            </div>
            <div class="col-8 text-center fs-4">{{ mname }}</div>
            <div class="col-2 text-end">
                <button class="btn btn-link" @click="rightnav()">
                    <span class="material-symbols-outlined">
                        more_vert
                    </span>
                </button>
            </div>
        </div>
    </div>
    <div class="mbody">
        <rightnav></rightnav>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import rightnav from "../components/rightnav.vue";
import Axios from "@/services/axios";
const props = defineProps({
    mname: String,
    back: String
});
var islogedin = false;
const userName = localStorage.getItem('name');
const userPhoto = localStorage.getItem('photo');
var theme = "light";
var uid = 0;

function isLoggedIn() {
    var token = localStorage.getItem('token') ? localStorage.getItem('token') : '0';
    const headers = { Authorization: `Bearer ${token}` };
    Axios.get("?app=auth&opt=is_in", { headers }).then((dt) => {
        if (dt.data.status === 'success') {
            islogedin = 'true';
            uid = dt.data.uid;
        } else {
            islogedin = 'false';
            localStorage.setItem('log', false);
        }
    });
}
function logout() {
    localStorage.removeItem('token');
    localStorage.removeItem('name');
    localStorage.removeItem('photo');
    localStorage.setItem('log', false);
    islogedin = false;
    uid = '';
    loaderShow = false;
}

onMounted(() => {
    if (localStorage.getItem("theme")) {
        theme = localStorage.getItem("theme");
        document.getElementById('htmlbody').setAttribute("data-bs-theme", theme);
    }
    const nav = document.getElementById('topnav');
    window.onscroll = function () {
        if (document.body.scrollTop >= 60 || document.documentElement.scrollTop >= 60) {
            nav.classList.add("nav-colored");
            nav.classList.remove("nav-transparent");
        }
        else {
            nav.classList.add("nav-transparent");
            nav.classList.remove("nav-colored");
        }
    };
    isLoggedIn();
});
</script>

<style scoped>
.material-symbols-outlined {
    vertical-align: middle;
    font-size: 32px;
}

.brand-logo {
    max-height: 50px;
}

header {
    position: fixed;
    top: 0px;
    width: 100% !important;
    z-index: 111;
}
</style>