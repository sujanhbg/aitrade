<template>
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Confirm</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{ msg }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-danger" @click="ConfirmEDx(postID)"
                        data-bs-dismiss="modal">{{ confirmbtn }}</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: 'confirmBox',
    props: {
        msg: String,
        confirmbtn: String,
        postID: Number
    }, methods: {
        ConfirmEDx(argv) {
            this.$parent.ConfirmCallback(argv);
        }
    }, mounted() {
        const myModal = new bootstrap.Modal('#staticBackdrop');
        myModal.show();
    }
}

</script>