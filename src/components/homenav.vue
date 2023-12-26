<template>
    <div class="topcard">
        <div class="row">
            <div class="col-2">&nbsp;</div>
            <div class="col-8 text-center fs-4">{{ mname }}</div>
            <div class="col-2 text-end">
                <button class="btn btn-link" @click="openRightnav()">
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
var theme = 'dark';
const topLinks = [
    { name: 'Home', link: '/', icon: 'home' },
    { name: 'Blog', link: '/blogs', icon: 'article' },
    { name: 'Article', link: '/article', icon: 'pages' },
    { name: 'Contact Me', link: '/contact_me', icon: 'email' }
];
var islogedin = false;
var userName = localStorage.getItem('name');
var userPhoto = localStorage.getItem('photo');
var uid = 0;


function chtheme(themename) {
    document.getElementById('htmlbody').setAttribute("data-bs-theme", themename);
    theme = themename;
    localStorage.setItem("theme", themename);
    //console.warn("Change to:"+themename);
}
async function isLoggedIn() {
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

function openRightnav() {
    const rn = document.getElementById('rnav');
    rn.style = "display:block";
}

onMounted(() => {
    if (localStorage.getItem("theme")) {
        theme = localStorage.getItem("theme") ? localStorage.getItem("theme") : 'dark';
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