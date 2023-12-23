<script setup></script>

<template>
    <div class="">
        <div class="topcard">
            <div class="row">
                <div class="col-2 text-start"><button class="btn btn-link" @click="this.$router.push(this.back)"><span
                            class="material-symbols-outlined">
                            arrow_back
                        </span></button>
                </div>
                <div class="col-8 text-center">{{ mname }}</div>
                <div class="col-2 text-end">
                    <button class="btn btn-link" @click="rightnav()">
                        <span class="material-symbols-outlined">
                            more_vert
                        </span>
                    </button>
                </div>
            </div>
        </div>

        <div class="row align-middle mbody">
            <h2>Welcome to AIFX</h2>
            <h6>Trading, Invest and Gameplay from single plase</h6>
        </div>
    </div>



    <div class="mbody">
        <div class="right-nav" id="rnav">
            <h1>Right Nav</h1>
        </div>
    </div>
</template>
<script>

export default {
    name: "HomeView",
    data() {
        return {
            back: '/home',
            mname: 'AIFX',
            moremn: []
        }
    },
    components: {},
    metaInfo: {
        meta: [
            { charset: 'utf-8' },
            { name: 'description', content: 'foo' }
        ]
    },
    methods: {
        goto(link) {
            this.$router.push(link);
        },
        rightnav() {
            const rn = document.getElementById('rnav');
            rn.style = "display:block";
        }
    },
    mounted() {

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
