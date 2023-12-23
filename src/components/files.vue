<template>
    <div class="container-fluid">
        <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
            id="filemModal">
            <div class="modal-dialog modal-xl">
                <div class="modal-content p-2">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">File Manager</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="row">
                        <div class="col col-4 p-1">
                            <strong>Folders</strong>
                            <hr>
                            <ul class="list-group">
                                <li v-for=" fl in folders" @click="setFolder(fl[0])"><span
                                        class="material-symbols-outlined">
                                        folder
                                    </span> {{ fl[1] }}</li>
                            </ul>

                        </div>
                        <div class="col col-8 p-1">
                            <div class="row text-center text-lg-start">
                                <div class="col-lg-3 col-md-4 col-6" v-for="fle in files">
                                    <a href="#" class="d-block mb-4 h-100">
                                        <img class="img-fluid img-thumbnail" :src="this.$imagethmb + fle[2] + '.' + fle[3]"
                                            :alt="fle[2]" @click="insertimage(this.$image + fle[2] + '.' + fle[3])"
                                            data-bs-dismiss="modal">
                                    </a>
                                </div>



                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>
<script>

export default {
    name: 'filem',
    data() {
        return {
            folders: [],
            files: [],
            folderid: 1
        }
    },
    methods: {
        Modal() {
            const modal = new bootstrap.Modal(document.getElementById('myModal'), { backdrop: "static", keyboard: false });
            modal.show();
        },
        getFolders() {
            fetch(this.$api + "app=files_folder")
                .then((ress) => ress.json())
                .then((dt) => {
                    this.folders = dt;
                });
        },
        getFiles(folder) {
            fetch(this.$api + "app=files_folder&opt=files&folder=" + folder)
                .then((ress) => ress.json())
                .then((dt) => {
                    this.files = dt;
                });
        },
        setFolder(folderID) {
            this.folderid = folderID;
        }, insertimage(imageurl) {
            const textfieldid = "defimage";
            document.getElementById(textfieldid).value = imageurl;
            document.getElementById(textfieldid + "_preview").src = imageurl;
            this.filesm = false
        }
    },
    mounted() {

        const myModal = new bootstrap.Modal('#filemModal');
        myModal.show();
        this.getFolders();
        this.getFiles(this.folderid);

    }
}
</script>