<template>
    <div class="p-3 right-nav " id="rnav">
        <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-decoration-none">
            <svg class="bi me-2" width="40" height="32">
                <use xlink:href="#bootstrap"></use>
            </svg>
            <span class="fs-4">Sidebar</span>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="#" class="nav-link active" aria-current="page">
                    <svg class="bi me-2" width="16" height="16">
                        <use xlink:href="#home"></use>
                    </svg>
                    Home
                </a>
            </li>
            <li>
                <a href="#" class="nav-link">
                    <svg class="bi me-2" width="16" height="16">
                        <use xlink:href="#speedometer2"></use>
                    </svg>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="#" class="nav-link">
                    <svg class="bi me-2" width="16" height="16">
                        <use xlink:href="#table"></use>
                    </svg>
                    Orders
                </a>
            </li>
            <li>
                <a href="#" class="nav-link">
                    <svg class="bi me-2" width="16" height="16">
                        <use xlink:href="#grid"></use>
                    </svg>
                    Products
                </a>
            </li>
            <li>
                <a href="#" class="nav-link">
                    <svg class="bi me-2" width="16" height="16">
                        <use xlink:href="#people-circle"></use>
                    </svg>
                    Customers
                </a>
            </li>
        </ul>
        <hr>
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="dropdownUser2"
                data-bs-toggle="dropdown" aria-expanded="false">
                <img :src="this.$image + userPhoto" alt="" width="32" height="32" class="rounded-circle me-2">
                <strong>{{ userName }}</strong>
            </a>
            <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser2" style="">
                <li><a class="dropdown-item" href="#">Profile</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li v-if="theme === 'dark'" @click="chtheme('light')"><a class="dropdown-item" href="#"><span
                            class="material-symbols-outlined mx-auto mb-1">light_mode</span>Light Mode</a></li>
                <li v-else @click="chtheme('dark')"><a class="dropdown-item" href="#"><span
                            class="material-symbols-outlined mx-auto mb-1">dark_mode</span>Dark Mode</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="/logout">Sign out</a></li>
            </ul>
        </div>
    </div>

    <!--bottom navigations-->
    <div class="bottomnav">
        <div class="row">
            <div class="col-2">
                <button class="btn btn-link">
                    <span class="material-symbols-outlined">
                        account_balance_wallet
                    </span>
                </button>
            </div>
            <div class="col-2">
                <button class="btn btn-link">
                    <span class="material-symbols-outlined">
                        person
                    </span>
                </button>
            </div>
            <div class="col-2">
                <button class="btn btn-link">
                    <span class="material-symbols-outlined">
                        currency_exchange
                    </span>
                </button>
            </div>
            <div class="col-2">
                <button class="btn btn-link">
                    <span class="material-symbols-outlined">
                        casino
                    </span>
                </button>
            </div>
            <div class="col-2">
                <button class="btn btn-link">
                    <span class="material-symbols-outlined">
                        share
                    </span>
                </button>
            </div>
            <div class="col-2">
                <button class="btn btn-link">
                    <span class="material-symbols-outlined">
                        notifications
                    </span>
                </button>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: "rightnav",
    data() {
        return {
            userName: localStorage.getItem('name'),
            userPhoto: localStorage.getItem('photo'),
            theme: 'dark',
        }
    },
    methods: {
        chtheme(themename) {
            document.getElementById('htmlbody').setAttribute("data-bs-theme", themename);
            this.theme = themename;
            localStorage.setItem("theme", themename);
            //console.warn("Change to:"+themename);
        },
    }
}
</script>
<style scoped>
.bottomnav {
    position: fixed;
    bottom: 0px;
    left: 0px;
    width: 100%;
    border-top: 1px solid #ccc;
}

.bottomnav .material-symbols-outlined {
    font-size: 32px;
}
</style>