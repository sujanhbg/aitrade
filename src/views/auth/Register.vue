<template>
  <div class="bodytitle gr-royal">
    <div class="container text-center">
      <h1 class="text-5xl">
        <img src="../../assets/logo.webp" title="Kalni-IT Logo" class="brand-logo img-fluid"></h1>
      <h2 class="text-3xl">Signup</h2>
    </div>
  </div>


  <div class="mt-3 d-flex justify-content-center align-items-center">
    <div class="container">
      <div class="row d-flex justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
          <div class="card">
            <div class="card-body p-5">
              <div class="mb-3">
                <label for="email" class="form-label ">Email address</label>
                <input type="email"
                       class="form-control"
                       id="email"
                       name="email"
                       placeholder="name@example.com"
                       form="loginForm">
              </div>
              <div class="mb-3">
                <label for="password" class="form-label ">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="*******"
                       form="loginForm" autocomplete="password">
              </div>
              <div class="d-grid">
                <button class="btn btn-outline-danger" type="button" form="loginForm"
                        @click="submit">Register
                </button>
              </div>
              <div>
                <p class="mb-0  text-center">Don't have an account? <a href="/login"
                                                                       class="text-primary fw-bold">Login</a></p>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>

import {ref} from "vue";

const formData = ref({})
const submit = () => {
  console.log('submit')
}
</script>