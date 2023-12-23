
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

<script>
import rightnav from "../components/rightnav.vue";
export default {
    name: 'topnav',
    components: { rightnav },
    props: {
        mname: String,
        back: String
    },
    data() {
        return {

            topLinks: [
                { name: 'Home', link: '/', icon: 'home' },
                { name: 'Blog', link: '/blogs', icon: 'article' },
                { name: 'Article', link: '/article', icon: 'pages' },
                { name: 'Contact Me', link: '/contact_me', icon: 'email' }
            ],
            islogedin: false,
            userName: localStorage.getItem('name'),
            userPhoto: localStorage.getItem('photo'),
        }
    },
    methods: {

        async isLoggedIn() {
            this.loaderShow = true;
            fetch(this.$api + "app=auth&opt=is_in")
                .then((ress) => ress.json())
                .then((dt) => {
                    if (dt === 'null' || dt.status === 'error') {
                        this.islogedin = 'false';
                        localStorage.setItem('log', false);
                    } else {
                        this.islogedin = 'true';
                        this.uid = dt.uid;
                        this.loaderShow = false;
                    }
                });
        }, logout() {
            localStorage.removeItem('token');
            localStorage.removeItem('name');
            localStorage.removeItem('photo');
            localStorage.setItem('log', false);
            this.islogedin = false;
            this.uid = '';
            this.loaderShow = false;
        }
    },
    mounted() {
        if (localStorage.getItem("theme")) {
            this.theme = localStorage.getItem("theme");
            document.getElementById('htmlbody').setAttribute("data-bs-theme", this.theme);
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
        this.isLoggedIn();
    }
}

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