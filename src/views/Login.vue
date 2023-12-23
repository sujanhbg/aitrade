<template>
  <div class="bodytitle gr-royal">
    <div class="container text-center">

      <h1 class="text-5xl"><img src="@/assets/logo.webp" title="Kalni-IT Logo" class="brand-logo img-fluid "></h1>
      <h2 class="text-3xl">Login</h2>
    </div>
  </div>


  <div class="mt-3 d-flex justify-content-center align-items-center" v-if="islogedin == true">
    <div class="container">
      <h1>Welcome {{ username }}</h1>
    </div>
  </div>
  <div class="mt-3 d-flex justify-content-center align-items-center" v-else>
    <div class="container">
      <div class="row d-flex justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
          <div class="card">
            <div class="card-body p-5">
              <div id="ress" class="d-4 text-danger">{{ getData }}</div>
              <form class="mb-3 mt-md-4" id="loginForm" onsubmit="return false;">

                <div class="mb-3">
                  <label for="email" class="form-label ">Email address</label>
                  <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com"
                    form="loginForm">
                </div>
                <div class="mb-3">
                  <label for="password" class="form-label ">Password</label>
                  <input type="password" class="form-control" id="password" name="password" placeholder="*******"
                    form="loginForm" autocomplete="password">
                </div>
                <p class="small"><a class="text-primary" href="forget-password.html">Forgot password?</a></p>
                <div class="d-grid">
                  <button class="btn btn-outline-danger" type="button" form="loginForm"
                    @click="subminForm();">Login</button>
                </div>
              </form>
              <div>
                <p class="mb-0  text-center">Don't have an account? <a href="/signup" class="text-primary fw-bold">Sign
                    Up</a></p>
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
  name: "LoginForm",
  data() {
    return {
      getData: '',
      uid: '',
      status: 'error',
      islogedin: localStorage.getItem('log') == undefined ? 'false' : localStorage.getItem('log'),
      siteName: '',
      username: ''
    }
  }, mounted() {
    const hostName = window.location.hostname.split('.');
    this.siteName = hostName[0] === 'localhost' ? 'prapti' : hostName[0];
  }, methods: {
    subminForm() {
      let oFormElement = document.getElementById('loginForm');
      fetch(this.$api + "app=auth&opt=login", { method: "POST", body: new FormData(oFormElement) })
        .then(ress => ress.json())
        .then(dt => {
          if (dt.status == 'success') {
            localStorage.setItem('token', dt.token);
            this.getData = "Login Success";
            this.status = dt.status;
            this.uid = dt.uid;
            this.islogedin = true;
            this.username = dt.name;
            localStorage.setItem('name', dt.name);
            localStorage.setItem('photo', dt.photo);
            localStorage.setItem('log', true);
            this.menuItems();
            setTimeout(function () { location.replace("/"); }, 1000);
          } else {
            this.getData = dt.msg;
          }
        });
    },
    async getContent() {
      this.loaderShow = true;
      fetch(this.$api + "app=auth&opt=user")
        .then((ress) => ress.json())
        .then((dt) => {

        });
    }, async menuItems() {
      fetch(this.$api + `app=home&opt=menu`)
        .then((ress) => ress.text())
        .then((dt) => {
          localStorage.setItem('menuitm1', dt);
        });
    }
  }
}
</script>