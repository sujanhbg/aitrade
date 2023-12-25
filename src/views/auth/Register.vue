<template>
  <div class="bodytitle gr-royal">
    <div class="container text-center">
      <h1 class="text-5xl">
        <img src="../../assets/logo.webp" title="Kalni-IT Logo" class="brand-logo img-fluid"></h1>
      <h2 class="text-3xl">Signup</h2>
    </div>
  </div>

  <div v-if="Object.keys(errors).length" class="error text-center">
    <p class="text-danger" v-html="errors"></p>
  </div>

  <div class="mt-3 d-flex justify-content-center align-items-center">
    <div class="container">
      <div class="row d-flex justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
          <div class="card">
            <div class="card-body p-5">
              <div class="mb-3">
                <label for="first_name" class="form-label ">First name</label>
                <input type="text"
                       class="form-control"
                       id="first_name"
                       placeholder="John"
                       v-model="formData.firstname">
              </div>

              <div class="mb-3">
                <label for="last_name" class="form-label ">Last name</label>
                <input type="text"
                       class="form-control"
                       id="last_name"
                       placeholder="Doe"
                       v-model="formData.lastname">
              </div>

              <div class="mb-3">
                <label for="email" class="form-label ">Email address</label>
                <input type="email"
                       class="form-control"
                       id="email"
                       name="email"
                       placeholder="name@example.com"
                       v-model="formData.email">
              </div>
              <div class="mb-3">
                <label for="password" class="form-label ">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="*******"
                       v-model="formData.password" autocomplete="password">
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
import Axios from "@/services/axios";

const formData = ref({})
const errors = ref({})
const submit = () => {
  errors.value = {}
  Axios.post('?app=registration&opt=register_new', formData.value).then((response) => {
    console.log(response.data.status)
    if (response.data.status === 'error') {
      errors.value = response.data.msg
    }
  }).catch((error) => {
    console.log(error)
  })
}
</script>
